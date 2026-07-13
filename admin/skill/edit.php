<?php
include '../../config/session.php';
include '../../config/koneksi.php';

// Deteksi Primary Key otomatis
$pk_query = mysqli_query($conn, "SHOW KEYS FROM skill WHERE Key_name = 'PRIMARY'");
$pk_row = mysqli_fetch_assoc($pk_query);
$pk = $pk_row['Column_name'];

// Cek apakah ID ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID tidak ditemukan. Kembali ke <a href='index.php'>Index</a>");
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM skill WHERE $pk = '$id'");
$data = mysqli_fetch_assoc($query);

// Cek apakah data ditemukan
if (!$data) {
    die("Error: Data tidak ditemukan di database. Pastikan ID benar.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Skill - <?= htmlspecialchars($data['nama_skill']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background: #030712; color: #fff; padding-top: 50px; font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(17, 24, 39, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); }
        .gradient-text { background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; }
        .form-label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; font-weight: 600; }
        .form-control, .form-select { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; padding: 12px; border-radius: 12px; }
        .btn-update { background: linear-gradient(90deg, #3b82f6, #8b5cf6); color: white; border: none; padding: 12px; font-weight: 600; border-radius: 30px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="glass-card">
                <h3 class="mb-4">Edit <span class="gradient-text"><?= htmlspecialchars($data['nama_skill']) ?></span></h3>
                
                <form action="proses.php?aksi=edit" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $data[$pk] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Skill</label>
                        <input type="text" name="nama_skill" class="form-control" value="<?= htmlspecialchars($data['nama_skill']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" class="form-select">
                            <option value="Beginner" <?= ($data['tingkat']=='Beginner'?'selected':'') ?>>Beginner</option>
                            <option value="Intermediate" <?= ($data['tingkat']=='Intermediate'?'selected':'') ?>>Intermediate</option>
                            <option value="Advanced" <?= ($data['tingkat']=='Advanced'?'selected':'') ?>>Advanced</option>
                            <option value="Expert" <?= ($data['tingkat']=='Expert'?'selected':'') ?>>Expert</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Persentase (%)</label>
                        <input type="number" name="persentase" class="form-control" value="<?= (int)$data['persentase'] ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Sertifikat Saat Ini</label>
                        <div class="p-3 mb-2 rounded bg-dark border border-secondary">
                            <?php if(!empty($data['gambar'])): ?>
                                <img src="uploads_skill/<?= htmlspecialchars($data['gambar']) ?>" style="max-height:100px; border-radius:8px;">
                                <p class="text-muted mt-2 small"><?= htmlspecialchars($data['gambar']) ?></p>
                            <?php else: ?>
                                <p class="text-muted mb-0 small">Belum ada gambar.</p>
                            <?php endif; ?>
                        </div>
                        <label class="form-label mt-2">Ganti Sertifikat (Opsional)</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-update w-100">Update Perubahan</button>
                    <a href="index.php" class="btn btn-outline-secondary w-100 mt-2 rounded-pill border-0 text-muted">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>