<?php
include '../../config/session.php';
include '../../config/koneksi.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
$query = mysqli_query($conn, "SELECT * FROM pendidikan WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Riwayat Pendidikan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-dark: #030712;
            --bg-radial: radial-gradient(circle at 80% 20%, #1e1b4b 0%, #030712 60%);
            --card-border: rgba(255, 255, 255, 0.05);
        }
        body { background: var(--bg-dark); background: var(--bg-radial); color: #ffffff; font-family: 'Segoe UI', sans-serif; min-height: 100vh; padding: 60px 0;}
        .glass-card { background: rgba(17, 24, 39, 0.5); border: 1px solid var(--card-border); border-radius: 20px; padding: 35px; backdrop-filter: blur(10px); }
        .form-label-custom { font-weight: 500; font-size: 12px; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control-premium { background: rgba(255, 255, 255, 0.03) !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; color: #ffffff !important; border-radius: 12px; padding: 12px 16px; font-size: 14px; transition: all 0.3s ease; }
        .form-control-premium:focus { border-color: rgba(168, 85, 247, 0.5) !important; box-shadow: 0 0 15px rgba(168, 85, 247, 0.25) !important; }
        .btn-gradient-primary { background: linear-gradient(90deg, #6366f1, #a855f7); color: white; border: none; padding: 12px 28px; border-radius: 30px; font-weight: 500; font-size: 14px; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3); transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-gradient-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(139, 92, 246, 0.5); color: white; }
        .btn-outline-custom { border: 1px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.03); color: white; padding: 12px 24px; border-radius: 30px; font-size: 14px; text-decoration: none; transition: background 0.3s; }
        .btn-outline-custom:hover { background: rgba(255, 255, 255, 0.1); color: white; }
        .gradient-text { background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body>

<div class="container" style="max-width: 800px;">
    <div class="mb-4">
        <div class="text-primary fw-bold small mb-1"><i class="fa-solid fa-pen-to-square me-2"></i>MODUL PEMBARUAN</div>
        <h1 class="fs-3 fw-bold m-0">UBAH <span class="gradient-text">PENDIDIKAN</span></h1>
    </div>

    <div class="glass-card">
        <form action="proses.php?aksi=edit" method="POST">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <div class="row">
                <div class="col-md-4 mb-4">
                    <label class="form-label-custom mb-2 d-block"><i class="fa-solid fa-layer-group text-primary me-2"></i>Jenjang</label>
                    <select name="jenjang" class="form-control-premium form-select" required>
                        <option value="SD" <?= $data['jenjang'] == 'SD' ? 'selected' : '' ?>>SD / Sederajat</option>
                        <option value="SMP" <?= $data['jenjang'] == 'SMP' ? 'selected' : '' ?>>SMP / Sederajat</option>
                        <option value="SMA/SMK" <?= $data['jenjang'] == 'SMA/SMK' ? 'selected' : '' ?>>SMA / SMK / Sederajat</option>
                        <option value="D3" <?= $data['jenjang'] == 'D3' ? 'selected' : '' ?>>Diploma (D3)</option>
                        <option value="S1" <?= $data['jenjang'] == 'S1' ? 'selected' : '' ?>>Sarjana (S1)</option>
                        <option value="S2" <?= $data['jenjang'] == 'S2' ? 'selected' : '' ?>>Magister (S2)</option>
                    </select>
                </div>
                <div class="col-md-8 mb-4">
                    <label class="form-label-custom mb-2 d-block"><i class="fa-solid fa-school text-primary me-2"></i>Nama Institusi / Sekolah</label>
                    <input type="text" name="institusi" class="form-control-premium w-100" value="<?= htmlspecialchars($data['institusi']) ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label-custom mb-2 d-block"><i class="fa-solid fa-book-bookmark text-purple me-2"></i>Jurusan / Program Studi (Opsional)</label>
                <input type="text" name="jurusan" class="form-control-premium w-100" value="<?= htmlspecialchars($data['jurusan']) ?>">
            </div>

            <div class="row">
                <div class="col-6 mb-4">
                    <label class="form-label-custom mb-2 d-block"><i class="fa-regular fa-calendar text-info me-2"></i>Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" min="1900" max="2100" class="form-control-premium w-100" value="<?= htmlspecialchars($data['tahun_mulai']) ?>" required>
                </div>
                <div class="col-6 mb-4">
                    <label class="form-label-custom mb-2 d-block"><i class="fa-regular fa-calendar-check text-success me-2"></i>Tahun Selesai</label>
                    <input type="text" name="tahun_selesai" class="form-control-premium w-100" value="<?= htmlspecialchars($data['tahun_selesai']) ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label-custom mb-2 d-block"><i class="fa-solid fa-star text-warning me-2"></i>Deskripsi / Pencapaian (Opsional)</label>
                <textarea name="deskripsi" rows="4" class="form-control-premium w-100"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-gradient-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="index.php" class="btn-outline-custom ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>