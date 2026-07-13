<?php
include '../../config/session.php';
include '../../config/koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM proyek WHERE id_proyek = '$id'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Proyek</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --bg-dark: #030712; --bg-radial: radial-gradient(circle at 80% 20%, #1e1b4b 0%, #030712 60%); --card-border: rgba(255, 255, 255, 0.05); }
        body { background: var(--bg-dark); background: var(--bg-radial); color: #ffffff; font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        .glass-card { background: rgba(17, 24, 39, 0.4); border: 1px solid var(--card-border); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); }
        .form-control { background: rgba(255, 255, 255, 0.03) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: white !important; border-radius: 12px; padding: 12px; }
        .btn-gradient { background: linear-gradient(90deg, #6366f1, #a855f7); color: white; border: none; padding: 12px 24px; border-radius: 30px; font-weight: 500; }
        .gradient-text { background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="d-flex align-items-center">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 glass-card">
            <h3 class="fw-bold mb-4">Edit <span class="gradient-text">Proyek</span></h3>
            <form action="proses.php?aksi=edit" method="POST">
                <input type="hidden" name="id_proyek" value="<?= $data['id_proyek'] ?>">
                <div class="mb-3">
                    <label class="form-label text-light">Nama Proyek</label>
                    <input type="text" name="nama_proyek" class="form-control" value="<?= $data['nama_proyek'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-light">Tech Stack</label>
                    <input type="text" name="tech_stack" class="form-control" value="<?= $data['tech_stack'] ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-light">Link Proyek</label>
                    <input type="url" name="link" class="form-control" value="<?= $data['link'] ?>" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gradient flex-grow-1"><i class="fa-solid fa-save me-2"></i>Update Data</button>
                    <a href="index.php" class="btn btn-outline-secondary px-4 rounded-pill">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>