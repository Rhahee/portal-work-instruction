<x-layouts.app title="Kelola WI">
<section class="section management-section">
    <div class="section-heading">
        <div><h1>Work Instructions</h1></div>
        <a class="button" href="{{ route('manage.instructions.create') }}">Buat Work Instruction</a>
    </div>

    <div class="data-table management-table">
        <table>
            <colgroup>
                <col class="wi-title-column"><col class="wi-category-column"><col class="wi-status-column"><col class="wi-date-column"><col class="wi-action-column">
            </colgroup>
            <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Diperbarui</th><th>Aksi / Review</th></tr></thead>
            <tbody>
                @forelse($instructions as $instruction)
                    <tr>
                        <td><strong>{{ $instruction->title }}</strong><br><small>{{ $instruction->author->name }}</small></td>
                        <td>{{ $instruction->category->name }}</td>
                        <td>
                            <span class="tag">{{ str_replace('_', ' ', $instruction->status) }}</span>
                            @if($instruction->deletion_status === 'pending')
                                <br><small class="danger">Menunggu hapus</small>
                            @elseif($instruction->deletion_status === 'rejected')
                                <br><small>Hapus ditolak</small>
                            @endif
                        </td>
                        <td>{{ $instruction->updated_at->format('d M Y') }}</td>
                        <td>
                            @if($instruction->deletion_status === 'pending' && auth()->user()->isAdmin())
                                <small>Diajukan oleh {{ $instruction->deletionRequester?->name }}</small>
                                <form class="review-form compact" method="post" action="{{ route('manage.instructions.deletion-review', $instruction) }}">
                                    @csrf
                                    <input name="deletion_review_notes" placeholder="Catatan wajib jika menolak" maxlength="2000">
                                    <button class="button" name="decision" value="approved" onclick="return confirm('Setujui penghapusan WI ini?');">Setujui hapus</button>
                                    <button class="button button-reject" name="decision" value="rejected">Tolak</button>
                                </form>
                            @elseif($instruction->deletion_status === 'pending')
                                <span class="metadata">Menunggu keputusan Admin</span>
                            @else
                                <a href="{{ route('manage.instructions.edit', $instruction) }}">Edit</a> ·
                                <form class="inline" method="post" action="{{ route('manage.instructions.destroy', $instruction) }}" onsubmit="return confirm('{{ auth()->user()->isAdmin() ? 'Hapus WI ini? Artikel dan lampiran PDF-nya tidak dapat dipulihkan.' : 'Ajukan penghapusan WI ini ke Admin?' }}');">
                                    @csrf @method('DELETE')
                                    <button class="link-button danger">{{ auth()->user()->isAdmin() ? 'Hapus' : 'Ajukan hapus' }}</button>
                                </form>
                                @if($instruction->deletion_status === 'rejected' && $instruction->deletion_review_notes)
                                    <br><small>Catatan Admin: {{ $instruction->deletion_review_notes }}</small>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada WI.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $instructions->links() }}</div>
</section>
</x-layouts.app>
