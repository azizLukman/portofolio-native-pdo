<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Naik 2 tingkat ke root folder portofolio untuk menemukan folder config
include '../../config/session.php';
include '../../config/koneksi.php';

// ==========================================
// PROSES LOGIKA UNTUK MENAMBAH DATA (INSERT)
// ==========================================
if (isset($_POST['btn_simpan'])) {
    // Tangkap data dari form input
    $nama_sertifikat = mysqli_real_escape_string($conn, $_POST['nama_sertifikat']);
    $kategori        = mysqli_real_escape_string($conn, $_POST['kategori']);
    $penerbit        = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tanggal         = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $id_sertifikat   = mysqli_real_escape_string($conn, $_POST['id_sertifikat']);
    $deskripsi       = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $link_verifikasi = mysqli_real_escape_string($conn, $_POST['link_verifikasi']);
    
    // Perbaikan Bug: Jika dikosongkan, ubah menjadi string kosong yang aman bagi MySQL Strict
    if (trim($id_sertifikat) === '') {
        $id_sertifikat = "";
    }

    // Proses Upload File Gambar
    $gambar = "";
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != "") {
        $filename = $_FILES['gambar']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        // Rename file secara unik agar tidak bentrok
        $gambar = "cert_" . time() . "." . $ext;
        
        $target_dir = "../../assets/images/sertifikat/";
        // Buat folder otomatis jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $gambar);
    }

    // Deteksi otomatis kolom yang ada di database agar tidak memicu query error
    $columns_check = mysqli_query($conn, "SHOW COLUMNS FROM sertifikat");
    $existing_columns = [];
    while ($col = mysqli_fetch_assoc($columns_check)) {
        $existing_columns[] = $col['Field'];
    }

    // Pemetaan data dinamis menyesuaikan struktur tabel user
    $insert_fields = [];
    $insert_values = [];

    // Cek Kolom Nama
    if (in_array('nama_sertifikat', $existing_columns)) { $insert_fields[] = 'nama_sertifikat'; $insert_values[] = "'$nama_sertifikat'"; }
    elseif (in_array('nama', $existing_columns)) { $insert_fields[] = 'nama'; $insert_values[] = "'$nama_sertifikat'"; }

    // Cek Kolom Gambar
    if ($gambar != "") {
        if (in_array('gambar', $existing_columns)) { $insert_fields[] = 'gambar'; $insert_values[] = "'$gambar'"; }
        elseif (in_array('file_gambar', $existing_columns)) { $insert_fields[] = 'file_gambar'; $insert_values[] = "'$gambar'"; }
        elseif (in_array('foto', $existing_columns)) { $insert_fields[] = 'foto'; $insert_values[] = "'$gambar'"; }
    }

    // Cek Kolom Kategori
    if (in_array('kategori', $existing_columns)) { $insert_fields[] = 'kategori'; $insert_values[] = "'$kategori'"; }
    
    // Cek Kolom Penerbit
    if (in_array('penerbit', $existing_columns)) { $insert_fields[] = 'penerbit'; $insert_values[] = "'$penerbit'"; }
    elseif (in_array('mitra', $existing_columns)) { $insert_fields[] = 'mitra'; $insert_values[] = "'$penerbit'"; }

    // Cek Kolom Tanggal
    if (in_array('tanggal', $existing_columns)) { $insert_fields[] = 'tanggal'; $insert_values[] = "'$tanggal'"; }
    elseif (in_array('tgl', $existing_columns)) { $insert_fields[] = 'tgl'; $insert_values[] = "'$tanggal'"; }
    elseif (in_array('tanggal_diperoleh', $existing_columns)) { $insert_fields[] = 'tanggal_diperoleh'; $insert_values[] = "'$tanggal'"; }

    // Cek Kolom ID/No Sertifikat
    if (in_array('id_sertifikat', $existing_columns)) { $insert_fields[] = 'id_sertifikat'; $insert_values[] = "'$id_sertifikat'"; }
    elseif (in_array('no_sertifikat', $existing_columns)) { $insert_fields[] = 'no_sertifikat'; $insert_values[] = "'$id_sertifikat'"; }
    elseif (in_array('nomor', $existing_columns)) { $insert_fields[] = 'nomor'; $insert_values[] = "'$id_sertifikat'"; }

    // Cek Kolom Deskripsi
    if (in_array('deskripsi', $existing_columns)) { $insert_fields[] = 'deskripsi'; $insert_values[] = "'$deskripsi'"; }
    elseif (in_array('ket', $existing_columns)) { $insert_fields[] = 'ket'; $insert_values[] = "'$deskripsi'"; }

    // Cek Kolom Link Verifikasi
    if (in_array('link_verifikasi', $existing_columns)) { $insert_fields[] = 'link_verifikasi'; $insert_values[] = "'$link_verifikasi'"; }
    elseif (in_array('link', $existing_columns)) { $insert_fields[] = 'link'; $insert_values[] = "'$link_verifikasi'"; }

    // Gabungkan menjadi query utuh
    $sql_insert = "INSERT INTO sertifikat (" . implode(',', $insert_fields) . ") VALUES (" . implode(',', $insert_values) . ")";
    
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('Sertifikat berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah data: " . mysqli_error($conn) . "');</script>";
    }
}

