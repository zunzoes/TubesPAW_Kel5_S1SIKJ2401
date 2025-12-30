<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apparify - Custom Apparel E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6096B4;
            --secondary: #93BFCF;
            --accent: #BDCDD6;
            --neutral: #EEE9DA;
            --bg-cream: #FCF8EE;
            --dark-text: #2C3333;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-cream);
            color: var(--dark-text);
        }
        
        /* Navbar Customization */
        .navbar {
            background-color: white !important;
            border-bottom: 1px solid var(--accent);
        }
        .navbar-brand, .text-primary {
            color: var(--primary) !important;
        }

        /* UKURAN LOGO NAVBAR */
        .navbar-brand img {
            height: 40px;
            width: auto;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 100px 0;
            min-height: 600px;
            display: flex;
            align-items: center;
        }
        
        .feature-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid var(--accent);
            border-radius: 15px;
            height: 100%;
            background-color: white;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(96, 150, 180, 0.2);
            border-color: var(--secondary);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        /* Buttons Customization */
        .btn-custom {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary-custom {
            background: white;
            color: var(--primary);
            border: 2px solid white;
        }
        
        .btn-primary-custom:hover {
            background: var(--neutral);
            color: var(--primary);
            border: 2px solid var(--neutral);
        }
        
        .btn-outline-custom {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-outline-custom:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* How It Works Badge */
        .step-number {
            background-color: var(--primary) !important;
            color: white;
            width: 80px; 
            height: 80px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .product-showcase {
            background: var(--neutral);
            padding: 80px 0;
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, #4A708B 100%);
            color: white;
            padding: 80px 0;
        }

        footer {
            background-color: var(--dark-text) !important;
            border-top: 5px solid var(--primary);
            color: #bdc3c7;
            padding: 4rem 0 2rem;
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
            color: var(--primary);
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
            background: var(--primary);
            transform: translateY(-3px);
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/apparify-high-resolution-logo-transparent.png') }}" alt="Apparify Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-lg-3 px-4" href="{{ route('register') }}">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Custom Apparel Made Easy</h1>
                    <p class="lead mb-4">Wujudkan pakaian impianmu dengan kualitas premium. Dari desain sendiri hingga koleksi eksklusif kami.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('register') }}" class="btn btn-custom btn-primary-custom">
                            <i class="fas fa-rocket"></i> Get Started
                        </a>
                        <a href="#features" class="btn btn-custom btn-outline-custom">
                            <i class="fas fa-circle-info"></i> Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="fas fa-tshirt" style="font-size: 15rem; color: var(--bg-cream); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold" style="color: var(--primary);">Why Choose Apparify?</h2>
                <p class="lead text-muted">Kualitas dan kemudahan adalah prioritas kami</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon"><i class="fas fa-palette"></i></div>
                        <h4>Custom Designs</h4>
                        <p class="text-muted">Unggah desainmu sendiri atau pilih dari koleksi template eksklusif kami.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                        <h4>Quality Guarantee</h4>
                        <p class="text-muted">Menggunakan material kain terbaik dan teknik sablon/bordir profesional.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon"><i class="fas fa-truck-fast"></i></div>
                        <h4>Fast Delivery</h4>
                        <p class="text-muted">Proses produksi efisien dengan pelacakan pesanan secara real-time.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="product-showcase">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold" style="color: var(--primary);">How It Works</h2>
                <p class="lead text-muted">Langkah mudah untuk mendapatkan pakaian kustom kamu</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="mb-3"><div class="rounded-circle step-number d-inline-flex align-items-center justify-content-center">1</div></div>
                    <h5>Choose Product</h5>
                    <p class="text-muted small">Pilih jenis pakaian dari katalog kami.</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3"><div class="rounded-circle step-number d-inline-flex align-items-center justify-content-center">2</div></div>
                    <h5>Customize</h5>
                    <p class="text-muted small">Atur ukuran, warna, dan pasang desainmu.</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3"><div class="rounded-circle step-number d-inline-flex align-items-center justify-content-center">3</div></div>
                    <h5>Order & Pay</h5>
                    <p class="text-muted small">Checkout dengan berbagai metode pembayaran aman.</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3"><div class="rounded-circle step-number d-inline-flex align-items-center justify-content-center">4</div></div>
                    <h5>Receive</h5>
                    <p class="text-muted small">Duduk manis, pesananmu sedang dikirim!</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row g-4 text-start">
                <div class="col-lg-4">
                    <h4 class="text-white fw-bold mb-3">Apparify</h4>
                    <p class="small">Platform penyedia apparel custom berkualitas tinggi dengan desain yang bisa Anda tentukan sendiri. Kami mengutamakan kualitas bahan dan kepuasan pelanggan.</p>
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
                    <p class="small mb-2"><i class="fas fa-map-marker-alt me-2" style="color: var(--primary)"></i> Depok, Jawa Barat, Indonesia</p>
                    <p class="small mb-2"><i class="fas fa-envelope me-2" style="color: var(--primary)"></i> support@apparify.com</p>
                    <p class="small"><i class="fas fa-phone me-2" style="color: var(--primary)"></i> +62 812 3456 7890</p>
                </div>
            </div>
            <hr class="my-4 opacity-25" style="border-top: 1px solid #ffffff;">
            <div class="text-center small">
                <p class="mb-0">&copy; 2025 <strong>Apparify</strong>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>