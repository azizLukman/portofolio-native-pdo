<?php
include '../../config/session.php';
include '../../config/koneksi.php';

// Mengambil data pengalaman kerja
$query = mysqli_query($conn, "SELECT * FROM pengalaman");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengalaman Kerja</title>
    
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

        body { background: var(--bg-dark); background: var(--bg-radial); color: #ffffff; font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }

        .navbar { background: rgba(7, 10, 18, 0.6); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, .05); position: sticky; top: 0; z-index: 1000; }
        .nav-left { display: flex; align-items: center; gap: 10px; font-weight: 700; }
        .nav-logo { font-size: 22px; font-weight: 900; color: #3b82f6; margin-right: 5px; }
        .nav-title-wrapper { display: flex; flex-direction: column; }
        .nav-title-wrapper strong { font-size: 14px; }
        .nav-title-wrapper span { font-size: 11px; color: #888; }
        .nav-menu a { color: #bbb; margin: 0 12px; text-decoration: none; font-size: 13px; }
        .nav-menu a:hover, .nav-menu a.active { color: white; }

        .btn-cv { border: 1px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.03); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; text-decoration: none; }
        .btn-cv:hover { background: #ffffff; color: var(--bg-dark); }

        .main-content { padding: 60px 0; flex: 1; }
        .gradient-text { background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .glass-card { background: rgba(17, 24, 39, 0.4); border: 1px solid var(--card-border); border-radius: 20px; padding: 30px; backdrop-filter: blur(10px); }

        .table-premium { color: #ffffff !important; margin-bottom: 0; }
        .table-premium th { background: rgba(255, 255, 255, 0.03) !important; color: #cbd5e1 !important; font-size: 12px; text-transform: uppercase; padding: 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important; }
        .table-premium td { padding: 18px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important; background: transparent !important; color: #f3f4f6 !important; vertical-align: middle; }

        .btn-action { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s ease; text-decoration: none; }
        .btn-gradient-add { background: linear-gradient(90deg, #6366f1, #a855f7); color: white; padding: 10px 24px; border-radius: 30px; font-size: 14px; text-decoration: none; }
        footer { border-top: 1px solid var(--card-border); padding: 25px 0; font-size: 13px; color: var(--text-muted); text-align: center; }
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
                <a href="#" class="active">Pengalaman Kerja</a>
                <a href="../kontak/index.php">Kontak</a>
            </div>
        </div>
        <div class="d-none d-lg-block">
            <a href="../dashboard.php" class="btn-cv"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
        </div>
    </div>
</nav>

<div class="container main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="text-primary fw-bold small mb-1"><i class="fa-solid fa-briefcase me-2"></i>MODUL PENGALAMAN</div>
            <h1 class="fs-2 fw-bold text-white">DAFTAR <span class="gradient-text">PEKERJAAN</span></h1>
        </div>
        <a href="tambah.php" class="btn-gradient-add"><i class="fa-solid fa-plus me-2"></i>Tambah Data</a>
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-premium text-nowrap">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Posisi</th>
                        <th>Perusahaan</th>
                        <th>Tahun</th>
                        <th>Deskripsi</th> <!-- Kolom Baru -->
                        <th class="text-center">Aksi</th>
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
                        <td><strong><?= htmlspecialchars($data['posisi'] ?? '-') ?></strong></td>
                        <td><?= htmlspecialchars($data['perusahaan'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($data['tahun_mulai'] ?? '-') ?> - <?= htmlspecialchars($data['tahun_selesai'] ?? '-') ?></td>
                        <!-- Kolom Deskripsi dengan batasan lebar agar tidak terlalu panjang -->
                        <td style="max-width: 250px;" class="text-truncate" title="<?= htmlspecialchars($data['deskripsi'] ?? '-') ?>">
                            <?= htmlspecialchars($data['deskripsi'] ?? '-') ?>
                        </td>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $data['id_pengalaman'] ?>" class="btn-action bg-primary bg-opacity-20 text-primary me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="proses.php?aksi=hapus&id=<?= $data['id_pengalaman'] ?>" class="btn-action bg-danger bg-opacity-20 text-danger" onclick="return confirm('Yakin hapus?')"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>&copy; <?= date('Y') ?> Aziz Lukmanudin.</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>