// Ambil total sertifikat untuk badge atas
$total_sertifikat = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM sertifikat"));

// Ambil data sertifikat untuk ditampilkan pada grid
$query = "SELECT * FROM sertifikat";
$result = mysqli_query($conn, $query);

$sertifikat_list = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Ambil ID Primary Key asli dari baris database
        $id_asli = $row['id'] ?? $row['id_sertifikat'] ?? null;

        $row['nama_sertifikat'] = $row['nama_sertifikat'] ?? $row['nama'] ?? 'Sertifikat Tanpa Nama';
        $row['gambar']          = $row['gambar']          ?? $row['file_gambar'] ?? $row['foto'] ?? '';
        $row['kategori']        = $row['kategori']        ?? 'Teknologi'; 
        $row['penerbit']        = $row['penerbit']        ?? $row['mitra'] ?? 'Dicoding Indonesia';
        $row['deskripsi']       = $row['deskripsi']       ?? $row['ket'] ?? 'Tidak ada deskripsi.';
        $row['tanggal_dinamis'] = $row['tanggal'] ?? $row['tgl'] ?? $row['tanggal_diperoleh'] ?? $row['created_at'] ?? '';
        $row['id_sertifikat_dinamis'] = $row['id_sertifikat'] ?? $row['no_sertifikat'] ?? $row['nomor'] ?? '-';
        $row['id_dinamis'] = $id_asli ?? count($sertifikat_list);
        
        $sertifikat_list[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat - Workspace Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --bg-dark: #030712;
            --bg-radial: radial-gradient(circle at center top, #0b1528 0%, #030712 100%);
            --card-bg: rgba(14, 20, 38, 0.4);
            --card-border: rgba(255, 255, 255, 0.05);
            --text-muted: #8892b0;
        }

        body {
            background: var(--bg-dark);
            background: var(--bg-radial);
            color: white;
            font-family: 'Segoe UI', sans-serif;
            padding-bottom: 120px;
            overflow-x: hidden;
        }

        .navbar {
            padding: 15px 30px;
            background: rgba(3, 7, 18, 0.6);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-left { display: flex; align-items: center; gap: 10px; }
        .nav-logo { font-size: 22px; font-weight: 900; color: #3b82f6; }
        .nav-title-wrapper { display: flex; flex-direction: column; }
        .nav-title-wrapper strong { font-size: 14px; letter-spacing: 0.5px; }
        .nav-title-wrapper span { font-size: 11px; color: #888; }
        .nav-menu a { color: #bbb; margin: 0 12px; text-decoration: none; font-size: 13px; transition: color .2s; }
        .nav-menu a:hover, .nav-menu a.active { color: white; position: relative; }
        .nav-menu a.active::after { content: ''; position: absolute; bottom: -6px; left: 0; width: 100%; height: 2px; background: #6366f1; }

        .btn-cv { border: 1px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.03); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; text-decoration: none; }
        .btn-cv:hover { background: rgba(255, 255, 255, 0.1); color: white; }

        .breadcrumb-text { font-size: 12px; color: var(--text-muted); margin-top: 30px; margin-bottom: 10px; }
        .breadcrumb-text a { color: var(--text-muted); text-decoration: none; }

        .header-title { font-size: 36px; font-weight: 700; margin-bottom: 8px; }
        .header-desc { font-size: 13px; color: var(--text-muted); max-width: 500px; line-height: 1.6; }

        .total-badge-box { background: rgba(14, 20, 38, 0.6); border: 1px solid var(--card-border); border-radius: 16px; padding: 12px 20px; display: flex; align-items: center; gap: 15px; }
        .total-icon { width: 40px; height: 40px; background: rgba(99, 102, 241, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6366f1; font-size: 18px; }
        .total-info span { font-size: 11px; color: var(--text-muted); display: block; }
        .total-info strong { font-size: 20px; font-weight: 700; }

        .filter-btn { background: transparent; border: none; color: #aaa; padding: 6px 16px; font-size: 13px; border-radius: 20px; transition: all 0.2s; }
        .filter-btn.active { background: #6366f1; color: white; }
        .search-input, .sort-select { background: rgba(14, 20, 38, 0.6); border: 1px solid var(--card-border); color: white; font-size: 12px; padding: 8px 15px; border-radius: 8px; }

        .cert-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 15px; height: 100%; cursor: pointer; transition: all 0.2s ease; position: relative; }
        .cert-card:hover, .cert-card.active { border-color: #6366f1; box-shadow: 0 0 15px rgba(99, 102, 241, 0.2); }
        .cert-img-wrapper { background: #111; border-radius: 8px; overflow: hidden; aspect-ratio: 16/10; margin-bottom: 15px; }
        .cert-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .cert-title { font-size: 13px; font-weight: 600; line-height: 1.4; margin-bottom: 8px; min-height: 36px; }
        
        .badge-tech { font-size: 10px; background: rgba(99, 102, 241, 0.15); color: #818cf8; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 10px; }
        .badge-desain { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .badge-pengembangan { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        
        .cert-date { font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
        .cert-options { position: absolute; bottom: 15px; right: 15px; color: var(--text-muted); font-size: 12px; }

        .detail-panel { background: rgba(10, 16, 32, 0.5); border: 1px solid var(--card-border); border-radius: 16px; padding: 25px; position: sticky; top: 90px; }
        .detail-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 14px; font-weight: 600; }
        .detail-preview-img { width: 100%; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--card-border); }
        .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.03); font-size: 12px; }
        .detail-label { width: 130px; color: var(--text-muted); display: flex; align-items: center; gap: 8px; }
        .detail-value { flex: 1; color: white; }
        
        .btn-purple { background: #6366f1; color: white; font-size: 12px; font-weight: 600; border: none; padding: 8px 16px; border-radius: 8px; }
        .btn-purple:hover { background: #4f46e5; color: white; }
        .btn-outline-custom { border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); color: white; font-size: 12px; padding: 8px 16px; border-radius: 8px; }

        .page-dot { width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 11px; color: var(--text-muted); text-decoration: none; margin: 0 4px; }
        .page-dot.active { background: #6366f1; color: white; }

        .social-box { position: fixed; bottom: 15px; left: 15px; right: 15px; display: flex; justify-content: space-between; align-items: center; padding: 12px 25px; border-radius: 12px; background: rgba(6, 11, 25, 0.8); backdrop-filter: blur(20px); border: 1px solid var(--card-border); z-index: 999; }
        .social-left a { color: var(--text-muted); margin-right: 10px; text-decoration: none; }
        
        /* STYLE UNTUK MODAL FORM */
        .modal-content { background: #0b1329; border: 1px solid rgba(255,255,255,0.1); color: white; }
        .modal-header { border-bottom: 1px solid rgba(255,255,255,0.05); }
        .modal-footer { border-top: 1px solid rgba(255,255,255,0.05); }
        .form-control, .form-select { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; font-size: 13px; }
        .form-control:focus, .form-select:focus { background: rgba(255,255,255,0.05); color: white; border-color: #6366f1; box-shadow: none; }
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
        <div class="collapse navbar-collapse justify-content-center">
            <div class="nav-menu">
                <a href="../dashboard.php">Beranda</a>
                <a href="../tentang/index.php">Tentang Saya</a>
                <a href="../skill/index.php">Skill</a>
                <a href="../pendidikan/index.php">Pendidikan</a>
                <a href="#" class="active">Sertifikat</a>
                <a href="../hobi/index.php">Hobi</a>
                <a href="../pengalaman/index.php">Pengalaman Kerja</a>
                <a href="../kontak/index.php">Kontak</a>
            </div>
        </div>
        <div>
            <a href="../dashboard.php" class="btn-cv"><i class="fa-solid fa-download me-1"></i> Download CV</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-5">
    
    <div class="breadcrumb-text">
        <a href="../dashboard.php">Beranda</a> &nbsp;&gt;&nbsp; <span>Sertifikat</span>
    </div>

    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="header-title">Sertifikat</h1>
            <div class="header-desc">Daftar sertifikat dan penghargaan yang telah saya peroleh untuk menunjang kemampuan dan pengalaman saya.</div>
            <button class="btn btn-purple mt-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa-solid fa-plus me-1"></i> Tambah Sertifikat Baru
            </button>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end mt-3 mt-md-0">
            <div class="total-badge-box">
                <div class="total-icon"><i class="fa-solid fa-briefcase"></i></div>
                <div class="total-info">
                    <span>Total Sertifikat</span>
                    <strong><?= $total_sertifikat ?>+</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="d-flex gap-2">
            <button class="filter-btn active" onclick="filterKategori('Semua')">Semua</button>
            <button class="filter-btn" onclick="filterKategori('Teknologi')">Teknologi</button>
            <button class="filter-btn" onclick="filterKategori('Pengembangan Diri')">Pengembangan Diri</button>
            <button class="filter-btn" onclick="filterKategori('Desain')">Desain</button>
            <button class="filter-btn" onclick="filterKategori('Lainnya')">Lainnya</button>
        </div>
        <div class="d-flex gap-2">
            <input type="text" class="search-input" id="searchBox" placeholder="Cari sertifikat..." onkeyup="cariSertifikat()">
            <select class="sort-select">
                <option>Terbaru</option>
                <option>Terlama</option>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-4 g-3" id="certGrid">
                </div>
            <div class="d-flex align-items-center mt-4 gap-2">
                <a href="#" class="page-dot"><i class="fa-solid fa-chevron-left" style="font-size: 9px;"></i></a>
                <a href="#" class="page-dot active">1</a>
                <a href="#" class="page-dot"><i class="fa-solid fa-chevron-right" style="font-size: 9px;"></i></a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="detail-panel">
                <div class="detail-header">
                    <span>Detail Sertifikat</span>
                    <i class="fa-solid fa-xmark text-muted" style="font-size: 14px;"></i>
                </div>
                
                <img src="" id="detImg" class="detail-preview-img" alt="Certificate Presentation Mockup">
                
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-warning btn-sm flex-fill fw-bold text-dark" id="btnAksiEdit" onclick="redirectKeEdit()" style="font-size:12px; border-radius:8px;">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Data
                    </button>
                    <button class="btn btn-danger btn-sm flex-fill fw-bold" id="btnAksiHapus" onclick="konfirmasiHapus()" style="font-size:12px; border-radius:8px;">
                        <i class="fa-solid fa-trash me-1"></i> Hapus
                    </button>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button class="btn-outline-custom flex-fill"><i class="fa-solid fa-magnifying-glass-plus me-1"></i> Perbesar</button>
                    <a href="" id="detDownload" class="btn-purple flex-fill text-center text-decoration-none" download>
                        <i class="fa-solid fa-download me-1"></i> Unduh Berkas
                    </a>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="total-icon" style="width:32px; height:32px; font-size:14px;"><i class="fa-solid fa-briefcase"></i></div>
                    <h6 class="m-0 font-weight-bold" id="detTitle" style="font-size: 14px;">Nama Sertifikat</h6>
                    <span class="badge-tech ms-auto" id="detKategori">Kategori</span>
                </div>

                <div class="detail-row">
                    <div class="detail-label"><i class="fa-regular fa-building"></i> Penerbit</div>
                    <div class="detail-value" id="detPenerbit">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fa-regular fa-calendar"></i> Tanggal Diperoleh</div>
                    <div class="detail-value" id="detTanggal">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fa-solid fa-circle-info"></i> Jenis</div>
                    <div class="detail-value" id="detJenis">Sertifikat Penyelesaian</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label"><i class="fa-regular fa-id-card"></i> ID Sertifikat</div>
                    <div class="detail-value" id="detId">-</div>
                </div>
                <div class="detail-row" style="border:none;">
                    <div class="detail-label align-items-start pt-1"><i class="fa-regular fa-file-lines"></i> Deskripsi</div>
                    <div class="detail-value" id="detDesc" style="line-height: 1.5; color:#aaa;">-</div>
                </div>
                <div class="detail-row mt-2" style="border:none;">
                    <div class="detail-label"><i class="fa-solid fa-link"></i> Link Verifikasi</div>
                    <div class="detail-value">
                        <a href="#" id="detLink" target="_blank" class="text-info text-decoration-none">Verifikasi Dokumen <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 10px;"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-file-signature me-2 text-primary"></i>Tambah Sertifikat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nama / Judul Sertifikat</label>
                    <input type="text" class="form-control" name="nama_sertifikat" placeholder="Contoh: Belajar Dasar Pemrograman Web" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori Filter</label>
                        <select class="form-select" name="kategori" required>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Pengembangan Diri">Pengembangan Diri</option>
                            <option value="Desain">Desain</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penerbit / Mitra</label>
                        <input type="text" class="form-control" name="penerbit" placeholder="Contoh: Dicoding Indonesia" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Diperoleh</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ID / Kredensial Sertifikat</label>
                        <input type="text" class="form-control" name="id_sertifikat" placeholder="Contoh: DIC-2024-WDB-12345">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Gambar Sertifikat (.png, .jpg, .jpeg)</label>
                    <input type="file" class="form-control" name="gambar" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link Verifikasi (URL)</label>
                    <input type="url" class="form-control" name="link_verifikasi" placeholder="https://dicoding.com/certificates/...">
                </div>
                <div class="mb-1">
                    <label class="form-label">Deskripsi singkat</label>
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Tuliskan kompetensi atau materi pokok yang dikuasai..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-custom text-white" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="btn_simpan" class="btn btn-purple px-4">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div class="social-box">
    <div class="social-left">
        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
        <a href="#"><i class="fa-brands fa-github"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
    </div>
    <div class="social-center">&ldquo;Terus belajar, terus berkarya.&rdquo;</div>
    <div class="social-right">&copy; 2026 Aziz Lukmanudin. All rights reserved.</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const dataSertifikat = <?= json_encode($sertifikat_list) ?>;
let kategoriAktif = "Semua";
let selectedId = null; // Menyimpan ID sertifikat yang sedang aktif diklik

function renderGrid(data) {
    const grid = document.getElementById('certGrid');
    grid.innerHTML = "";
    
    if(data.length === 0) {
        grid.innerHTML = `<div class="col-12 text-muted text-center py-5">Tidak ada sertifikat ditemukan.</div>`;
        return;
    }

    data.forEach((item, index) => {
        let badgeClass = "badge-tech";
        if(item.kategori === 'Desain') badgeClass += " badge-desain";
        if(item.kategori === 'Pengembangan Diri') badgeClass += " badge-pengembangan";

        let imgPath = item.gambar ? `../../assets/images/sertifikat/${item.gambar}` : '../../assets/images/sertifikat/default.jpg';
        
        const targetID = item.id_dinamis;
        const tanggalData = item.tanggal_dinamis;
        const judulSertifikat = item.nama_sertifikat;

        const card = document.createElement('div');
        card.className = "col";
        card.innerHTML = `
            <div class="cert-card" onclick="showDetail('${targetID}', this)">
                <div class="cert-img-wrapper">
                    <img src="${imgPath}" alt="${judulSertifikat}">
                </div>
                <div class="cert-title">${judulSertifikat}</div>
                <div class="badge-tech ${badgeClass}">${item.kategori}</div>
                <div class="cert-date"><i class="fa-regular fa-calendar"></i> ${formatTanggal(tanggalData)}</div>
                <div class="cert-options"><i class="fa-solid fa-ellipsis"></i></div>
            </div>
        `;
        grid.appendChild(card);
        
        if(index === 0) {
            showDetail(targetID, card.querySelector('.cert-card'));
        }
    });
}

function showDetail(id, element) {
    document.querySelectorAll('.cert-card').forEach(c => c.classList.remove('active'));
    if(element) element.classList.add('active');

    selectedId = id; // Update ID yang sedang dipilih secara real-time
    const item = dataSertifikat.find((s) => s.id_dinamis == id);
    if(!item) return;

    let imgPath = item.gambar ? `../../assets/images/sertifikat/${item.gambar}` : '../../assets/images/sertifikat/default.jpg';

    document.getElementById('detImg').src = imgPath;
    document.getElementById('detDownload').href = imgPath;
    document.getElementById('detTitle').innerText = item.nama_sertifikat;
    document.getElementById('detKategori').innerText = item.kategori;
    document.getElementById('detPenerbit').innerText = item.penerbit;
    document.getElementById('detTanggal').innerText = formatTanggal(item.tanggal_dinamis);
    document.getElementById('detId').innerText = item.id_sertifikat_dinamis;
    document.getElementById('detDesc').innerText = item.deskripsi;
    document.getElementById('detLink').href = item.link_verifikasi || item.link || '#';
}

// Fungsi tombol edit melempar ID ke halaman edit.php
function redirectKeEdit() {
    if(selectedId) {
        window.location.href = `edit.php?id=${selectedId}`;
    }
}

// Fungsi tombol hapus melempar ID ke halaman hapus.php
function konfirmasiHapus() {
    if(selectedId) {
        if(confirm("Apakah Anda yakin ingin menghapus data sertifikat ini permanen beserta file gambarnya?")) {
            window.location.href = `hapus.php?id=${selectedId}`;
        }
    }
}

function filterKategori(kat) {
    kategoriAktif = kat;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if(btn.innerText === kat) btn.classList.add('active');
    });
    eksekusiFilterDanCari();
}

function cariSertifikat() { eksekusiFilterDanCari(); }

function eksekusiFilterDanCari() {
    const keyword = document.getElementById('searchBox').value.toLowerCase();
    const hasil = dataSertifikat.filter(item => {
        const namaField = (item.nama_sertifikat || '').toLowerCase();
        const penerbitField = (item.penerbit || '').toLowerCase();
        
        const cocokKategori = (kategoriAktif === "Semua" || item.kategori === kategoriAktif);
        const cocokKeyword = namaField.includes(keyword) || penerbitField.includes(keyword);
        return cocokKategori && cocokKeyword;
    });
    renderGrid(hasil);
}

function formatTanggal(stringTanggal) {
    if(!stringTanggal || stringTanggal === '-') return '-';
    const opsi = { year: 'numeric', month: 'long', day: 'numeric' };
    const dateParsed = new Date(stringTanggal);
    return isNaN(dateParsed) ? stringTanggal : dateParsed.toLocaleDateString('id-ID', opsi);
}

window.onload = function() { renderGrid(dataSertifikat); };
</script>
</body>
</html>