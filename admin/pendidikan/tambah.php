<?php
include '../../config/session.php';
include '../../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Riwayat Pendidikan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: #030712;
            background: radial-gradient(circle at 80% 20%, #1e1b4b 0%, #030712 60%);
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            padding: 60px 0;
        }
        .glass-card {
            background: rgba(17, 24, 39, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
        }
        .form-control, .form-control:focus {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 12px;
        }
        .form-control:focus { border-color: #6366f1; box-shadow: none; }
        .btn-save {
            background: linear-gradient(90deg, #6366f1, #a855f7);
            color: white; border: none; padding: 12px 30px; border-radius: 30px; font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-3 fw-bold m-0"><i class="fa-solid fa-plus me-2 text-primary"></i>TAMBAH <span style="color:#a855f7">PENDIDIKAN</span></h2>
                <a href="index.php" class="btn btn-sm btn-outline-secondary rounded-pill text-white px-3"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
            </div>

            <div class="glass-card">
                <form action="proses.php?aksi=tambah" method="POST" enctype="multipart/form-data" onsubmit="return konfirmasiSimpan();">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Nama Institusi / Sekolah</label>
                        <input type="text" name="institusi" class="form-control" required placeholder="Contoh: Universitas Ma'arif Nahdlatul Ulama Kebumen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Jurusan / Peminatan</label>
                        <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Teknik Informatika">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Tahun Mulai</label>
                            <input type="number" name="tahun_mulai" class="form-control" required placeholder="Contoh: 2022">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Tahun Selesai</label>
                            <input type="text" name="tahun_selesai" class="form-control" required placeholder="Contoh: 2026 / Sekarang">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Deskripsi Pencapaian</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan pencapaian..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold d-block"><i class="fa-solid fa-file-pdf text-danger me-2"></i>Upload Dokumen Ijazah / Transkrip (PDF / Gambar)</label>
                        <input type="file" name="ijazah" class="form-control" required>
                        <div class="form-text text-muted" style="font-size: 11px;">*Wajib diisi. Pastikan ukuran file gambar di bawah 1MB agar sukses tersimpan.</div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk me-2"></i>Simpan Riwayat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function konfirmasiSimpan() {
    // Memunculkan dialog konfirmasi bawaan browser
    var setuju = confirm("Apakah Anda yakin ingin menyimpan data riwayat pendidikan dan berkas ijazah ini?");
    
    if (setuju) {
        return true; // Jika klik OK, form akan terkirim dan disimpan
    } else {
        return false; // Jika klik Cancel, proses kirim data dibatalkan otomatis
    }
}
</script>

</body>
</html>