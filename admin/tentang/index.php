<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* FIX PATH DARI FOLDER NESTED - Mempertahankan Logika Asli Anda */
include '../../config/session.php';
include '../../config/koneksi.php';

// Mengambil data counter dari database secara dinamis
$total_skill = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM skill"));
$total_pendidikan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pendidikan"));
$total_sertifikat = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM sertifikat"));
$total_pengalaman = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengalaman"));

// Mengambil deskripsi spesifik tentang saya
$query_tentang = mysqli_query($conn, "SELECT * FROM tentang LIMIT 1");
$data_tentang = mysqli_fetch_assoc($query_tentang);
$deskripsi = $data_tentang['deskripsi'] ?? 'Saya adalah seorang pengembang web dan digital creator yang berfokus pada pembuatan website modern, responsif, dan user friendly.';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kelola Tentang Saya</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #030712;
            --bg-radial: radial-gradient(circle at 80% 20%, #1e1b4b 0%, #030712 60%);
            --accent-purple: #8b5cf6;
            --accent-blue: #3b82f6;
            --text-muted: #9ca3af;
            --card-bg: rgba(17, 24, 39, 0.6);
            --card-border: rgba(255, 255, 255, 0.05);
        }

        body {
            background: var(--bg-dark);
            background: var(--bg-radial);
            color: #ffffff;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            overflow-x: hidden;
        }

        /* NAVBAR STYLING */
       /* Pastikan warna teks navbar-toggler-icon terlihat di background gelap */
.navbar-toggler-icon {
    filter: invert(1);
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    background: rgba(7, 10, 18, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, .05);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-left {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
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

.nav-menu a {
    color: #bbb;
    margin: 0 12px;
    text-decoration: none;
    font-size: 13px;
    transition: color .2s;
    display: inline-block;
}

.nav-menu a:hover, .nav-menu a.active {
    color: white;
}

/* Penyesuaian responsif agar menu vertikal rapi saat di mobile screen */
@media (max-width: 991.98px) {
    .nav-menu {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding-left: 10px;
    }
    .nav-menu a {
        margin: 0;
        font-size: 15px;
    }
}

.btn-cv {
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.03);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    text-decoration: none;
    transition: background 0.2s;
    display: inline-block;
}

.btn-cv:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}           
        .btn-cv {
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-radius: 30px;
            padding: 6px 18px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-cv:hover {
            background: #ffffff;
            color: var(--bg-dark);
        }

        /* HERO SECTION AS MANAGEMENT FORM */
        .hero-section {
            padding: 80px 0 60px 0;
        }
        .hero-tag {
            font-size: 16px;
            font-weight: 500;
        }
        .hero-title {
            font-size: 44px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 5px;
        }
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* PREMIUM GLASSMORPHIC MANAGEMENT COMPONENT */
        .form-label-custom {
            font-weight: 500;
            font-size: 13px;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-control-premium {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-control-premium:focus {
            border-color: rgba(168, 85, 247, 0.5) !important;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.25) !important;
        }

        /* BUTTONS */
        .btn-gradient-primary {
            background: linear-gradient(90deg, #6366f1, #a855f7);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.5);
            color: white;
        }
        .btn-outline-custom {
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.03);
            color: white;
            padding: 14px 24px;
            border-radius: 30px;
            font-size: 14px;
            text-decoration: none;
            margin-left: 10px;
            transition: background 0.3s;
        }
        .btn-outline-custom:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* HERO VISUAL (RIGHT SIDE) */
        .hero-image-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .glow-circle {
            position: absolute;
            width: 380px;
            height: 380px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 50%;
            filter: blur(25px);
            opacity: 0.4;
            z-index: 1;
        }
        .hero-img {
            position: relative;
            z-index: 2;
            width: 320px;
            height: 380px;
            object-fit: cover;
            border-radius: 30px; 
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .floating-badge {
            position: absolute;
            bottom: 10px;
            right: 20px;
            background: rgba(10, 15, 30, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 20px;
            border-radius: 16px;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .badge-status-dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #10b981;
        }

        /* COUNTER BOX METRICS */
        .counter-container {
            background: rgba(10, 15, 30, 0.4);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 30px 20px;
            margin-top: 40px;
            backdrop-filter: blur(10px);
        }
        .counter-item {
            text-align: center;
            position: relative;
        }
        .counter-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 20%;
            height: 60%;
            width: 1px;
            background: rgba(255, 255, 255, 0.1);
        }
        @media (max-width: 767px) {
            .counter-item:not(:last-child)::after { display: none; }
            .counter-item { margin-bottom: 20px; }
        }
        .counter-icon-wrap {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: #60a5fa;
            font-size: 18px;
        }
        .counter-number {
            font-size: 28px;
            font-weight: 700;
            display: block;
            line-height: 1.2;
        }
        .counter-label {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* FOOTER */
        footer {
            border-top: 1px solid var(--card-border);
            padding: 25px 0;
            margin-top: 60px;
            font-size: 13px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-2">
        <a class="nav-left text-decoration-none" href="../dashboard.php">
            <div class="nav-logo">R</div>
            <div class="nav-title-wrapper text-white">
                <strong>AZIZ LUKMANUDIN</strong>
                <span>Workspace Control</span>
            </div>
        </a>

        <!-- Tombol Toggler untuk Mobile View -->
        <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon navbar-dark"></span>
        </button>

        <!-- Menu Navigasi Tengah -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <div class="nav-menu my-3 my-lg-0">
                <a href="../dashboard.php">Beranda</a>
                <a href="#" class="active">Tentang Saya</a>
                <a href="../skill/index.php">Skill</a>
                <a href="../pendidikan/index.php">Pendidikan</a>
                <a href="../sertifikat/index.php">Sertifikat</a>
                <a href="../hobi/index.php">Hobi</a>
                <a href="../pengalaman/index.php">Pengalaman Kerja</a>
                <a href="../kontak/index.php">Kontak</a>
            </div>
        </div>

        <!-- Tombol Aksi Kanan -->
        <div class="d-none d-lg-block">
            <a href="../dashboard.php" class="btn-cv">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</nav>
<div class="container hero-section">
    <form action="proses_ubah.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data_tentang['id'] ?? '' ?>">

        <div class="row align-items-center">
            
            <div class="col-lg-6 order-2 order-lg-1 mt-5 mt-lg-0">
                <div class="hero-tag mb-2 text-primary fw-bold"><i class="fa-solid fa-user-gear me-2"></i>MODUL KONTROL</div>
                <h1 class="hero-title">KELOLA BIO UTAMA</h1>
                <h3 class="fs-4 fw-semibold mb-4"><span class="gradient-text">Tentang Saya & File Foto</span></h3>
                
                <div class="mb-4">
                    <label class="form-label-custom d-block mb-2"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Ubah Narasi Deskripsi</label>
                    <textarea name="deskripsi" rows="6" class="form-control form-control-premium" placeholder="Tulis deskripsi profil portofolio Anda di sini..."><?= htmlspecialchars($deskripsi) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom d-block mb-2"><i class="fa-solid fa-image text-purple me-2"></i>Unggah Foto Profil Baru (Opsional)</label>
                    <input type="file" name="foto" class="form-control form-control-premium">
                </div>
                
                <div class="my-4 pt-2">
                    <button type="submit" class="btn-gradient-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Data
                    </button>
                    <a href="../dashboard.php" class="btn-outline-custom">Batal</a>
                </div>
            </div>
            
            <div class="col-lg-6 order-1 order-lg-2 hero-image-container">
                <div class="glow-circle"></div>
                
                <?php if(!empty($data_tentang['foto']) && file_exists('../../assets/img/'.$data_tentang['foto'])): ?>
                    <img src="../../assets/img/<?= $data_tentang['foto'] ?>" alt="Foto Aziz Lukmanudin" class="hero-img img-fluid">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400x500/111827/ffffff?text=Aziz+Lukmanudin" alt="Default Preview" class="hero-img img-fluid">
                <?php endif; ?>
                
                <div class="floating-badge">
                    <div class="position-relative">
                        <i class="fa-solid fa-circle-check text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-secondary" style="font-size:11px;">Status Sinkronisasi</div>
                        <div class="fw-bold text-white small">Database Aktif</div>
                        <div class="small text-muted" style="font-size:11px;"><span class="badge-status-dot me-1"></span>Terhubung</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="counter-container">
        <div class="row">
            <div class="col-6 col-md-3 counter-item">
                <div class="counter-icon-wrap"><i class="fa-solid fa-laptop-code"></i></div>
                <span class="counter-number"><?= $total_skill ?></span>
                <span class="counter-label">Total Keahlian</span>
            </div>
            <div class="col-6 col-md-3 counter-item">
                <div class="counter-icon-wrap" style="color: #c084fc;"><i class="fa-solid fa-graduation-cap"></i></div>
                <span class="counter-number"><?= $total_pendidikan ?></span>
                <span class="counter-label">Riwayat Pendidikan</span>
            </div>
            <div class="col-6 col-md-3 counter-item">
                <div class="counter-icon-wrap" style="color: #34d399;"><i class="fa-solid fa-certificate"></i></div>
                <span class="counter-number"><?= $total_sertifikat ?></span>
                <span class="counter-label">Sertifikasi</span>
            </div>
            <div class="col-6 col-md-3 counter-item">
                <div class="counter-icon-wrap" style="color: #fbbf24;"><i class="fa-solid fa-briefcase"></i></div>
                <span class="counter-number"><?= $total_pengalaman ?></span>
                <span class="counter-label">Pengalaman Kerja</span>
            </div>
        </div>
    </div>
</div>

<footer class="text-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-md-start mb-3 mb-md-0">
                <span>&copy; <?= date('Y') ?> Aziz Lukmanudin. Control Center System.</span>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="badge bg-dark border border-secondary text-secondary py-2 px-3" style="font-size: 11px;"><i class="fa-solid fa-shield-halved text-primary me-1"></i> Mode Administrator Terverifikasi</span>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>