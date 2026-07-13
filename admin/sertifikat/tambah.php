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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Sertifikat - Workspace Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap 5 & FontAwesome -->
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
        }

        .navbar {
            padding: 15px 30px;
            background: rgba(3, 7, 18, 0.6);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--card-border);
        }

        .nav-left { display: flex; align-items: center; gap: 10px; }
        .nav-logo { font-size: 22px; font-weight: 900; color: #3b82f6; }
        .nav-title-wrapper { display: flex; flex-direction: column; }
        .nav-title-wrapper strong { font-size: 14px; letter-spacing: 0.5px; }
        
        .breadcrumb-text { font-size: 12px; color: var(--text-muted); margin-top: 30px; margin-bottom: 20px; }
        .breadcrumb-text a { color: var(--text-muted); text-decoration: none; }

        .form-container {
            background: rgba(14, 20, 38, 0.6);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }

        .form-control, .form-select { 
            background: rgba(255, 255, 255, 0.03); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            color: white; 
            font-size: 14px;
            padding: 10px 15px;
        }
        .form-control:focus, .form-select:focus { 
            background: rgba(255, 255, 255, 0.05); 
            color: white; 
            border-color: #6366f1; 
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.2); 
        }

        .btn-purple { background: #6366f1; color: white; font-size: 14px; font-weight: 600; border: none; padding: 10px 24px; border-radius: 8px; }
        .btn-purple:hover { background: #4f46e5; color: white; }
        .btn-outline-custom { border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); color: white; font-size: 14px; padding: 10px 24px; border-radius: 8px; text-decoration: none; }
        .btn-outline-custom:hover { background: rgba(255,255,255,0.08); color: white; }
        
        .image-preview {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px dashed rgba(255,255,255,0.2);
            padding: 10px;
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container-fluid px-2">
        <a class="nav-left text-decoration-none" href="../dashboard.php">
            <div class="nav-logo">R</div>
            <div class="nav-title-wrapper text-white">
                <strong>AZIZ LUKMANUDIN</strong>
                <span>Workspace Control</span>
            </div>
        </a>
    </div>
</nav>

<div class="container" style="max-width: 800px;">
    <!-- Breadcrumb -->
    <div class="breadcrumb-text">
        <a href="../dashboard.php">Beranda</a> &nbsp;&gt;&nbsp; <a href="index.php">Sertifikat</a> &nbsp;&gt;&nbsp; <span>Tambah</span>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold m-0">Tambah Sertifikat Baru</h2>
            <p class="text-muted small m-0 mt-1">Masukkan berkas dan data kredensial sertifikat Anda secara valid.</p>
        </div>
        <a href="index.php" class="btn-outline-custom py-2 px-3 fs-6"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
    </div>

    <!-- FORM INPUT -->
    <form action="" method="POST" enctype="multipart/form-data" class="form-container">
        <div class="mb-3">
            <label class="form-label text-white-50">Nama / Judul Sertifikat <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_sertifikat" placeholder="Contoh: Belajar Dasar Pemrograman Web" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-white-50">Kategori Filter <span class="text-danger">*</span></label>
                <select class="form-select" name="kategori" required>
                    <option value="Teknologi">Teknologi</option>
                    <option value="Pengembangan Diri">Pengembangan Diri</option>
                    <option value="Desain">Desain</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-white-50">Penerbit / Mitra <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="penerbit" placeholder="Contoh: Dicoding Indonesia" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-white-50">Tanggal Diperoleh <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="tanggal" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label text-white-50">ID / Kredensial Sertifikat</label>
                <input type="text" class="form-control" name="id_sertifikat" placeholder="Contoh: DIC-2024-WDB-12345">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label text-white-50">Upload Gambar Sertifikat (.png, .jpg, .jpeg) <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="gambar" id="inputGambar" accept="image/*" required>
            <img id="preview" class="image-preview" alt="Pratinjau Gambar">
        </div>

        <div class="mb-3">
            <label class="form-label text-white-50">Link Verifikasi (URL Kredensial Resmi)</label>
            <input type="url" class="form-control" name="link_verifikasi" placeholder="https://dicoding.com/certificates/...">
        </div>

        <div class="mb-4">
            <label class="form-label text-white-50">Deskripsi Singkat</label>
            <textarea class="form-control" name="deskripsi" rows="4" placeholder="Tuliskan materi pokok atau pencapaian yang Anda raih dari kompetensi ini..."></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn-outline-custom">Reset Form</button>
            <button type="submit" name="btn_simpan" class="btn btn-purple"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Data</button>
        </div>
    </form>
</div>

<script>
    // Preview gambar sebelum diupload
    const inputGambar = document.getElementById('inputGambar');
    const preview = document.getElementById('preview');

    inputGambar.onchange = evt => {
        const [file] = inputGambar.files;
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }
</script>
</body>
</html>