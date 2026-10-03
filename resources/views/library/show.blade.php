<x-layouts.app title="{{ $workInstruction->title }}">
<article class="paper"><div class="article-head"><span class="tag {{ $workInstruction->category->type }}">{{ $workInstruction->category->name }}</span><h1>{{ $workInstruction->title }}</h1>@if($workInstruction->excerpt)<p class="lead">{{ $workInstruction->excerpt }}</p>@endif<p class="metadata">Diterbitkan {{ $workInstruction->published_at?->format('d M Y') }} · Diperbarui {{ $workInstruction->updated_at->format('d M Y') }} · {{ $workInstruction->author->name }}</p></div><hr><div class="article-content">{!! $workInstruction->content_html !!}</div>
@if(auth()->user()?->canReadIt() && $workInstruction->attachment_path)<aside class="pdf-attachment"><span>PDF</span><div><strong>Sumber Informasi</strong><p>{{ $workInstruction->attachment_name }}</p></div><a class="button button-small" href="{{ route('library.attachment', $workInstruction) }}" target="_blank" rel="noopener">Buka PDF</a></aside>@endif
</article>
</x-layouts.app>
