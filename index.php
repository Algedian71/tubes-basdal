<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIB Hotel - Pengalaman Menginap Mewah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gold: #C6A43F;
            --primary-gold-dark: #A8882E;
            --dark-bg: #0A0A0A;
            --dark-gray: #1A1A1A;
            --light-gray: #F8F9FA;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            color: #333;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gold);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-gold-dark);
        }
        
        /* Hero Section with Parallax */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 700px;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                        url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background Overlay */
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 100%);
            z-index: 1;
        }
        
        /* Luxury Navbar */
        .navbar-luxury {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 25px 0;
            background: transparent;
            z-index: 1000;
            transition: all 0.4s ease;
        }
        
        .navbar-luxury.scrolled {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-luxury .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            letter-spacing: 2px;
        }
        
        .navbar-luxury .navbar-brand i {
            color: var(--primary-gold);
            margin-right: 8px;
        }
        
        .navbar-luxury .nav-link {
            color: white;
            font-weight: 500;
            margin: 0 15px;
            transition: 0.3s;
            position: relative;
        }
        
        .navbar-luxury .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary-gold);
            transition: 0.3s;
        }
        
        .navbar-luxury .nav-link:hover::after,
        .navbar-luxury .nav-link.active::after {
            width: 80%;
        }
        
        .navbar-luxury .nav-link:hover {
            color: var(--primary-gold);
        }
        
        /* Hero Content */
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            padding: 0 20px;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: var(--primary-gold);
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 5rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 1.2rem;
            font-weight: 300;
            margin-bottom: 40px;
            opacity: 0.95;
        }
        
        /* Modern Buttons */
        .btn-luxury {
            background: transparent;
            border: 2px solid var(--primary-gold);
            color: white;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-luxury::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-luxury:hover::before {
            left: 100%;
        }
        
        .btn-luxury:hover {
            background: var(--primary-gold);
            border-color: var(--primary-gold);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(198, 164, 63, 0.3);
        }
        
        .btn-luxury-primary {
            background: var(--primary-gold);
            border-color: var(--primary-gold);
            color: white;
        }
        
        .btn-luxury-primary:hover {
            background: var(--primary-gold-dark);
            border-color: var(--primary-gold-dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(198, 164, 63, 0.4);
        }
        
        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: white;
        }
        
        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--dark-bg);
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.4s ease;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-dark));
            transform: scaleX(0);
            transition: 0.4s;
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(198,164,63,0.1), rgba(168,136,46,0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .feature-icon i {
            font-size: 2.5rem;
            color: var(--primary-gold);
        }
        
        .feature-card h4 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        
        /* Room Showcase */
        .rooms-section {
            padding: 100px 0;
            background: var(--light-gray);
        }
        
        .room-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
        }
        
        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .room-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }
        
        .room-card:hover .room-image img {
            transform: scale(1.1);
        }
        
        .room-price {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--primary-gold);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .room-content {
            padding: 25px;
        }
        
        .room-content h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .room-features {
            display: flex;
            gap: 15px;
            margin: 15px 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .room-features i {
            color: var(--primary-gold);
            margin-right: 5px;
        }
        
        /* Testimonials */
        .testimonials-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--dark-bg), var(--dark-gray));
            color: white;
        }
        
        .testimonial-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            margin: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            transition: 0.3s;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.1);
        }
        
        .testimonial-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            font-style: italic;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .testimonial-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .testimonial-author h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .testimonial-author p {
            color: var(--primary-gold);
            font-size: 0.9rem;
        }
        
        /* Newsletter */
        .newsletter-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            color: white;
        }
        
        .newsletter-form {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .newsletter-form .input-group {
            background: white;
            border-radius: 50px;
            overflow: hidden;
            padding: 5px;
        }
        
        .newsletter-form input {
            border: none;
            padding: 15px 25px;
            font-size: 1rem;
        }
        
        .newsletter-form button {
            background: var(--dark-bg);
            border: none;
            padding: 15px 30px;
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .newsletter-form button:hover {
            background: #000;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-bg);
            color: white;
            padding: 60px 0 20px;
        }
        
        .footer h5 {
            font-family: 'Playfair Display', serif;
            margin-bottom: 20px;
        }
        
        .footer a {
            color: #aaa;
            text-decoration: none;
            transition: 0.3s;
        }
        
        .footer a:hover {
            color: var(--primary-gold);
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            transition: 0.3s;
        }
        
        .social-links a:hover {
            background: var(--primary-gold);
            transform: translateY(-3px);
        }
        
        /* Floating WhatsApp */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            transition: 0.3s;
            z-index: 1000;
        }
        
        .whatsapp-float:hover {
            transform: scale(1.1);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .btn-luxury {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

<!-- Luxury Navbar -->
<nav class="navbar navbar-expand-lg navbar-luxury" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-building"></i> UNIB HOTEL
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#rooms">Kamar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#testimonials">Testimoni</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Kontak</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
        <div class="hero-subtitle">Welcome to UNIB Hotel</div>
        <h1>Temukan Kenyamanan & Kemewahan Sejati</h1>
        <p>Pengalaman menginap tak terlupakan di UNIB BELAKANG, Indonesia.</p>
        <div class="hero-btns mt-4">
            <a href="auth/login.php" class="btn-luxury mx-2">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
            <a href="auth/register.php" class="btn-luxury btn-luxury-primary mx-2">
                Buat Akun <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">Mengapa Memilih Kami</span>
            <h2 class="section-title">Fasilitas & Layanan Premium</h2>
            <p class="text-muted">Nikmati pengalaman menginap dengan fasilitas terbaik yang kami sediakan</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-wifi"></i>
                    </div>
                    <h4>WiFi Premium</h4>
                    <p>Koneksi internet super cepat 24 jam untuk mendukung aktivitas Anda selama menginap</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h4>Breakfast Buffet</h4>
                    <p>Nikmati sarapan prasmanan dengan berbagai menu internasional setiap pagi</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <h4>Kolam Renang</h4>
                    <p>Kolam renang outdoor dengan pemandangan kota yang menakjubkan</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-gem"></i>
                    </div>
                    <h4>Layanan Spa</h4>
                    <p>Relaksasi dengan berbagai perawatan spa tradisional dan modern</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h4>Meeting Room</h4>
                    <p>Ruangan meeting modern untuk kebutuhan bisnis Anda</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <h4>Parkir Luas</h4>
                    <p>Area parkir yang luas dan aman untuk kenyamanan tamu</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rooms Showcase -->
<section class="rooms-section" id="rooms">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">Kamar Kami</span>
            <h2 class="section-title">Tipe Kamar Unggulan</h2>
            <p class="text-muted">Pilih kamar yang sesuai dengan kebutuhan dan budget Anda</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="room-card">
                    <div class="room-image">
                        <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600&h=400&fit=crop" alt="Deluxe Room">
                        <div class="room-price">Rp 500K/malam</div>
                    </div>
                    <div class="room-content">
                        <h4>Deluxe Room</h4>
                        <div class="room-features">
                            <span><i class="bi bi-person"></i> 2 Orang</span>
                            <span><i class="bi bi-aspect-ratio"></i> 32 m²</span>
                        </div>
                        <p>Kamar nyaman dengan fasilitas lengkap untuk pengalaman menginap yang tak terlupakan.</p>
                        <a href="auth/login.php" class="btn-luxury btn-luxury-primary w-100 text-center" style="display: inline-block;">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="room-card">
                    <div class="room-image">
                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&h=400&fit=crop" alt="Suite Room">
                        <div class="room-price">Rp 1M/malam</div>
                    </div>
                    <div class="room-content">
                        <h4>Executive Suite</h4>
                        <div class="room-features">
                            <span><i class="bi bi-person"></i> 4 Orang</span>
                            <span><i class="bi bi-aspect-ratio"></i> 64 m²</span>
                        </div>
                        <p>Suite mewah dengan ruang tamu terpisah dan pemandangan kota yang spektakuler.</p>
                        <a href="auth/login.php" class="btn-luxury btn-luxury-primary w-100 text-center" style="display: inline-block;">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="room-card">
                    <div class="room-image">
                        <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&h=400&fit=crop" alt="Presidential Suite">
                        <div class="room-price">Rp 3M/malam</div>
                    </div>
                    <div class="room-content">
                        <h4>Presidential Suite</h4>
                        <div class="room-features">
                            <span><i class="bi bi-person"></i> 6 Orang</span>
                            <span><i class="bi bi-aspect-ratio"></i> 128 m²</span>
                        </div>
                        <p>Kemewahan tertinggi dengan fasilitas premium dan pelayanan personal butler.</p>
                        <a href="auth/login.php" class="btn-luxury btn-luxury-primary w-100 text-center" style="display: inline-block;">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section" id="testimonials">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">Testimoni</span>
            <h2 class="section-title text-white">Apa Kata Tamu Kami</h2>
            <p class="text-white-50">Pengalaman nyata dari para tamu yang telah menginap di UNIB Hotel</p>
        </div>
        
        <div class="row g-4">
            <!-- Testimoni 1 - Sarah Wijaya -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div class="mb-3">
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                    </div>
                    <p class="testimonial-text">"Pelayanan sangat memuaskan, kamar bersih dan nyaman. Staf sangat ramah dan membantu. Definitely will come back!"</p>
                    <div class="testimonial-author">
                        <img src="assets/img/testimoni/customer1.jpg" alt="Sarah Wijaya" onerror="this.src='https://randomuser.me/api/portraits/women/1.jpg'">
                        <div>
                            <h6>Noor Berandalan</h6>
                            <p>Pengendali Naga Mahjong</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimoni 2 - Budi Santoso -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <div class="mb-3">
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                    </div>
                    <p class="testimonial-text">"Hotel bintang 5 dengan harga terjangkau. Lokasi strategis, makanan enak, view kamar amazing. Highly recommended!"</p>
                    <div class="testimonial-author">
                        <img src="assets/img/testimoni/customer2.jpg" alt="Budi Santoso" onerror="this.src='https://randomuser.me/api/portraits/men/2.jpg'">
                        <div>
                            <h6>Arie Vetresia & Kurnia Anggraini</h6>
                            <p>Besti Forever</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimoni 3 - Maria Angelina -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-card">
                    <div class="mb-3">
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-fill" style="color: var(--primary-gold);"></i>
                        <i class="bi bi-star-half" style="color: var(--primary-gold);"></i>
                    </div>
                    <p class="testimonial-text">"Spa dan fasilitasnya luar biasa! Sangat cocok untuk staycation. Akan booking lagi bulan depan."</p>
                    <div class="testimonial-author">
                        <img src="assets/img/testimoni/customer3.jpg" alt="Maria Angelina" onerror="this.src='https://randomuser.me/api/portraits/women/3.jpg'">
                        <div>
                            <h6>Alfi Fotografer</h6>
                            <p>Honeymoon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container text-center" data-aos="fade-up">
        <h3 class="mb-3" style="font-family: 'Playfair Display', serif;">Dapatkan Penawaran Spesial</h3>
        <p class="mb-4">Berlangganan newsletter kami untuk mendapatkan promo dan diskon eksklusif</p>
        <form class="newsletter-form">
            <div class="input-group">
                <input type="email" class="form-control" placeholder="Masukkan email Anda" required>
                <button class="btn" type="submit">Subscribe</button>
            </div>
        </form>
    </div>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up">
                <h5><i class="bi bi-building"></i> UNIB HOTEL</h5>
                <p class="text-muted">Hotel mewah dengan pelayanan terbaik di UNIB BELAKANG. Pengalaman menginap yang tak terlupakan.</p>
                <div class="social-links mt-3">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <h5>Kontak Kami</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i> Jl. UNIB No. 1, Bengkulu</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2 text-primary"></i> +62 123 4567 890</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2 text-primary"></i> info@unibhotel.com</li>
                </ul>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <h5>Jam Operasional</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">Check-in: 14:00 WIB</li>
                    <li class="mb-2">Check-out: 12:00 WIB</li>
                    <li class="mb-2">Restoran: 06:00 - 22:00 WIB</li>
                    <li class="mb-2">24 Hour Room Service</li>
                </ul>
            </div>
        </div>
        <hr class="mt-4" style="background: rgba(255,255,255,0.1);">
        <div class="text-center mt-4">
            <p class="text-muted mb-0">&copy; 2024 UNIB Hotel. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp -->
<a href="https://wa.me/621234567890" class="whatsapp-float" target="_blank">
    <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('mainNav');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
</body>
</html>