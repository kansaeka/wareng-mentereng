<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'WebGIS Dusun Wareng')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')

</head>

<body>
    <header class="site-header">
        <div class="container navbar">
            <a href="{{ url('/') }}" class="brand">
                <span class="brand-logo">W</span>

                <span class="brand-text">
                    <strong>Dusun Wareng</strong>
                    <small>Portal Informasi dan WebGIS</small>
                </span>
            </a>

            <nav class="site-navigation" aria-label="Navigasi utama">
                <a href="{{ route('home') }}"
                    class="site-navigation-link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                    Beranda
                </a>

                <a href="{{ route('stories.wareng') }}"
                    class="site-navigation-link {{ request()->routeIs('stories.wareng') ? 'is-active' : '' }}">
                    Jelajah Wareng
                </a>

                <a href="{{ route('map.index') }}"
                    class="site-navigation-link {{ request()->routeIs('map.index') ? 'is-active' : '' }}">
                    Peta Interaktif
                </a>

                <details class="account-navigation">
                    <summary class="site-navigation-icon-button" aria-label="Buka menu akun" title="Menu akun">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12a5 5 0 1 0 0-10
                       5 5 0 0 0 0 10Zm0 2
                       c-5.33 0-8 2.67-8 6
                       v1h16v-1c0-3.33-2.67-6-8-6Z" />
                        </svg>
                    </summary>

                    <div class="account-navigation-menu">
                        @guest
                            <a href="{{ route('login') }}">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M10 17v2H5V5h5v2h2V3H3
                                               v18h9v-4h-2Zm7-5-4-4
                                               1.4-1.4L20.8 13l-6.4 6.4
                                               L13 18l4-4H8v-2h9Z" />
                                </svg>

                                <span>Masuk</span>
                            </a>
                        @endguest

                        @auth
                            @if (auth()->user()->is_admin)
                                <div class="account-navigation-user">
                                    <strong>
                                        {{ auth()->user()->name }}
                                    </strong>

                                    <small>Administrator</small>
                                </div>

                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>

                                <a href="{{ route('admin.facility-categories.index') }}">
                                    Kelola Kategori
                                </a>

                                <a href="{{ route('admin.facilities.index') }}">
                                    Kelola Fasilitas
                                </a>

                                <a href="{{ route('admin.stories.index') }}">
                                    Kelola Jelajah Wareng
                                </a>

                                <form action="{{ route('logout') }}" method="POST" class="account-navigation-logout">
                                    @csrf

                                    <button type="submit">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M10 17v2H5V5h5v2h2V3H3
                                                       v18h9v-4h-2Zm7-5-4-4
                                                       1.4-1.4L20.8 13l-6.4 6.4
                                                       L13 18l4-4H8v-2h9Z" />
                                        </svg>

                                        <span>Keluar</span>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </details>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-content">
            <div>
                <strong>Portal Informasi dan WebGIS Dusun Wareng</strong>

                <p>
                    Media informasi wilayah, masyarakat, potensi,
                    dan kehidupan Dusun Wareng.
                </p>
            </div>

            <div>
                <p>&copy; {{ date('Y') }} Dusun Wareng</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
