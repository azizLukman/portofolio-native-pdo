<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

// proteksi login
include '../config/session.php';

// koneksi database
include '../config/koneksi.php';

// data count
$skill = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM skill"));
$pendidikan = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM pendidikan"));
$sertifikat = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM sertifikat"));
$hobi = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM hobi"));
$pengalaman = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM pengalaman"));
$tentang = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tentang"));

// Tambahan count untuk menu proyek jika ada tabelnya (jika tidak ada, akan default ke 0 atau silakan sesuaikan nama tabelnya)
$proyek = isset($conn) ? mysqli_num_rows(mysqli_query($conn,"SHOW TABLES LIKE 'proyek'")) > 0 ? mysqli_num_rows(mysqli_query($conn,"SELECT * FROM proyek")) : 0 : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        html{
            scroll-behavior:smooth;
        }

        body{
            margin:0;
            background: radial-gradient(circle at top, #111a2e, #070a12);
            color:white;
            font-family: 'Segoe UI', sans-serif;
            padding-bottom:120px;
        }

        /* NAVBAR (Sesuai Gambar) */
        .navbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:15px 30px;
            background: rgba(7, 10, 18, 0.6);
            backdrop-filter: blur(20px);
            border-bottom:1px solid rgba(255,255,255,.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-left{
            display:flex;
            align-items:center;
            gap:10px;
            font-weight:700;
        }
        
        .nav-logo {
            font-size: 22px;
            font-weight: 900;
            color: #3b82f6;
            margin-right: 5px;
        }

        .nav-title-wrapper {
            display: flex;
            flex-direction: column;
        }

        .nav-title-wrapper strong {
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .nav-title-wrapper span {
            font-size: 11px;
            color: #888;
        }

        .nav-menu a{
            color: #bbb;
            margin:0 12px;
            text-decoration:none;
            font-size:13px;
            transition: color .2s;
        }

        .nav-menu a:hover, .nav-menu a.active{
            color:white;
        }

        .btn-cv{
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.03);
            color:white;
            padding:6px 16px;
            border-radius:20px;
            font-size:12px;
            text-decoration:none;
            transition: background 0.2s;
        } 

        .btn-cv:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }  

        /* HERO TITLE SECTION */
        .hero{
            text-align:center;
            padding:50px 20px 30px;
        }

        .hero h2{
            font-weight:700;
            font-size: 40px;
        }
        
        .hero h2 span {
            color: #5b7cff;
            background: linear-gradient(90deg, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p{
            color:#8892b0;
            max-width:700px;
            margin:10px auto 0;
            font-size: 14px;
        }
        
        .hero-line {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        /* 4-COLUMN GRID LAYOUT (Sesuai Mockup Gambar Menu Portofolio) */
        .card-box{
            display:flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            background: rgba(14, 20, 38, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 16px;
            padding: 35px 20px;
            text-align: center;
            transition: all 0.25s ease;
            height: 100%;
            color: white;
            text-decoration: none;
        }

        .card-box:hover{
            transform: translateY(-5px);
            background: rgba(17, 25, 48, 0.6);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            color: white;
        }

        /* Lingkaran Ikon Melingkar Bulat */
        .icon-circle{
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        /* Warna Lingkaran Pendaran Berdasarkan Mockup */
        .box-blue .icon-circle { border-color: rgba(59, 130, 246, 0.3); color: #3b82f6; }
        .box-green .icon-circle { border-color: rgba(16, 185, 129, 0.3); color: #10b981; }
        .box-yellow .icon-circle { border-color: rgba(245, 158, 11, 0.3); color: #f59e0b; }
        .box-purple .icon-circle { border-color: rgba(139, 92, 246, 0.3); color: #8b5cf6; }
        .box-pink .icon-circle { border-color: rgba(236, 72, 153, 0.3); color: #ec4899; }
        .box-cyan .icon-circle { border-color: rgba(6, 182, 212, 0.3); color: #06b6d4; }

        .menu-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .menu-desc {
            font-size: 12px;
            color: #8892b0;
            line-height: 1.5;
            margin-bottom: 20px;
            min-height: 36px;
        }

        .count-badge {
            font-size: 11px;
            color: #718096;
            margin-bottom: 15px;
            background: rgba(255,255,255,0.03);
            padding: 2px 10px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.02);
        }

        /* Tombol Pill Outline "Lihat Detail →" */
        .btn-open{
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.02);
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 12px;
            color: white;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* Efek Hover Tombol Menyesuaikan Varian Warna */
        .box-blue:hover .btn-open { background: #3b82f6; border-color: #3b82f6; }
        .box-green:hover .btn-open { background: #10b981; border-color: #10b981; }
        .box-yellow:hover .btn-open { background: #f59e0b; border-color: #f59e0b; }
        .box-purple:hover .btn-open { background: #8b5cf6; border-color: #8b5cf6; }
        .box-pink:hover .btn-open { background: #ec4899; border-color: #ec4899; }
        .box-cyan:hover .btn-open { background: #06b6d4; border-color: #06b6d4; }

        /* ANIMATION WRAPPER */
        .animate-card-wrapper {
            opacity:0;
            transform: translateY(15px);
            animation: fadeUp .5s forwards;
        }

        @keyframes fadeUp{
            to{
                opacity:1;
                transform: translateY(0);
            }
        }

        .row > div:nth-child(1) .animate-card-wrapper { animation-delay: 0.05s; }
        .row > div:nth-child(2) .animate-card-wrapper { animation-delay: 0.1s; }
        .row > div:nth-child(3) .animate-card-wrapper { animation-delay: 0.15s; }
        .row > div:nth-child(4) .animate-card-wrapper { animation-delay: 0.2s; }
        .row > div:nth-child(5) .animate-card-wrapper { animation-delay: 0.25s; }
        .row > div:nth-child(6) .animate-card-wrapper { animation-delay: 0.3s; }
        .row > div:nth-child(7) .animate-card-wrapper { animation-delay: 0.35s; }
        .row > div:nth-child(8) .animate-card-wrapper { animation-delay: 0.4s; }

        /* FOOTER STRIP BOTTOM */
        .social-box{
            position:fixed;
            bottom:15px;
            left:15px;
            right:15px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:12px 25px;
            border-radius:12px;
            background: rgba(10, 15, 30, 0.7);
            backdrop-filter: blur(20px);
            border:1px solid rgba(255,255,255,.05);
            z-index:999;
        }

        .social-left{
            display:flex;
            gap:8px;
        }

        .social-left a{
            width:34px;
            height:34px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
            color:#bbb;
            background: rgba(255, 255, 255, 0.03);
            border:1px solid rgba(255,255,255,.05);
            text-decoration:none;
            transition:.2s;
            font-size: 13px;
        }

        .social-left a:hover{
            transform:translateY(-2px);
            color: white;
            background: #3b82f6;
        }

        .social-center{
            font-size:12px;
            color:#8892b0;
        }

        .social-right{
            font-size:12px;
            color:#8892b0;
        }
    </style>
</head>
<body>

<div id="beranda"></div>

<div class="navbar">
    <div class="nav-left">
        <div class="nav-logo">A</div>
        <div class="nav-title-wrapper">
            <strong>AZIZ LUKMANUDIN</strong>
            <span>Digital Creator</span>
        </div>
    </div>

    <div class="nav-menu">
        <a href="#beranda" class="active">Beranda</a>
        <a href="tentang/index.php">Tentang Saya</a>
        <a href="skill/index.php">Skill</a>
        <a href="pendidikan/index.php">Pendidikan</a>
        <a href="sertifikat/index.php">Sertifikat</a>
        <a href="hobi/index.php">Hobi</a>
        <a href="pengalaman/index.php">Pengalaman Kerja</a>
        <a href="kontak/index.php">Kontak</a>
    </div>

    <a href="../assets/cv/aziz-lukmanudin.pdf" class="btn-cv" download>
        <i class="fa-solid fa-download me-1"></i> Download CV
    </a>
</div>

<div class="container">
    <div class="hero">
        <h2>Menu <span>Portofolio</span></h2>
        <p>Jelajahi setiap bagian untuk mengenal saya lebih dalam. Klik pada menu yang ingin Anda lihat.</p>
        <div class="hero-line"></div>
    </div>
</div>

<div class="container pb-5 px-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

        <div class="col">
            <div class="animate-card-wrapper h-100">
                <a href="tentang/index.php" class="card-box box-blue">
                    <div class="icon-circle"><i class="fa-solid fa-user"></i></div>
                    <div class="menu-title">Tentang Saya</div>
                    <div class="menu-desc">Informasi singkat tentang diri saya, latar belakang, visi dan misi.</div>
                    <div class="count-badge"><?= $tentang ?> data terisi</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

        <div class="col">
            <div class="animate-card-wrapper h-100">
                <a href="skill/index.php" class="card-box box-green">
                    <div class="icon-circle"><i class="fa-solid fa-code"></i></div>
                    <div class="menu-title">Skill</div>
                    <div class="menu-desc">Kemampuan dan keahlian yang saya miliki dalam bidang teknologi dan lainnya.</div>
                    <div class="count-badge"><?= $skill ?> data terisi</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

        <div class="col">
            <div class="animate-card-wrapper h-100">
                <a href="pendidikan/index.php" class="card-box box-yellow">
                    <div class="icon-circle"><i class="fa-solid fa-graduation-cap"></i></div>
                    <div class="menu-title">Pendidikan</div>
                    <div class="menu-desc">Riwayat pendidikan formal yang telah saya tempuh hingga saat ini.</div>
                    <div class="count-badge"><?= $pendidikan ?> data terisi</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

        <div class="col">
            <div class="animate-card-wrapper h-100">
                <a href="sertifikat/index.php" class="card-box box-purple">
                    <div class="icon-circle"><i class="fa-solid fa-certificate"></i></div>
                    <div class="menu-title">Sertifikat</div>
                    <div class="menu-desc">Daftar sertifikat dan penghargaan yang telah saya peroleh.</div>
                    <div class="count-badge"><?= $sertifikat ?> data terisi</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

        <div class="col">
            <div class="animate-card-wrapper h-100">
                <a href="hobi/index.php" class="card-box box-pink">
                    <div class="icon-circle"><i class="fa-solid fa-heart"></i></div>
                    <div class="menu-title">Hobi</div>
                    <div class="menu-desc">Kegiatan dan minat pribadi yang saya gemari di luar pekerjaan.</div>
                    <div class="count-badge"><?= $hobi ?> data terisi</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

        <div class="col">
            <div class="animate-card-wrapper h-100">
                <a href="pengalaman/index.php" class="card-box box-blue">
                    <div class="icon-circle"><i class="fa-solid fa-briefcase"></i></div>
                    <div class="menu-title">Pengalaman Kerja</div>
                    <div class="menu-desc">Riwayat pengalaman kerja di berbagai perusahaan / organisasi.</div>
                    <div class="count-badge"><?= $pengalaman ?> data terisi</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

        <div class="col" id="kontak-menu">
            <div class="animate-card-wrapper h-100">
                <a href="kontak/index.php" class="card-box box-green">
                    <div class="icon-circle"><i class="fa-solid fa-address-book"></i></div>
                    <div class="menu-title">Kontak</div>
                    <div class="menu-desc">Informasi kontak yang dapat digunakan untuk terhubung dengan saya.</div>
                    <div class="count-badge">Sistem Terbuka</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

        <div class="col">
            <div class="animate-card-wrapper h-100">
                <a href="proyek/index.php" class="card-box box-cyan">
                    <div class="icon-circle"><i class="fa-solid fa-star"></i></div>
                    <div class="menu-title">Proyek / Karya</div>
                    <div class="menu-desc">Beberapa proyek dan karya terbaik yang pernah saya kerjakan.</div>
                    <div class="count-badge"><?= $proyek ?> data terisi</div>
                    <span class="btn-open">Lihat Detail <i class="fa-solid fa-arrow-right-long"></i></span>
                </a>
            </div>
        </div>

    </div>
</div>

<div class="social-box" id="kontak">
    <div class="social-left">
        <a href="https://linkedin.com" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
        <a href="https://github.com" target="_blank"><i class="fa-brands fa-github"></i></a>
        <a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
        <a href="mailto:kamu@email.com"><i class="fa-regular fa-envelope"></i></a>
    </div>

    <div class="social-center">
        <i class="fa-solid fa-quote-left me-1" style="font-size: 9px; opacity: 0.5;"></i> 
        Terus belajar, terus berkarya, dan jangan pernah berhenti berkembang. 
        <i class="fa-solid fa-quote-right ms-1" style="font-size: 9px; opacity: 0.5;"></i>
    </div>

    <div class="social-right">
        &copy; 2024 Rizky Pratama. All rights reserved.
    </div>
</div>

<script>
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', function(){
        document.querySelectorAll('.nav-menu a').forEach(a => a.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

</body>
</html>