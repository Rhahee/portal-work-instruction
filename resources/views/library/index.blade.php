<x-layouts.app title="WI Library">
<section class="page-head">
    <h1>Temukan prosedur yang Anda butuhkan.</h1>
    <p>Jelajahi panduan kerja yang telah diterbitkan.</p>
</section>
<section class="section">
    <form class="filter-bar" method="get" action="{{ route('library.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari judul atau isi WI…">
        <select name="category" onchange="this.form.submit()"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>@endforeach</select>
        <button class="button">Terapkan</button>
    </form>
    <div class="wi-list">
        @forelse($instructions as $instruction)
            <a class="wi-row" href="{{ route('library.show', $instruction) }}"><span class="tag {{ $instruction->category->type }}">{{ $instruction->category->name }}</span><div><h2>{{ $instruction->title }}</h2><p>{{ $instruction->excerpt }}</p><small>Oleh {{ $instruction->author->name }} · Diperbarui {{ $instruction->updated_at->format('d M Y') }}</small></div><span>→</span></a>
        @empty
            <p class="empty">Tidak ada WI yang cocok. Coba ubah kata kunci atau filter.</p>
        @endforelse
    </div>
    <div class="pagination">{{ $instructions->links() }}</div>
</section>
</x-layouts.app>
