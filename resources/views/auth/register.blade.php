@extends('layouts.app')

@section('title', 'Register - Apparify')

@section('content')
<style>
    /* 1. Dasar Halaman & Layout Centered */
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        background-color: #FCF8EE; /* Background Cream khas Apparify */
        display: flex;
        flex-direction: column;
    }

    /* 2. Wrapper untuk menengahkan frame secara vertikal & horizontal */
    .main-wrapper {
        flex: 1 0 auto;
        display: flex;
        align-items: center; 
        justify-content: center;
        padding: 50px 0;
    }

    /* 3. Merapikan Ukuran Frame Register */
    .register-card {
        border: 1px solid #BDCDD6;
        border-radius: 20px;
        background-color: #ffffff;
        width: 100%;
        max-width: 550px; /* Ukuran frame proporsional untuk input berdampingan */
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .register-header {
        color: #6096B4; /* Primary Blue */
        font-weight: 700;
        margin-bottom: 5px;
    }

    /* 4. Styling Form Input */
    .form-label {
        color: #2C3333;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 5px;
    }

    .form-control, .form-select {
        border: 1px solid #BDCDD6;
        border-radius: 10px;
        padding: 10px 15px;
        background-color: #FCF8EE;
        font-size: 0.9rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6096B4;
        box-shadow: 0 0 0 0.25rem rgba(96, 150, 180, 0.2);
        background-color: #ffffff;
    }

    /* 5. Tombol Daftar (Pill Style) */
    .btn-register {
        background-color: #6096B4;
        border: none;
        border-radius: 50px; /* Gaya Pill konsisten */
        font-weight: 700;
        padding: 12px;
        transition: all 0.3s ease;
        color: white;
        letter-spacing: 0.5px;
    }

    .btn-register:hover {
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
</style>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div class="card register-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="register-header">Create Account</h2>
                            <p class="text-muted small">Bergabunglah dengan Apparify dan mulai kustomisasi pakaianmu!</p>
                        </div>
                        
                        @if($errors->any())
                            <div class="alert alert-danger border-0 small py-2 mb-4" style="background-color: #EEE9DA; color: #c0392b;">
                                <ul class="mb-0 px-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf {{-- Menghindari Error Page Expired --}}
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="0812xxxx" value="{{ old('phone') }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Home Address</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap pengiriman">{{ old('address') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="********" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="********" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Register As</label>
                                    <select name="role" class="form-select" required>
                                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer (Ingin Berbelanja)</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Pengelola Toko)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-register w-100 shadow-sm">
                                    DAFTAR SEKARANG
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small">Sudah punya akun? <a href="{{ route('login') }}" style="color: #6096B4; font-weight: 700; text-decoration: none;">Login di sini</a></p>
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