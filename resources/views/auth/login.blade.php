@extends('layouts.app')

@section('title', 'Login - Apparify')

@section('content')
<style>
    /* 1. Pengaturan Dasar Halaman */
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        background-color: #FCF8EE; /* Background Cream khas Apparify */
        display: flex;
        flex-direction: column;
    }

    /* 2. Wrapper Utama untuk Menengahkan Konten */
    .main-wrapper {
        flex: 1 0 auto;
        display: flex;
        align-items: center; 
        justify-content: center;
        padding: 40px 0;
    }

    /* 3. Styling Kartu Login */
    .login-card {
        border: 1px solid #BDCDD6;
        border-radius: 20px;
        background-color: #ffffff;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .login-header {
        color: #6096B4;
        font-weight: 700;
        margin-bottom: 5px;
    }

    /* 4. Styling Form */
    .form-label {
        color: #2C3333;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .form-control {
        border: 1px solid #BDCDD6;
        border-radius: 10px;
        padding: 12px;
        background-color: #FCF8EE;
    }

    .form-control:focus {
        border-color: #6096B4;
        box-shadow: 0 0 0 0.25rem rgba(96, 150, 180, 0.25);
        background-color: #ffffff;
    }

    .input-group-text {
        background-color: #EEE9DA;
        border: 1px solid #BDCDD6;
        color: #6096B4;
    }

    /* 5. Tombol Login (Pill Style) */
    .btn-login {
        background-color: #6096B4;
        border: none;
        border-radius: 50px;
        font-weight: 700;
        padding: 14px;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-login:hover {
        background-color: #93BFCF;
        transform: translateY(-2px);
        color: white;
    }

    /* 6. Footer Styling */
    footer {
        flex-shrink: 0;
        background-color: #2C3333 !important;
        border-top: 5px solid #6096B4;
        color: #bdc3c7;
        padding: 3rem 0;
    }

    footer h5 {
        color: white;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .footer-link {
        color: #bdc3c7;
        text-decoration: none;
        transition: 0.3s;
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .footer-link:hover {
        color: #6096B4;
        padding-left: 5px;
    }

    .social-icon {
        width: 35px;
        height: 35px;
        background: rgba(255,255,255,0.1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        margin-right: 10px;
        transition: 0.3s;
    }

    .social-icon:hover {
        background: #6096B4;
        transform: translateY(-3px);
    }
</style>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="login-header">Login</h2>
                            <p class="text-muted small">Masuk ke akun Anda</p>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success border-0 small py-2" style="background-color: #BDCDD6; color: #2C3333;">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger border-0 small py-2" style="background-color: #EEE9DA; color: #c0392b;">
                                <ul class="mb-0 px-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf {{-- Penting untuk mencegah Page Expired --}}
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope fa-sm"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock fa-sm"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label text-muted small" for="remember">Remember Me</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login w-100 shadow-sm">
                                LOGIN SEKARANG <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small">Belum punya akun? <a href="{{ route('register') }}" style="color: #6096B4; font-weight: 700; text-decoration: none;">Daftar Sekarang</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row g-4 text-start">
            <div class="col-lg-4">
                <h4 class="text-white fw-bold mb-3">Apparify</h4>
                <p class="small">Platform penyedia apparel custom berkualitas tinggi dengan desain yang bisa Anda tentukan sendiri.</p>
                <div class="mt-4">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-1">
                <h5>Quick Links</h5>
                <a href="#" class="footer-link">Dashboard</a>
                <a href="#" class="footer-link">Cari Produk</a>
                <a href="#" class="footer-link">Custom Desain</a>
            </div>
            <div class="col-lg-2">
                <h5>Support</h5>
                <a href="#" class="footer-link">Hubungi Kami</a>
                <a href="#" class="footer-link">Status Pesanan</a>
                <a href="#" class="footer-link">FAQ</a>
            </div>
            <div class="col-lg-3">
                <h5>Contact Us</h5>
                <p class="small mb-2"><i class="fas fa-map-marker-alt me-2" style="color: #6096B4"></i> Depok, Jawa Barat, Indonesia</p>
                <p class="small mb-2"><i class="fas fa-envelope me-2" style="color: #6096B4"></i> support@apparify.com</p>
                <p class="small"><i class="fas fa-phone me-2" style="color: #6096B4"></i> +62 812 3456 7890</p>
            </div>
        </div>
        <hr class="my-4 opacity-25">
        <div class="text-center small">
            <p class="mb-0">&copy; {{ date('Y') }} <strong>Apparify</strong>. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection