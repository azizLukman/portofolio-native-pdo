<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/session.php';
include '../../config/koneksi.php';

// Ambil data kontak
$query = mysqli_query($conn, "SELECT * FROM kontak");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kelola Kontak</title>
    
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(7, 10, 18, 0.6);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-left { display: flex; align-items: center; gap: 10px; font-weight: 700; }
        .nav-logo { font-size: 22px; font-weight: 900; color: #3b82f6; margin-right: 5px; }
        .nav-title-wrapper { display: flex; flex-direction: column; }
        .nav-title-wrapper strong { font-size: 14px; letter-spacing: 0.5px; }
        .nav-title-wrapper span { font-size: 11px; color: #888; }
        .nav-menu a { color: #bbb; margin: 0 12px; text-decoration: none; font-size: 13px; transition: color .2s; display: inline-block; }
        .nav-menu a:hover, .nav-menu a.active { color: white; }

        .btn-cv {
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.03);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-cv:hover { background: #ffffff; color: var(--bg-dark); }

        .main-content { padding: 60px 0; flex: 1; }
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-card {
            background: rgba(17, 24, 39, 0.4);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }

        .table-premium { color: #ffffff !important; margin-bottom: 0; }
        .table-premium th {
            background: rgba(255, 255, 255, 0.03) !important;
            color: #cbd5e1 !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
        .table-premium td {
            padding: 18px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
            background: transparent !important;
            color: #f3f4f6 !important;
            vertical-align: middle;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-gradient-add {
            background: linear-gradient(90deg, #6366f1, #a855f7);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
            transition: all 0.2s ease;
        }
        .btn-gradient-add:hover { transform: translateY(-2px); color: white; }

        footer { border-top: 1px solid var(--card-border); padding: 25px 0; font-size: 13px; color: var(--text-muted); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-2">
        <a class="nav-left text-decoration-none" href="../dashboard.php">
            <div class="nav-logo">A</div>
            <div class="nav-title-wrapper text-white">
                <strong>AZIZ LUKMANUDIN</strong>
                <span>Workspace Control</span>
            </div>
        </a>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <div class="nav-menu my-3 my-lg-0">
                <a href="../dashboard.php">Beranda</a>
                <a href="../tentang/index.php">Tentang Saya</a>
                <a href="../skill/index.php">Skill</a>
                <a href="../pendidikan/index.php">Pendidikan</a>
                <a href="../sertifikat/index.php">Sertifikat</a>
                <a href="../hobi/index.php">Hobi</a>
                <a href="../pengalaman/index.php">Pengalaman Kerja</a>
                <a href="#" class="active">Kontak</a>
            </div>
        </div>
        <div class="d-none d-lg-block">
            <a href="../dashboard.php" class="btn-cv"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
        </div>
    </div>
</nav>

<div class="container main-content">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="text-primary fw-bold small mb-1"><i class="fa-solid fa-address-book me-2"></i>MODUL KONTAK</div>
            <h1 class="fs-2 fw-bold m-0 text-white">DAFTAR <span class="gradient-text">KONTAK</span></h1>
        </div>
        <div>
            <a href="tambah.php" class="btn-gradient-add">
                <i class="fa-solid fa-plus me-2"></i>Tambah Kontak
            </a>
        </div>
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-premium text-nowrap">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Platform</th>
                        <th width="35%">Link / Nomor</th>
                        <th width="15%" class="text-center">Ikon</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($query) > 0):
                        while($data = mysqli_fetch_assoc($query)): 
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><div class="fw-bold text-white"><?= htmlspecialchars($data['nama'] ?? '') ?></div></td>
                            <td><?= htmlspecialchars($data['link'] ?? '-') ?></td>
                            <td class="text-center fs-5"><i class="<?= htmlspecialchars($data['ikon'] ?? '') ?>"></i></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $data['id_kontak'] ?>" class="btn-action bg-primary bg-opacity-20 text-primary me-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="proses.php?aksi=hapus&id=<?= $data['id_kontak'] ?>" class="btn-action bg-danger bg-opacity-20 text-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-2 d-block mb-3 text-secondary"></i>
                                Belum ada data kontak yang tersimpan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="text-center">
    <div class="container">
        <span>&copy; <?= date('Y') ?> Aziz Lukmanudin. Control Center System.</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>