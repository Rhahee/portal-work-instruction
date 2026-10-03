<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\WorkInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $categories = Category::query()->when(! $user?->canReadIt(), fn ($q) => $q->where('type', 'general'))->orderBy('name')->get();
        $instructions = WorkInstruction::with(['category', 'author'])->published()->visibleTo($user)
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->input('category'))))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($search) => $search->where('title', 'like', '%'.$request->q.'%')->orWhere('excerpt', 'like', '%'.$request->q.'%')->orWhere('content_html', 'like', '%'.$request->q.'%')))
            ->latest('published_at')->paginate(10)->withQueryString();
        return view('library.index', compact('categories', 'instructions'));
    }

    public function show(Request $request, WorkInstruction $workInstruction)
    {
        $this->authorizeRead($request, $workInstruction);
        return view('library.show', compact('workInstruction'));
    }

    public function attachment(Request $request, WorkInstruction $workInstruction)
    {
        $this->authorizeRead($request, $workInstruction);
        abort_unless($request->user()?->canReadIt(), 403);
        abort_unless($workInstruction->attachment_path && Storage::disk('local')->exists($workInstruction->attachment_path), 404);
        return response()->file(Storage::disk('local')->path($workInstruction->attachment_path), ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="'.$workInstruction->attachment_name.'"']);
    }

    private function authorizeRead(Request $request, WorkInstruction $workInstruction): void
    {
        if ($workInstruction->status !== 'published') {
            abort_unless($request->user() && ($request->user()->isAdmin() || $workInstruction->author_id === $request->user()->id), 403);
            return;
        }
        abort_unless($workInstruction->category->type === 'general' || $request->user()?->canReadIt(), 403);
    }
}
