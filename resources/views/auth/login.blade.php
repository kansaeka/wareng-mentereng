@extends('layouts.app')

@section('title', 'Login Admin | WebGIS Dusun Wareng')

@section('content')
    <section class="admin-auth-section">
        <div class="container">
            <div class="admin-auth-card">
                <p class="section-label">
                    ADMINISTRATOR
                </p>

                <h1>Masuk ke WebGIS</h1>

                <p class="admin-auth-description">
                    Gunakan akun administrator untuk mengelola
                    kategori dan data fasilitas Dusun Wareng.
                </p>

                @if ($errors->any())
                    <div
                        class="admin-alert admin-alert-error"
                        role="alert"
                    >
                        <strong>Login gagal.</strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ route('login.store') }}"
                    method="POST"
                    class="admin-auth-form"
                >
                    @csrf

                    <div class="admin-form-group">
                        <label for="email">
                            Email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="admin-form-group">
                        <label for="password">
                            Password
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <label class="admin-remember-option">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >

                        <span>Ingat sesi login</span>
                    </label>

                    <button
                        type="submit"
                        class="admin-primary-button"
                    >
                        Masuk ke Dashboard
                    </button>
                </form>

                <a
                    href="{{ route('home') }}"
                    class="admin-back-link"
                >
                    Kembali ke halaman publik
                </a>
            </div>
        </div>
    </section>
@endsection
