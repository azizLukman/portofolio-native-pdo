<?php
include '../../config/session.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kontak Baru</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #030712;
            --bg-radial: radial-gradient(circle at 80% 20%, #1e1b4b 0%, #030712 60%);
            --card-border: rgba(255, 255, 255, 0.05);
        }
        body { background: var(--bg-dark); background: var(--bg-radial); color: #ffffff; font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        
        /* Nav & Layout */
        .navbar { background: rgba(7, 10, 18, 0.6); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, .05); }
        .glass-card { background: rgba(17, 24, 39, 0.6); border: 1px solid var(--card-border); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); }
        .gradient-text { background: linear-gradient(135deg, #60a5fa 0%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Form Styling */
        .form-label { color: #9ca3af; font-size: 0.9rem; margin-bottom: 8px; }
        .form-control, .form-select { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; padding: 12px; border-radius: 12px; }
        .form-control:focus, .form-select:focus { background: rgba(255, 255, 255, 0.05); border-color: #6366f1; color: white; box-shadow: none; }
        .form-select option { background: #111827; color: white; }
        
        .btn-save { background: linear-gradient(90deg, #6366f1, #a855f7); border: none; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; width: 100%; }
        .btn-back { background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #9ca3af; padding: 12px 24px; border-radius: 12px; text-decoration: none; display: block; text-align: center; }
        .btn-back:hover { background: rgba(255,255,255,0.05); color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-2">
        <a class="text-decoration-none text-white d-flex align-items-center gap-2" href="../dashboard.php">
            <div style="width: 35px; height: 35px; background: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900;">A</div>
            <strong>CONTROL CENTER</strong>
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="glass-card">
                <h3 class="mb-4">Tambah <span class="gradient-text">Kontak</span></h3>
                
                <form action="proses.php?aksi=tambah" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Platform</label>
                        <select name="nama" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Platform --</option>
                            <option value="WhatsApp">WhatsApp</option>
                            <option value="Instagram">Instagram</option>
                            <option value="TikTok">TikTok</option>
                            <option value="YouTube">YouTube</option>
                            <option value="Email">Email</option>
                            <option value="GitHub">GitHub</option>
                            <option value="LinkedIn">LinkedIn</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Link / Nomor</label>
                        <input type="text" name="link" class="form-control" placeholder="Contoh: https://wa.me/628..." required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-save mb-2">Simpan Kontak</button>
                        <a href="index.php" class="btn-back">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>