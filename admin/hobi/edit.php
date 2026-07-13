<?php
include '../../config/session.php';
include '../../config/koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM hobi WHERE id_hobi = '$id'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hobi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { background: #030712; color: #fff; padding-top: 50px; font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(17, 24, 39, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); }
        .gradient-text { background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; }
        .form-label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; font-weight: 600; }
        .form-control { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; padding: 12px; border-radius: 12px; }
        .form-control:focus { background: rgba(255, 255, 255, 0.05); color: #fff; border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
        .btn-submit { background: linear-gradient(90deg, #3b82f6, #8b5cf6); border: none; color: white; padding: 12px; font-weight: 600; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="glass-card">
                <h3 class="mb-4">Edit <span class="gradient-text">Hobi</span></h3>
                <form action="proses.php?aksi=edit" method="POST">
                    <input type="hidden" name="id_hobi" value="<?= $data['id_hobi'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Hobi</label>
                        <input type="text" name="nama_hobi" class="form-control" value="<?= htmlspecialchars($data['nama_hobi']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Ikon</label>
                        <input type="text" name="ikon" class="form-control" value="<?= htmlspecialchars($data['ikon']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-submit w-100 rounded-pill">Update Hobi</button>
                    <a href="index.php" class="btn btn-outline-secondary w-100 mt-2 rounded-pill border-0 text-muted">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>