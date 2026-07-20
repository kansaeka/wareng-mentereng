<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'WebGIS Dusun Wareng')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')

</head>

<body id="top" class="{{ request()->routeIs('home') ? 'home-page' : 'inner-page' }}">
    <header class="site-header">
        <div class="container navbar">
            <a href="{{ route('home') }}" class="brand" aria-label="Beranda WarengMentereng">
                <span class="brand-logo">
                    <img src="{{ asset('images/brand/logo-wareng.png') }}" alt="Logo WarengMentereng">
                </span>

                <span class="brand-text">
                    <strong>WarengMentereng</strong>

                    <small>
                        WebGIS Dusun Wareng
                    </small>
                </span>
            </a>

            <div class="navbar-actions">
                <nav class="site-navigation" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}"
                        class="site-navigation-link
                    {{ request()->routeIs('home') ? 'is-active' : '' }}">
                        Beranda
                    </a>

                    <a href="{{ route('stories.wareng') }}"
                        class="site-navigation-link
                    {{ request()->routeIs('stories.wareng') ? 'is-active' : '' }}">
                        Jelajah Wareng
                    </a>

                    <a href="{{ route('map.index') }}"
                        class="site-navigation-link
                    {{ request()->routeIs('map.index') ? 'is-active' : '' }}">
                        Peta Interaktif
                    </a>
                </nav>

                <details class="account-navigation">
                    <summary class="site-navigation-icon-button" aria-label="Buka menu akun" title="Menu akun">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="8" r="4"></circle>

                            <path d="M4.5 21c.4-4.2
                            3-6.5 7.5-6.5s7.1
                            2.3 7.5 6.5"></path>
                        </svg>
                    </summary>

                    <div class="account-navigation-menu">
                        @guest
                            <a href="{{ route('login') }}">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M10 17v2H5V5h5v2"></path>

                                    <path d="m15 8 4 4-4 4"></path>

                                    <path d="M19 12H9"></path>
                                </svg>

                                <span>Masuk</span>
                            </a>
                        @endguest

                        @auth
                            @if (auth()->user()->is_admin)
                                <div class="account-navigation-user">
                                    <span class="account-navigation-avatar" aria-hidden="true">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>

                                    <div>
                                        <strong>
                                            {{ auth()->user()->name }}
                                        </strong>

                                        <small>
                                            Administrator
                                        </small>
                                    </div>
                                </div>

                                <div class="account-navigation-divider"></div>

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

                                <div class="account-navigation-divider"></div>

                                <form action="{{ route('logout') }}" method="POST" class="account-navigation-logout">
                                    @csrf

                                    <button type="submit">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M10 17v2H5V5h5v2"></path>

                                            <path d="m15 8 4 4-4 4"></path>

                                            <path d="M19 12H9"></path>
                                        </svg>

                                        <span>Keluar</span>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </details>

                <details class="mobile-navigation">
                    <summary class="mobile-navigation-toggle" aria-label="Buka navigasi" title="Menu navigasi">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 7h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 17h16"></path>
                        </svg>
                    </summary>

                    <nav class="mobile-navigation-menu" aria-label="Navigasi ponsel">
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">
                            <span>Beranda</span>

                            <small>Halaman utama</small>
                        </a>

                        <a href="{{ route('stories.wareng') }}"
                            class="{{ request()->routeIs('stories.wareng') ? 'is-active' : '' }}">
                            <span>Jelajah Wareng</span>

                            <small>Narasi berbasis peta</small>
                        </a>

                        <a href="{{ route('map.index') }}"
                            class="{{ request()->routeIs('map.index') ? 'is-active' : '' }}">
                            <span>Peta Interaktif</span>

                            <small>Eksplorasi informasi spasial</small>
                        </a>
                    </nav>
                </details>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer-pattern" aria-hidden="true"></div>

        <div class="container site-footer-container">
            <div class="site-footer-main">
                <div class="site-footer-identity">
                    <a href="{{ route('home') }}" class="site-footer-brand" aria-label="Beranda WebGIS Dusun Wareng">
                        <span class="site-footer-brand-mark">
                            <img src="{{ asset('images/brand/logo-wareng.png') }}" alt="Logo WarengMentereng">
                        </span>

                        <span class="site-footer-brand-text">
                            <strong>WarengMentereng</strong>

                            <small>
                                WebGIS Dusun Wareng
                            </small>
                        </span>
                    </a>

                    <p>
                        Portal informasi geografis untuk mengenal
                        wilayah, fasilitas, potensi, dan kehidupan
                        masyarakat Dusun Wareng melalui peta digital.
                    </p>

                    <div class="site-footer-development">
                        <span class="site-footer-development-icon" aria-hidden="true">
                            ✦
                        </span>

                        <div>
                            <small>DIKEMBANGKAN OLEH</small>

                            <strong>
                                Tim PKL Dusun Wareng
                            </strong>

                            <span>
                                Universitas Gadjah Mada
                            </span>
                        </div>
                    </div>

                    <div class="site-footer-socials">
                        <span class="site-footer-social-label">
                            Terhubung dengan WarengMentereng
                        </span>

                        <div class="site-footer-social-list">
                            <a href="https://www.instagram.com/warengmentereng/" class="site-footer-social-link"
                                target="_blank" rel="noopener noreferrer" aria-label="Instagram WarengMentereng"
                                title="Instagram">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <circle cx="17.5" cy="6.5" r="1.1" class="social-fill"></circle>
                                </svg>
                            </a>

                            <a href="https://wa.me/6281224341287" class="site-footer-social-link" target="_blank"
                                rel="noopener noreferrer" aria-label="WhatsApp WarengMentereng" title="WhatsApp">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20 12a8 8 0 0 1-11.7 7.1L4 20l1-4.1A8 8 0 1 1 20 12Z"></path>
                                    <path
                                        d="M9.4 8.8c.2-.4.4-.4.6-.4h.5c.2 0 .4 0 .5.3l.7 1.7c.1.2 0 .4-.1.6l-.4.5c-.1.1-.2.2-.1.4.2.4.7 1.2 1.6 1.9.9.8 1.7 1.1 2.1 1.3.2.1.3 0 .4-.1l.6-.7c.1-.2.3-.2.5-.1l1.6.7c.2.1.3.2.3.5v.5c0 .2-.1.4-.4.6-.4.2-1 .4-1.7.3-.8-.1-2-.5-3.5-1.8-1.7-1.4-2.6-3-2.9-3.8-.2-.7 0-1.3.2-1.6Z">
                                    </path>
                                </svg>
                            </a>

                            <a href="mailto:kansakelana@gmail.com" class="site-footer-social-link"
                                aria-label="Email WarengMentereng" title="Email">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M4 7l8 6 8-6"></path>
                                </svg>
                            </a>

                            <a href="https://maps.google.com/?q=Dusun+Wareng+Sumberarum+Magelang"
                                class="site-footer-social-link" target="_blank" rel="noopener noreferrer"
                                aria-label="Lokasi Dusun Wareng" title="Lokasi">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 21s7-5.4 7-12a7 7 0 1 0-14 0c0 6.6 7 12 7 12Z"></path>
                                    <circle cx="12" cy="9" r="2.5"></circle>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="site-footer-navigation">
                    <div class="site-footer-column">
                        <h2>Navigasi</h2>

                        <nav aria-label="Navigasi footer utama">
                            <a href="{{ route('home') }}">
                                Beranda
                            </a>

                            <a href="{{ route('stories.wareng') }}">
                                Jelajah Wareng
                            </a>

                            <a href="{{ route('map.index') }}">
                                Peta Interaktif
                            </a>
                        </nav>
                    </div>

                    <div class="site-footer-column">
                        <h2>Informasi Wilayah</h2>

                        <nav aria-label="Navigasi informasi wilayah">
                            <a href="{{ route('home') }}#profil-dusun">
                                Profil Dusun
                            </a>

                            <a href="{{ route('home') }}#informasi-wilayah">
                                Wareng dalam Angka
                            </a>

                            <a href="{{ route('home') }}#potensi-dusun">
                                Potensi Dusun
                            </a>

                            <a href="{{ route('home') }}#peta-ringkas">
                                Peta Ringkas
                            </a>
                        </nav>
                    </div>

                    <div class="site-footer-column">
                        <h2>Status Informasi</h2>

                        <div class="site-footer-status">
                            <span class="site-footer-status-dot" aria-hidden="true"></span>

                            <span>
                                WebGIS dalam pengembangan
                            </span>
                        </div>

                        <p>
                            Informasi spasial tertentu masih menggunakan
                            data sementara dan akan diperbarui setelah
                            proses verifikasi lapangan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="site-footer-divider"></div>

            <div class="site-footer-bottom">
                <p>
                    &copy; {{ now()->year }}
                    <strong>WarengMentereng.</strong>
                    Hak cipta dilindungi.
                </p>

                <p>
                    Disusun oleh Tim PKL Dusun Wareng,
                    Universitas Gadjah Mada.
                </p>

                <a href="#top" class="site-footer-back-to-top" aria-label="Kembali ke bagian atas halaman">
                    Kembali ke atas

                    <span aria-hidden="true">
                        ↑
                    </span>
                </a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
