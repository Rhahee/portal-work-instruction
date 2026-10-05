<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Work Instruction Portal' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
</head>
<body>
<div class="app-shell">
    <aside class="site-sidebar" id="site-sidebar" aria-label="Menu portal">
        <button class="sidebar-close" type="button" aria-label="Tutup menu">×</button>
        <a class="brand" href="{{ route('home') }}">
            <img class="brand-mark" src="{{ asset('images/brand/work-instruction-mark.svg') }}" alt="">
            <span>Work Instruction<br><small>Portal</small></span>
        </a>

        <nav class="side-nav" aria-label="Navigasi utama">
            <p class="side-nav-label">NAVIGASI</p>
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="{{ request()->routeIs('library.*') ? 'active' : '' }}" href="{{ route('library.index') }}">Library</a>
            @auth
                @if(auth()->user()->canSubmitInstructions())
                    <p class="side-nav-label">RUANG KERJA</p>
                    <a class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">Dashboard</a>
                    <a class="{{ request()->routeIs('manage.instructions.*') ? 'active' : '' }}" href="{{ route('manage.instructions.index') }}">Kelola WI @if($workspaceAttentionCount)<span class="nav-badge" aria-label="{{ $workspaceAttentionCount }} item perlu perhatian">{{ $workspaceAttentionCount }}</span>@endif</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <p class="side-nav-label">ADMINISTRASI</p>
                    <a class="{{ request()->routeIs('manage.categories.*') ? 'active' : '' }}" href="{{ route('manage.categories.index') }}">Kategori</a>
                    <a class="{{ request()->routeIs('manage.users.*') ? 'active' : '' }}" href="{{ route('manage.users.index') }}">Pengguna</a>
                @endif
            @endauth
        </nav>

        <div class="side-account">
            @auth
                <small>{{ auth()->user()->name }} · {{ strtoupper(auth()->user()->role) }}</small>
                <form method="post" action="{{ route('logout') }}">@csrf<button class="link-button" type="submit">Keluar</button></form>
            @else
                <a class="button button-small" href="{{ route('login') }}">Masuk</a>
            @endauth
        </div>
    </aside>

    <div class="app-content">
        <div class="mobile-topbar">
            <button class="mobile-menu-toggle" type="button" aria-controls="site-sidebar" aria-expanded="false"><span aria-hidden="true">☰</span> Menu</button>
            <a href="{{ route('home') }}">WI Portal</a>
        </div>
        @if(session('status'))<div class="notice success">{{ session('status') }}</div>@endif
        <main>{{ $slot }}</main>
        <footer>Work Instruction Portal · Internal knowledge, clearly documented.</footer>
    </div>
</div>
<button class="sidebar-backdrop" type="button" aria-label="Tutup menu"></button>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.site-sidebar');
    const trigger = document.querySelector('.mobile-menu-toggle');
    const closeButton = document.querySelector('.sidebar-close');
    const backdrop = document.querySelector('.sidebar-backdrop');
    const setOpen = (open) => {
        sidebar.classList.toggle('is-open', open);
        backdrop.classList.toggle('is-visible', open);
        trigger.setAttribute('aria-expanded', String(open));
        document.body.classList.toggle('menu-open', open);
    };
    trigger.addEventListener('click', () => setOpen(!sidebar.classList.contains('is-open')));
    closeButton.addEventListener('click', () => setOpen(false));
    backdrop.addEventListener('click', () => setOpen(false));
    sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') setOpen(false); });
});
</script>
</body>
</html>
