<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/session.php';
include '../../config/koneksi.php';

// Deteksi struktur database
$columns_check = mysqli_query($conn, "SHOW COLUMNS FROM sertifikat");
$id_column_key = 'id';
$existing_columns = [];
while ($col = mysqli_fetch_assoc($columns_check)) {
    $existing_columns[] = $col['Field'];
    if ($col['Key'] == 'PRI') {
        $id_column_key = $col['Field'];
    }
}

// Tangkap ID Target yang dikirim dari index.php
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id_target = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data lama berdasarkan ID
$query_old = mysqli_query($conn, "SELECT * FROM sertifikat WHERE $id_column_key = '$id_target'");
$data = mysqli_fetch_assoc($query_old);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

// Pengondisian variabel agar tidak error di form value
$old_nama    = $data['nama_sertifikat'] ?? $data['nama'] ?? '';
$old_gambar  = $data['gambar']          ?? $data['file_gambar'] ?? $data['foto'] ?? '';
$old_kategori= $data['kategori']        ?? 'Teknologi';
$old_penerbit= $data['penerbit']        ?? $data['mitra'] ?? '';
$old_tanggal = $data['tanggal']         ?? $data['tgl'] ?? $data['tanggal_diperoleh'] ?? '';
$old_id_cert = $data['id_sertifikat']   ?? $data['no_sertifikat'] ?? $data['nomor'] ?? '';
$old_desc    = $data['deskripsi']       ?? $data['ket'] ?? '';
$old_link    = $data['link_verifikasi']  ?? $data['link'] ?? '';

