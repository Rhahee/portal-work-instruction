<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\WorkInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManageWorkInstructionController extends Controller
{
    public function index(Request $request) { return view('manage.instructions.index', ['instructions' => WorkInstruction::with(['category', 'author', 'deletionRequester'])->when(! $request->user()->isAdmin(), fn ($q) => $q->where('author_id', $request->user()->id))->latest()->paginate(15)]); }
    public function create() { return view('manage.instructions.form', ['instruction' => new WorkInstruction(), 'categories' => Category::orderBy('name')->get()]); }
    public function store(Request $request) { $instruction = new WorkInstruction(['author_id' => $request->user()->id]); return $this->save($request, $instruction); }
    public function edit(Request $request, WorkInstruction $instruction) { $this->authorizeOwner($request, $instruction); return view('manage.instructions.form', ['instruction' => $instruction, 'categories' => Category::orderBy('name')->get()]); }
    public function update(Request $request, WorkInstruction $instruction) { return $this->save($request, $instruction); }
    public function destroy(Request $request, WorkInstruction $instruction)
    {
        $this->authorizeOwner($request, $instruction);

        if ($request->user()->isAdmin()) {
            $this->deleteInstruction($instruction);
            return redirect()->route('manage.instructions.index')->with('status', 'WI dihapus.');
        }

        abort_if($instruction->deletion_status === 'pending', 422, 'Permintaan hapus sudah menunggu keputusan Admin.');
        $instruction->update([
            'deletion_status' => 'pending',
            'deletion_requested_at' => now(),
            'deletion_requested_by' => $request->user()->id,
            'deletion_review_notes' => null,
            'deletion_reviewed_at' => null,
            'deletion_reviewed_by' => null,
        ]);

        return redirect()->route('manage.instructions.index')->with('status', 'Permintaan hapus telah diajukan ke Admin.');
    }

    public function reviewDeletion(Request $request, WorkInstruction $instruction)
    {
        abort_unless($request->user()->isAdmin(), 403);
        abort_unless($instruction->deletion_status === 'pending', 422, 'WI belum diajukan untuk dihapus.');
        $data = $request->validate(['decision' => ['required', 'in:approved,rejected'], 'deletion_review_notes' => ['nullable', 'string', 'max:2000']]);
        if ($data['decision'] === 'rejected' && blank($data['deletion_review_notes'] ?? null)) return back()->withErrors(['deletion_review_notes' => 'Catatan penolakan wajib diisi.']);

        if ($data['decision'] === 'approved') {
            $this->deleteInstruction($instruction);
            return redirect()->route('manage.instructions.index')->with('status', 'Permintaan disetujui; WI telah dihapus.');
        }

        $instruction->update([
            'deletion_status' => 'rejected',
            'deletion_review_notes' => $data['deletion_review_notes'],
            'deletion_reviewed_at' => now(),
            'deletion_reviewed_by' => $request->user()->id,
        ]);
        return redirect()->route('manage.instructions.index')->with('status', 'Permintaan hapus ditolak dengan catatan untuk penulis.');
    }
    public function review(Request $request, WorkInstruction $workInstruction)
    {
        abort_unless($request->user()->isAdmin(), 403);
        abort_unless($workInstruction->status === 'pending_review', 422, 'WI belum diajukan untuk review.');
        $data = $request->validate(['decision' => ['required', 'in:published,rejected'], 'review_notes' => ['nullable', 'string', 'max:2000']]);
        if ($data['decision'] === 'rejected' && blank($data['review_notes'] ?? null)) return back()->withErrors(['review_notes' => 'Catatan penolakan wajib diisi.']);
        $workInstruction->update(['status' => $data['decision'], 'review_notes' => $data['review_notes'] ?? null, 'reviewed_by' => $request->user()->id, 'reviewed_at' => now(), 'published_at' => $data['decision'] === 'published' ? now() : null]);
        return redirect()->route('manage.instructions.index')->with('status', $data['decision'] === 'published' ? 'WI disetujui dan diterbitkan.' : 'WI ditolak dengan catatan untuk penulis.');
    }
    public function upload(Request $request)
    {
        $request->validate(['image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
        $path = $request->file('image')->store('wi-images', 'public');
        return response()->json(['url' => '/storage/'.$path]);
    }
    private function save(Request $request, WorkInstruction $instruction)
    {
        $this->authorizeOwner($request, $instruction);
        abort_if($instruction->exists && $instruction->deletion_status === 'pending', 422, 'WI sedang menunggu keputusan penghapusan Admin.');
        $allowedStatuses = $request->user()->isAdmin() ? ['draft', 'published'] : ['draft', 'pending_review'];
        $data = $request->validate(['title' => ['required', 'string', 'max:180'], 'excerpt' => ['nullable', 'string', 'max:500'], 'category_id' => ['required', 'exists:categories,id'], 'status' => ['required', 'in:'.implode(',', $allowedStatuses)], 'content_json' => ['nullable', 'json'], 'content_html' => ['nullable', 'string', 'max:500000'], 'attachment_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:15360']]);
        Category::findOrFail($data['category_id']);
        $data['slug'] = $this->uniqueSlug($data['title'], $instruction->id);
        $data['content_html'] = $this->sanitize($data['content_html'] ?? '');
        $data['content_json'] = isset($data['content_json']) ? json_decode($data['content_json'], true, 512, JSON_THROW_ON_ERROR) : null;
        $data['published_at'] = $data['status'] === 'published' ? ($instruction->published_at ?? now()) : null;
        if (! $request->user()->isAdmin()) { $data['review_notes'] = null; $data['reviewed_by'] = null; $data['reviewed_at'] = null; }
        if ($request->hasFile('attachment_pdf')) {
            $file = $request->file('attachment_pdf');
            $data['attachment_path'] = $file->store('wi-attachments', 'local');
            $data['attachment_name'] = Str::limit(preg_replace('/[^A-Za-z0-9._ -]/', '_', $file->getClientOriginalName()), 180, '');
        }
        $previousAttachment = $instruction->attachment_path;
        $instruction->fill($data)->save();
        if ($request->hasFile('attachment_pdf') && $previousAttachment && $previousAttachment !== $instruction->attachment_path) Storage::disk('local')->delete($previousAttachment);
        return redirect()->route('manage.instructions.index')->with('status', $data['status'] === 'pending_review' ? 'WI diajukan untuk review Admin.' : 'WI disimpan.');
    }
    private function deleteInstruction(WorkInstruction $instruction): void { if ($instruction->attachment_path) Storage::disk('local')->delete($instruction->attachment_path); $instruction->delete(); }
    private function authorizeOwner(Request $request, WorkInstruction $instruction): void { abort_unless(! $instruction->exists || $request->user()->isAdmin() || $instruction->author_id === $request->user()->id, 403); }
    private function uniqueSlug(string $title, ?int $ignore): string { $base = Str::slug($title) ?: 'work-instruction'; $slug = $base; $n = 2; while (WorkInstruction::where('slug', $slug)->when($ignore, fn ($q) => $q->whereKeyNot($ignore))->exists()) $slug = "$base-$n++"; return $slug; }
    private function sanitize(string $html): string
    {
        $allowed = '<p><br><h1><h2><h3><h4><strong><em><u><s><mark><ul><ol><li><blockquote><pre><code><a><img><table><thead><tbody><tr><th><td><hr><div><span>';
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        foreach ($dom->getElementsByTagName('*') as $node) {
            for ($i = $node->attributes->length - 1; $i >= 0; $i--) {
                $attribute = $node->attributes->item($i);
                $name = strtolower($attribute->name); $value = trim($attribute->value);
                if (str_starts_with($name, 'on') || $name === 'style' || (($name === 'href' || $name === 'src') && !preg_match('#^(https?:|/|data:image/)#i', $value))) $node->removeAttribute($attribute->name);
            }
        }
        return strip_tags($dom->saveHTML(), $allowed);
    }
}
