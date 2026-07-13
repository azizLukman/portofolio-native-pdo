<?php
include '../../config/session.php';
include '../../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM skill ORDER BY persentase DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Keahlian (Skill)</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #030712;
            --bg-radial: radial-gradient(circle at 80% 20%, #1e1b4b 0%, #030712 60%);
            --accent-purple: #8b5cf6;
            --accent-blue: #3b82f6;
            --text-muted: #9ca3af;
            --card-border: rgba(255, 255, 255, 0.05);
        }

        body {
            background: var(--bg-dark); background: var(--bg-radial); color: #ffffff;
            font-family: 'Segoe UI', Roboto, sans-serif; min-height: 100vh; display: flex; flex-direction: column;
        }

        .navbar {
            background: rgba(7, 10, 18, 0.6); backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, .05); position: sticky; top: 0; z-index: 1000;
        }
        .nav-left { display: flex; align-items: center; gap: 10px; font-weight: 700; }
        .nav-logo { font-size: 22px; font-weight: 900; color: #3b82f6; }
        .nav-menu a { color: #bbb; margin: 0 12px; text-decoration: none; font-size: 13px; }
        .nav-menu a.active { color: white; font-weight: bold; }

        .btn-cv {
            border: 1px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.03);
            color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; text-decoration: none;
        }
        .btn-cv:hover { background: #ffffff; color: var(--bg-dark); }

        .main-content { padding: 60px 0; flex: 1; }
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .glass-card {
            background: rgba(17, 24, 39, 0.4); border: 1px solid var(--card-border);
            border-radius: 20px; padding: 30px; backdrop-filter: blur(10px);
        }

        .table-premium { color: #ffffff !important; }
        .table-premium th {
            background: rgba(255, 255, 255, 0.03) !important; color: #cbd5e1 !important;
            font-size: 12px; text-transform: uppercase; padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
        .table-premium td {
            padding: 18px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
            background: transparent !important; color: #f3f4f6 !important; vertical-align: middle;
        }

        .progress-premium {
            background: rgba(255, 255, 255, 0.05); height: 8px; border-radius: 10px; overflow: hidden;
        }
        .progress-bar-premium {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6); border-radius: 10px;
        }

        .btn-action {
            width: 35px; height: 35px; display: inline-flex; align-items: center;
            justify-content: center; border-radius: 10px; text-decoration: none;
        }

        .btn-gradient-add {
            background: linear-gradient(90deg, #6366f1, #a855f7); color: white;
            padding: 10px 24px; border-radius: 30px; font-size: 14px; text-decoration: none;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        .btn-gradient-add:hover { transform: translateY(-2px); color: white; }

        footer { border-top: 1px solid var(--card-border); padding: 25px 0; font-size: 13px; color: var(--text-muted); }
        
        /* Style untuk preview sertifikat */
        .cert-preview { width: 50px; height: 35px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: 0.3s; }
        .cert-preview:hover { transform: scale(1.1); border-color: var(--accent-blue); }
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
        <div class="collapse navbar-collapse justify-content-center">
            <div class="nav-menu">
                <a href="../dashboard.php">Beranda</a>
                <a href="../tentang/index.php">Tentang Saya</a>
                <a href="#" class="active">Skill</a>
                <a href="../pendidikan/index.php">Pendidikan</a>
                <a href="../sertifikat/index.php">Sertifikat</a>
                <a href="../hobi/index.php">Hobi</a>
                <a href="../pengalaman/index.php">Pengalaman Kerja</a>
                <a href="../kontak/index.php">Kontak</a>
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
            <div class="text-primary fw-bold small mb-1"><i class="fa-solid fa-screwdriver-wrench me-2"></i>MODUL KEAHLIAN</div>
            <h1 class="fs-2 fw-bold m-0 text-white">MY <span class="gradient-text">SKILLS</span></h1>
        </div>
        <div>
            <a href="tambah.php" class="btn-gradient-add">
                <i class="fa-solid fa-plus me-2"></i>Tambah Skill Baru
            </a>
        </div>
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-premium text-nowrap">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Keahlian</th>
                        <th width="15%">Sertifikat Validasi</th> <th width="15%">Tingkat Kemahiran</th>
                        <th width="30%">Penguasaan (Persentase)</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($query) > 0):
                        while($data = mysqli_fetch_assoc($query)): 
                            // Perbaikan di sini: Langsung gunakan 'id'
                            $id_data = $data['id'] ?? $data['id_skill'] ?? $data['skill_id'] ?? 0;
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <div class="fw-bold text-white"><i class="fa-solid fa-layer-group text-info me-2"></i><?= htmlspecialchars($data['nama_skill'] ?? '') ?></div>
                            </td>
                            <td>
                                <?php if(!empty($data['gambar'])): ?>
                                    <a href="uploads_skill/<?= htmlspecialchars($data['gambar']) ?>" target="_blank">
                                        <img src="uploads_skill/<?= htmlspecialchars($data['gambar']) ?>" class="cert-preview" alt="Sertifikat">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Belum ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-30 px-3 py-2 rounded-pill">
                                    <?= htmlspecialchars($data['tingkat'] ?? 'Intermediate') ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress progress-premium flex-grow-1">
                                        <div class="progress-bar progress-bar-premium" style="width: <?= (int)$data['persentase'] ?>%"></div>
                                    </div>
                                    <span class="fw-bold text-white fs-6"><?= (int)$data['persentase'] ?>%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php 
                                echo '<a href="edit.php?id=' . $id_data . '" class="btn-action bg-primary bg-opacity-20 text-primary border border-primary border-opacity-20 me-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                                echo '<a href="proses.php?aksi=hapus&id=' . $id_data . '" class="btn-action bg-danger bg-opacity-20 text-danger border border-danger border-opacity-20" title="Hapus" onclick="return confirm(\'Apakah Anda yakin ingin menghapus skill ini?\')"><i class="fa-solid fa-trash-can"></i></a>';
                                ?>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-boxes-stacked fs-2 d-block mb-3 text-secondary"></i>
                                Belum ada data keahlian yang dimasukkan.
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

</body>
</html>