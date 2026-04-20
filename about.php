<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - UNIB HOTEL | Pengalaman Menginap Mewah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gold: #C6A43F;
            --primary-gold-dark: #A8882E;
            --dark-bg: #0A0A0A;
            --dark-gray: #1A1A1A;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-gray) 100%);
            color: white;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            opacity: 0.15;
            z-index: -2;
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10,10,10,0.95), rgba(26,26,26,0.95));
            z-index: -1;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gold);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-gold-dark);
        }
        
        /* Modern Navbar */
        .navbar-luxury {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            border-bottom: 1px solid rgba(198, 164, 63, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
            letter-spacing: 2px;
        }
        
        .navbar-brand i {
            color: var(--primary-gold);
            margin-right: 8px;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 15px;
            transition: 0.3s;
            position: relative;
        }
        
        .nav-link::after {
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
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }
        
        .nav-link:hover {
            color: var(--primary-gold) !important;
        }
        
        /* Hero Section */
        .hero-about {
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
        }
        
        .hero-subtitle {
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--primary-gold);
            margin-bottom: 20px;
        }
        
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff, var(--primary-gold));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .gold-line {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-dark));
            margin: 30px auto;
        }
        
        /* Stats Section */
        .stats-section {
            padding: 60px 0;
            margin: 40px 0;
        }
        
        .stats-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 50px 0;
            border: 1px solid rgba(198, 164, 63, 0.3);
        }
        
        .stat-item {
            text-align: center;
            position: relative;
        }
        
        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 50px;
            background: linear-gradient(180deg, transparent, var(--primary-gold), transparent);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-gold);
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #aaa;
        }
        
        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 40px;
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .glass-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-gold);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: 0.3s;
        }
        
        .icon-circle i {
            font-size: 2.5rem;
            color: var(--dark-bg);
        }
        
        .glass-card:hover .icon-circle {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 0 30px rgba(198, 164, 63, 0.5);
        }
        
        .glass-card h4 {
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .glass-card p {
            color: #ccc;
            line-height: 1.6;
        }
        
        /* Vision Mission Section */
        .vision-mission {
            padding: 80px 0;
        }
        
        .vm-card {
            background: linear-gradient(135deg, rgba(198,164,63,0.1), rgba(168,136,46,0.05));
            border: 1px solid rgba(198,164,63,0.3);
            border-radius: 25px;
            padding: 40px;
            transition: 0.3s;
        }
        
        .vm-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-gold);
        }
        
        .vm-icon {
            font-size: 3rem;
            color: var(--primary-gold);
            margin-bottom: 20px;
        }
        
        /* Team Section */
        .team-section {
            padding: 80px 0;
        }
        
        .team-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            transition: 0.3s;
            text-align: center;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-gold);
        }
        
        .team-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 30px auto 20px;
            overflow: hidden;
            border: 3px solid var(--primary-gold);
        }
        
        .team-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .team-card h5 {
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .team-card p {
            color: var(--primary-gold);
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        
        /* Footer */
        .footer {
            background: rgba(10, 10, 10, 0.95);
            padding: 60px 0 30px;
            margin-top: 80px;
            border-top: 1px solid rgba(198, 164, 63, 0.3);
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
        
        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .stat-item:not(:last-child)::after {
                display: none;
            }
            
            .glass-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<!-- Modern Navbar dengan Menu Lengkap -->
<nav class="navbar-luxury">
    <div class="container">
        <a class="navbar-brand" href="customer/index.php">
            <i class="bi bi-building"></i> UNIB HOTEL
        </a>
        <div class="d-flex align-items-center">
            <a class="nav-link" href="customer/index.php">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
            <a class="nav-link active" href="about.php">
                <i class="bi bi-info-circle me-1"></i> Tentang Kami
            </a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-about">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="hero-subtitle">Tentang Kami</div>
            <h1 class="hero-title">Pengalaman Menginap<br>Yang Tak Terlupakan</h1>
            <div class="gold-line"></div>
            <p class="text-white-50 mx-auto" style="max-width: 700px;">
            Terletak strategis di jantung kota Bengkulu, UNIB Hotel hadir sebagai destinasi menginap premium yang memadukan kemewahan modern dengan keramahan tradisional Indonesia.
            </p>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-box" data-aos="fade-up" data-aos-duration="1000">
            <div class="row">
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Kamar Mewah</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number">12+</div>
                    <div class="stat-label">Tahun Berdiri</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number">5+</div>
                    <div class="stat-label">Restoran</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Layanan Prima</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="glass-card text-center" data-aos="fade-up" data-aos-duration="1000">
                <i class="bi bi-quote fs-1" style="color: var(--primary-gold);"></i>
                <p class="fs-4 mb-4" style="font-family: 'Playfair Display', serif; color: white;">
                    "Menghadirkan kemewahan yang autentik dengan sentuhan keramahan khas Indonesia"
                </p>
                <p class="mb-0">
                    Sejak didirikan pada tahun 2014, UNIB Hotel telah menjadi ikon hospitality di Bengkulu. 
                    Dengan desain arsitektur yang memadukan elemen modern dan tradisional, setiap sudut hotel kami 
                    dirancang untuk memberikan pengalaman menginap yang tak terlupakan. Kami percaya bahwa pelayanan 
                    yang tulus dan perhatian terhadap detail adalah kunci menciptakan momen-momen berharga bagi setiap tamu.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="container mb-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h5 class="text-uppercase mb-3" style="letter-spacing: 5px; color: var(--primary-gold);">Nilai Kami</h5>
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif;">Mengapa Memilih Kami?</h2>
        <div class="gold-line"></div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="glass-card text-center">
                <div class="icon-circle">
                    <i class="bi bi-gem"></i>
                </div>
                <h4>Kualitas Premium</h4>
                <p>Setiap kamar dilengkapi dengan furnitur premium, linen berkualitas tinggi, dan fasilitas modern untuk kenyamanan maksimal Anda.</p>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="glass-card text-center">
                <div class="icon-circle">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h4>Keamanan & Privasi</h4>
                <p>Sistem keamanan 24 jam dengan CCTV dan personil keamanan profesional, serta layanan privasi tamu yang terjamin.</p>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="glass-card text-center">
                <div class="icon-circle">
                    <i class="bi bi-cup-hot"></i>
                </div>
                <h4>Kuliner Eksklusif</h4>
                <p>Nikmati hidangan lezat dari chef profesional dengan berbagai pilihan menu lokal dan internasional.</p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="vision-mission">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="vm-card">
                    <div class="vm-icon">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Visi</h3>
                    <p class="text-white-50">
                        Menjadi hotel bintang lima terdepan di Bengkulu yang dikenal dengan pelayanan kelas dunia, 
                        inovasi berkelanjutan, dan pengalaman menginap yang tak terlupakan bagi setiap tamu.
                    </p>
                </div>
            </div>
            
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="vm-card">
                    <div class="vm-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Misi</h3>
                    <ul class="text-white-50" style="padding-left: 20px;">
                        <li class="mb-2">Memberikan pelayanan terbaik yang melebihi harapan tamu</li>
                        <li class="mb-2">Mengembangkan SDM profesional di bidang perhotelan</li>
                        <li class="mb-2">Menjaga standar kebersihan dan kenyamanan tertinggi</li>
                        <li>Berkontribusi pada pariwisata dan ekonomi lokal Bengkulu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h5 class="text-uppercase mb-3" style="letter-spacing: 5px; color: var(--primary-gold);">Tim Kami</h5>
            <h2 class="fw-bold" style="font-family: 'Playfair Display', serif;">Profesional Berpengalaman</h2>
            <div class="gold-line"></div>
        </div>
        
        <div class="row g-4">
            <!-- Tim 1 - General Manager -->
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="team-card">
                    <div class="team-image">
                        <img src="assets/img/team/manager.jpg" alt="General Manager" onerror="this.src='https://randomuser.me/api/portraits/men/32.jpg'">
                    </div>
                    <h5>Algedian</h5>
                    <p>General Manager</p>
                </div>
            </div>
            
            <!-- Tim 2 - Hotel Manager -->
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="team-card">
                    <div class="team-image">
                        <img src="assets/img/team/hotel-manager.jpg" alt="Hotel Manager" onerror="this.src='https://randomuser.me/api/portraits/women/44.jpg'">
                    </div>
                    <h5>Prof Arya</h5>
                    <p>Hotel Manager</p>
                </div>
            </div>
            
            <!-- Tim 3 - Executive Chef -->
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="team-card">
                    <div class="team-image">
                        <img src="assets/img/team/chef.jpg" alt="Executive Chef" onerror="this.src='https://randomuser.me/api/portraits/men/67.jpg'">
                    </div>
                    <h5>Chef afifah</h5>
                    <p>Executive Chef</p>
                </div>
            </div>
            
            <!-- Tim 4 - Marketing Manager -->
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                <div class="team-card">
                    <div class="team-image">
                        <img src="assets/img/team/marketing.jpg" alt="Marketing Manager" onerror="this.src='https://randomuser.me/api/portraits/women/68.jpg'">
                    </div>
                    <h5>Widya Bakwan</h5>
                    <p>Marketing Manager</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5><i class="bi bi-building"></i> UNIB HOTEL</h5>
                <p class="text-muted small">Hotel mewah dengan pelayanan terbaik di Bengkulu. Pengalaman menginap yang tak terlupakan.</p>
                <div class="social-links">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h5>Kontak Kami</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2" style="color: var(--primary-gold);"></i> Jl. UNIB No. 1, Bengkulu</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2" style="color: var(--primary-gold);"></i> +62 123 4567 890</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2" style="color: var(--primary-gold);"></i> info@unibhotel.com</li>
                </ul>
            </div>
            <div class="col-md-4">
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
        <div class="text-center">
            <p class="text-muted small mb-0">&copy; 2024 UNIB Hotel. All Rights Reserved.</p>
        </div>
    </div>
</footer>

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
        const navbar = document.querySelector('.navbar-luxury');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(10, 10, 10, 0.98)';
        } else {
            navbar.style.background = 'rgba(10, 10, 10, 0.95)';
        }
    });
</script>
</body>
</html>