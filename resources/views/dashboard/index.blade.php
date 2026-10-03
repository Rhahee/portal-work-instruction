<x-layouts.app title="Dashboard">
<div class="dashboard dashboard-single">
    <section>
        <div class="dashboard-hero">
            <div>
                <p class="eyebrow">{{ auth()->user()->isAdmin() ? 'ADMINISTRASI PORTAL' : 'RUANG KERJA IT' }}</p>
                <h1>{{ auth()->user()->isAdmin() ? 'Overview portal' : 'Selamat datang, '.auth()->user()->name }}</h1>
                <p>{{ auth()->user()->isAdmin() ? 'Pantau dokumen dan pengajuan yang membutuhkan keputusan Anda.' : 'Kelola draft, pantau review, dan terbitkan pengetahuan teknis tim.' }}</p>
            </div>
        </div>

        @if(!auth()->user()->isAdmin())
            <div class="dashboard-stat-grid">
                <div class="dashboard-stat"><small>Total WI</small><strong>{{ $totalInstructions }}</strong></div>
                <div class="dashboard-stat"><small>Draft</small><strong>{{ $draftInstructions }}</strong></div>
                <div class="dashboard-stat"><small>Menunggu review</small><strong>{{ $pendingReviewInstructions }}</strong></div>
                <div class="dashboard-stat {{ $rejectedInstructions ? 'needs-attention' : '' }}"><small>Perlu revisi</small><strong>{{ $rejectedInstructions }}</strong></div>
            </div>

            @if($rejectedInstructions)
                <div class="dashboard-alert"><strong>Ada {{ $rejectedInstructions }} WI yang perlu direvisi.</strong><a href="{{ route('manage.instructions.index') }}">Lihat artikel saya →</a></div>
            @endif

            <div class="dashboard-grid">
                <div class="dashboard-panel">
                    <div class="panel-heading"><div><p class="eyebrow">ARTIKEL SAYA</p><h2>Aktivitas terbaru</h2></div><div class="panel-actions"><a class="button button-small" href="{{ route('manage.instructions.create') }}">Buat Work Instruction</a><a href="{{ route('manage.instructions.index') }}">Kelola semua →</a></div></div>
                    <div class="dashboard-wi-list">
                        @forelse($recentInstructions as $instruction)
                            <a class="dashboard-wi-row" href="{{ route('manage.instructions.edit', $instruction) }}">
                                <div><h3>{{ $instruction->title }}</h3><small>{{ $instruction->category->name }} · Diperbarui {{ $instruction->updated_at->format('d M Y') }}</small></div>
                                <span class="tag">{{ str_replace('_', ' ', $instruction->status) }}</span>
                            </a>
                        @empty
                            <div class="dashboard-empty"><p>Belum ada WI yang Anda buat.</p></div>
                        @endforelse
                    </div>
                </div>

                <aside class="dashboard-panel workflow-panel">
                    <p class="eyebrow">ALUR PUBLIKASI</p>
                    <h2>Dari draft hingga terbit</h2>
                    <ol class="workflow-list"><li><strong>Buat atau perbarui draft</strong><span>Simpan pekerjaan Anda kapan saja.</span></li><li><strong>Ajukan untuk review</strong><span>Admin memeriksa isi dan kelayakan publikasi.</span></li><li><strong>Terbit atau revisi</strong><span>Perbaiki catatan Admin bila diperlukan.</span></li></ol>
                    <a href="{{ route('library.index') }}">Lihat Library →</a>
                </aside>
            </div>
        @else
            <div class="dashboard-stat-grid admin-stats">
                <div class="dashboard-stat"><small>Total WI</small><strong>{{ $totalInstructions }}</strong></div>
                <div class="dashboard-stat"><small>Terbit</small><strong>{{ $publishedInstructions }}</strong></div>
                <div class="dashboard-stat"><small>Menunggu review</small><strong>{{ $pendingReviewInstructions }}</strong></div>
                <div class="dashboard-stat"><small>Pengguna</small><strong>{{ $totalUsers }}</strong></div>
            </div>
            <div class="dashboard-panel">
                <div class="panel-heading"><div><p class="eyebrow">DOKUMEN</p><h2>Perubahan terbaru</h2></div><div class="panel-actions"><a class="button button-small" href="{{ route('manage.instructions.create') }}">Buat Work Instruction</a><a href="{{ route('manage.instructions.index') }}">Kelola WI →</a></div></div>
                <div class="dashboard-wi-list">
                    @forelse($recentInstructions as $instruction)
                        <a class="dashboard-wi-row" href="{{ route('manage.instructions.edit', $instruction) }}"><div><h3>{{ $instruction->title }}</h3><small>{{ $instruction->category->name }} · {{ $instruction->author->name }}</small></div><span class="tag">{{ str_replace('_', ' ', $instruction->status) }}</span></a>
                    @empty
                        <p class="dashboard-empty">Belum ada WI.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </section>
</div>
</x-layouts.app>
