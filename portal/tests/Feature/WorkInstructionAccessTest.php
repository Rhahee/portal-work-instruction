<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\WorkInstruction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkInstructionAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_library_hides_it_instructions(): void
    {
        $general = Category::create(['name' => 'General', 'slug' => 'general', 'type' => 'general']);
        $it = Category::create(['name' => 'IT', 'slug' => 'it', 'type' => 'it']);
        $author = User::factory()->create(['role' => 'admin']);
        $visible = WorkInstruction::create(['title' => 'Visible WI', 'slug' => 'visible-wi', 'status' => 'published', 'published_at' => now(), 'category_id' => $general->id, 'author_id' => $author->id]);
        $hidden = WorkInstruction::create(['title' => 'Secret IT WI', 'slug' => 'secret-it-wi', 'status' => 'published', 'published_at' => now(), 'category_id' => $it->id, 'author_id' => $author->id]);
        $this->get(route('library.index'))->assertOk()->assertSee($visible->title)->assertDontSee($hidden->title);
        $this->get(route('library.show', $hidden))->assertForbidden();
    }

    public function test_it_user_can_read_it_instruction(): void
    {
        $it = Category::create(['name' => 'IT', 'slug' => 'it', 'type' => 'it']);
        $author = User::factory()->create(['role' => 'admin']);
        $instruction = WorkInstruction::create(['title' => 'VPN Setup', 'slug' => 'vpn-setup', 'status' => 'published', 'published_at' => now(), 'category_id' => $it->id, 'author_id' => $author->id]);
        $this->actingAs(User::factory()->create(['role' => 'it']))->get(route('library.show', $instruction))->assertOk()->assertSee('VPN Setup');
    }

    public function test_only_staff_can_open_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'legacy-reader']))->get(route('dashboard.index'))->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'it']))->get(route('dashboard.index'))->assertOk();
    }

    public function test_it_submits_and_admin_approves_an_instruction(): void
    {
        $category = Category::create(['name' => 'IT', 'slug' => 'it', 'type' => 'it']);
        $author = User::factory()->create(['role' => 'it']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($author)->post(route('manage.instructions.store'), [
            'title' => 'Artikel untuk direview', 'excerpt' => 'Ringkasan', 'category_id' => $category->id,
            'status' => 'pending_review', 'content_json' => json_encode(['type' => 'doc', 'content' => []]), 'content_html' => '<p>Konten</p>',
        ])->assertRedirect(route('manage.instructions.index'));

        $instruction = WorkInstruction::firstOrFail();
        $this->assertSame('pending_review', $instruction->status);
        $this->actingAs($admin)->post(route('manage.instructions.review', $instruction), ['decision' => 'published'])->assertRedirect(route('manage.instructions.index'));
        $this->assertDatabaseHas('work_instructions', ['id' => $instruction->id, 'status' => 'published', 'reviewed_by' => $admin->id]);
    }

    public function test_it_cannot_edit_another_authors_instruction(): void
    {
        $category = Category::create(['name' => 'IT', 'slug' => 'it', 'type' => 'it']);
        $instruction = WorkInstruction::create(['title' => 'Milik orang lain', 'slug' => 'milik-orang-lain', 'status' => 'draft', 'category_id' => $category->id, 'author_id' => User::factory()->create(['role' => 'it'])->id]);
        $this->actingAs(User::factory()->create(['role' => 'it']))->get(route('manage.instructions.edit', $instruction))->assertForbidden();
    }

    public function test_admin_must_leave_a_note_when_rejecting_an_instruction(): void
    {
        $category = Category::create(['name' => 'IT', 'slug' => 'it', 'type' => 'it']);
        $instruction = WorkInstruction::create(['title' => 'Perlu revisi', 'slug' => 'perlu-revisi', 'status' => 'pending_review', 'category_id' => $category->id, 'author_id' => User::factory()->create(['role' => 'it'])->id]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->from(route('manage.instructions.edit', $instruction))->post(route('manage.instructions.review', $instruction), ['decision' => 'rejected'])->assertSessionHasErrors('review_notes');
        $this->actingAs($admin)->post(route('manage.instructions.review', $instruction), ['decision' => 'rejected', 'review_notes' => 'Tambahkan bukti langkah pengujian.'])->assertRedirect(route('manage.instructions.index'));
        $this->assertDatabaseHas('work_instructions', ['id' => $instruction->id, 'status' => 'rejected', 'review_notes' => 'Tambahkan bukti langkah pengujian.']);
    }

    public function test_pdf_attachment_on_general_wi_is_private_and_available_to_it_users(): void
    {
        Storage::fake('local');
        $category = Category::create(['name' => 'General', 'slug' => 'general', 'type' => 'general']);
        $author = User::factory()->create(['role' => 'it']);
        $instruction = WorkInstruction::create(['title' => 'Panduan PDF', 'slug' => 'panduan-pdf', 'status' => 'published', 'published_at' => now(), 'attachment_path' => 'wi-attachments/panduan.pdf', 'attachment_name' => 'panduan.pdf', 'category_id' => $category->id, 'author_id' => $author->id]);
        Storage::disk('local')->put($instruction->attachment_path, '%PDF-1.4 test');

        $this->get(route('library.attachment', $instruction))->assertForbidden();
        $this->actingAs($author)->get(route('library.attachment', $instruction))->assertOk();
    }

    public function test_it_can_attach_a_pdf_to_a_general_instruction(): void
    {
        Storage::fake('local');
        $category = Category::create(['name' => 'General', 'slug' => 'general', 'type' => 'general']);
        $user = User::factory()->create(['role' => 'it']);
        $this->actingAs($user)->post(route('manage.instructions.store'), ['title' => 'WI dengan PDF', 'category_id' => $category->id, 'status' => 'draft', 'content_json' => json_encode(['type' => 'doc', 'content' => []]), 'content_html' => '<p>Konten</p>', 'attachment_pdf' => UploadedFile::fake()->create('panduan.pdf', 100, 'application/pdf')])->assertRedirect(route('manage.instructions.index'));
        $instruction = WorkInstruction::firstOrFail();
        $this->assertNotNull($instruction->attachment_path);
        Storage::disk('local')->assertExists($instruction->attachment_path);
    }

    public function test_highlight_markup_is_preserved_when_an_instruction_is_saved(): void
    {
        $category = Category::create(['name' => 'IT', 'slug' => 'it', 'type' => 'it']);
        $user = User::factory()->create(['role' => 'it']);
        $this->actingAs($user)->post(route('manage.instructions.store'), ['title' => 'WI dengan highlight', 'category_id' => $category->id, 'status' => 'draft', 'content_json' => json_encode(['type' => 'doc', 'content' => []]), 'content_html' => '<p><mark>Informasi penting</mark></p>'])->assertRedirect(route('manage.instructions.index'));
        $this->assertStringContainsString('<mark>Informasi penting</mark>', WorkInstruction::firstOrFail()->content_html);
    }

    public function test_it_requests_deletion_and_admin_approval_removes_instruction_and_attachment(): void
    {
        Storage::fake('local');
        $category = Category::create(['name' => 'IT', 'slug' => 'it', 'type' => 'it']);
        $author = User::factory()->create(['role' => 'it']);
        $instruction = WorkInstruction::create([
            'title' => 'WI untuk dihapus',
            'slug' => 'wi-untuk-dihapus',
            'status' => 'draft',
            'attachment_path' => 'wi-attachments/obsolete.pdf',
            'attachment_name' => 'obsolete.pdf',
            'category_id' => $category->id,
            'author_id' => $author->id,
        ]);
        Storage::disk('local')->put($instruction->attachment_path, '%PDF-1.4');

        $this->actingAs($author)
            ->delete(route('manage.instructions.destroy', $instruction))
            ->assertRedirect(route('manage.instructions.index'));

        $this->assertDatabaseHas('work_instructions', ['id' => $instruction->id, 'deletion_status' => 'pending', 'deletion_requested_by' => $author->id]);
        Storage::disk('local')->assertExists('wi-attachments/obsolete.pdf');

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->post(route('manage.instructions.deletion-review', $instruction), ['decision' => 'approved'])
            ->assertRedirect(route('manage.instructions.index'));

        $this->assertDatabaseMissing('work_instructions', ['id' => $instruction->id]);
        Storage::disk('local')->assertMissing('wi-attachments/obsolete.pdf');
    }
}