// ==========================================
// PROSES LOGIKA UPDATE DATA
// ==========================================
if (isset($_POST['btn_update'])) {
    $nama_sertifikat = mysqli_real_escape_string($conn, $_POST['nama_sertifikat']);
    $kategori        = mysqli_real_escape_string($conn, $_POST['kategori']);
    $penerbit        = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tanggal         = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $id_sertifikat   = mysqli_real_escape_string($conn, $_POST['id_sertifikat']);
    $deskripsi       = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $link_verifikasi = mysqli_real_escape_string($conn, $_POST['link_verifikasi']);
    
    if (trim($id_sertifikat) === '') {
        $id_sertifikat = "";
    }

    // Jika ganti gambar baru
    $gambar_query = "";
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != "") {
        $filename = $_FILES['gambar']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $gambar_baru = "cert_" . time() . "." . $ext;
        
        $target_dir = "../../assets/images/sertifikat/";
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $gambar_baru)) {
            // Hapus gambar lama agar tidak menumpuk di hosting
            if ($old_gambar != "" && file_exists($target_dir . $old_gambar)) {
                unlink($target_dir . $old_gambar);
            }
            
            if (in_array('gambar', $existing_columns)) $gambar_query = ", gambar='$gambar_baru'";
            elseif (in_array('file_gambar', $existing_columns)) $gambar_query = ", file_gambar='$gambar_baru'";
            elseif (in_array('foto', $existing_columns)) $gambar_query = ", foto='$gambar_baru'";
        }
    }

    $update_parts = [];
    if (in_array('nama_sertifikat', $existing_columns)) $update_parts[] = "nama_sertifikat='$nama_sertifikat'";
    elseif (in_array('nama', $existing_columns)) $update_parts[] = "nama='$nama_sertifikat'";
    
    if (in_array('kategori', $existing_columns)) $update_parts[] = "kategori='$kategori'";
    
    if (in_array('penerbit', $existing_columns)) $update_parts[] = "penerbit='$penerbit'";
    elseif (in_array('mitra', $existing_columns)) $update_parts[] = "mitra='$penerbit'";

    if (in_array('tanggal', $existing_columns)) $update_parts[] = "tanggal='$tanggal'";
    elseif (in_array('tgl', $existing_columns)) $update_parts[] = "tgl='$tanggal'";
    elseif (in_array('tanggal_diperoleh', $existing_columns)) $update_parts[] = "tanggal_diperoleh='$tanggal'";

    if (in_array('id_sertifikat', $existing_columns)) $update_parts[] = "id_sertifikat='$id_sertifikat'";
    elseif (in_array('no_sertifikat', $existing_columns)) $update_parts[] = "no_sertifikat='$id_sertifikat'";
    elseif (in_array('nomor', $existing_columns)) $update_parts[] = "nomor='$id_sertifikat'";

    if (in_array('deskripsi', $existing_columns)) $update_parts[] = "deskripsi='$deskripsi'";
    elseif (in_array('ket', $existing_columns)) $update_parts[] = "ket='$deskripsi'";

    if (in_array('link_verifikasi', $existing_columns)) $update_parts[] = "link_verifikasi='$link_verifikasi'";
    elseif (in_array('link', $existing_columns)) $update_parts[] = "link='$link_verifikasi'";

    $sql_update = "UPDATE sertifikat SET " . implode(',', $update_parts) . " $gambar_query WHERE $id_column_key = '$id_target'";
    
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Sertifikat berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Sertifikat - Workspace Control</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-dark: #030712;
            --bg-radial: radial-gradient(circle at center top, #0b1528 0%, #030712 100%);
            --card-bg: rgba(14, 20, 38, 0.6);
            --card-border: rgba(255, 255, 255, 0.05);
        }
        body { background: var(--bg-dark); background: var(--bg-radial); color: white; font-family: 'Segoe UI', sans-serif; padding-top: 50px; padding-bottom: 100px; }
        .edit-box { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .form-control, .form-select { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; font-size: 13px; }
        .form-control:focus, .form-select:focus { background: rgba(255, 255, 255, 0.05); color: white; border-color: #6366f1; box-shadow: none; }
        .btn-purple { background: #6366f1; color: white; font-size: 13px; font-weight: 600; border: none; padding: 10px 24px; border-radius: 8px; }
        .btn-purple:hover { background: #4f46e5; color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="mb-3">
                <a href="index.php" class="text-muted text-decoration-none" style="font-size:13px;"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke List</a>
            </div>
            <div class="edit-box">
                <h4 class="fw-bold mb-4"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Sertifikat</h4>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label text-white-50">Nama / Judul Sertifikat</label>
                        <input type="text" class="form-control" name="nama_sertifikat" value="<?= htmlspecialchars($old_nama) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white-50">Kategori Filter</label>
                            <select class="form-select" name="kategori" required>
                                <option value="Teknologi" <?= $old_kategori == 'Teknologi' ? 'selected' : '' ?>>Teknologi</option>
                                <option value="Pengembangan Diri" <?= $old_kategori == 'Pengembangan Diri' ? 'selected' : '' ?>>Pengembangan Diri</option>
                                <option value="Desain" <?= $old_kategori == 'Desain' ? 'selected' : '' ?>>Desain</option>
                                <option value="Lainnya" <?= $old_kategori == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white-50">Penerbit / Mitra</label>
                            <input type="text" class="form-control" name="penerbit" value="<?= htmlspecialchars($old_penerbit) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white-50">Tanggal Diperoleh</label>
                            <input type="date" class="form-control" name="tanggal" value="<?= $old_tanggal ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white-50">ID / Kredensial Sertifikat</label>
                            <input type="text" class="form-control" name="id_sertifikat" value="<?= htmlspecialchars($old_id_cert) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white-50">Ganti Gambar (.png, .jpg, .jpeg)</label>
                        <input type="file" class="form-control" name="gambar" accept="image/*">
                        <div class="form-text text-muted" style="font-size:11px;">Biarkan kosong jika tidak ingin mengubah berkas sertifikat lama.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white-50">Link Verifikasi (URL)</label>
                        <input type="url" class="form-control" name="link_verifikasi" value="<?= htmlspecialchars($old_link) ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-white-50">Deskripsi singkat</label>
                        <textarea class="form-control" name="deskripsi" rows="3"><?= htmlspecialchars($old_desc) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-outline-secondary px-4 text-white" style="font-size:13px;">Batal</a>
                        <button type="submit" name="btn_update" class="btn btn-purple px-4">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>