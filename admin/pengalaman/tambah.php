<?php
include '../../config/session.php';
include '../../config/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengalaman</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root { 
            --bg-dark: #030712; 
            --bg-radial: radial-gradient(circle at 80% 20%, #1e1b4b 0%, #030712 60%); 
            --card-border: rgba(255, 255, 255, 0.05); 
        }
        
        /* Memaksa semua teks menjadi putih */
        body, h1, h2, h3, h4, h5, h6, label, p, span, div, a { 
            color: #ffffff !important; 
        }

        body { 
            background: var(--bg-dark); 
            background: var(--bg-radial); 
            font-family: 'Segoe UI', sans-serif; 
            min-height: 100vh; 
            display: flex;
            align-items: center;
        }

        .glass-card { 
            background: rgba(17, 24, 39, 0.6); 
            border: 1px solid var(--card-border); 
            border-radius: 20px; 
            padding: 40px; 
            backdrop-filter: blur(10px); 
        }

        .form-control { 
            background: rgba(255, 255, 255, 0.03) !important; 
            border: 1px solid rgba(255, 255, 255, 0.1) !important; 
            color: white !important; 
            padding: 12px; 
            border-radius: 12px; 
        }

        .btn-save { 
            background: linear-gradient(90deg, #6366f1, #a855f7); 
            border: none; 
            color: white !important; 
            padding: 12px 24px; 
            border-radius: 12px; 
            font-weight: 600; 
            width: 100%; 
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .gradient-text { 
            background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 glass-card">
            <h3 class="mb-4 fw-bold">Tambah <span class="gradient-text">Pengalaman</span></h3>
            <form action="proses.php?aksi=tambah" method="POST">
                
                <div class="mb-3">
                    <label class="form-label">Posisi</label>
                    <input type="text" name="posisi" class="form-control" placeholder="Contoh: Web Developer" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Perusahaan</label>
                    <input type="text" name="perusahaan" class="form-control" placeholder="Contoh: Tech Corp" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Mulai</label>
                        <input type="number" name="tahun_mulai" class="form-control" placeholder="2023" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Selesai</label>
                        <input type="number" name="tahun_selesai" class="form-control" placeholder="2024" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Ceritakan tanggung jawabmu..." required></textarea>
                </div>
                
                <button type="submit" class="btn btn-save">Simpan Data</button>
                <a href="index.php" class="btn-back">Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>