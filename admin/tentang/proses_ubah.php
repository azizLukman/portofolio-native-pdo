<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/session.php';
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : '';
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // 1. Cek apakah sudah ada data di tabel 'tentang'
    $cek_data = mysqli_query($conn, "SELECT * FROM tentang LIMIT 1");
    $jumlah_data = mysqli_num_rows($cek_data);
    $data_lama = mysqli_fetch_assoc($cek_data);

    // 2. Ambil nama file foto lama untuk validasi penimpaan file fisik
    $foto_lama = isset($data_lama['foto']) ? $data_lama['foto'] : '';
    $foto_nama_baru = $foto_lama; 

    // 3. Proses Upload File Foto (Jika pengguna mengunggah foto baru)
    if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != "") {
        $filename = $_FILES['foto']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $ekstensi_diizinkan = array('jpg', 'jpeg', 'png', 'webp');
        
        if (in_array($ext, $ekstensi_diizinkan)) {
            // Membuat nama acak unik agar browser melakukan refresh gambar secara realtime
            $foto_nama_baru = "profile_" . time() . "." . $ext;
            $target_dir = "../../assets/img/";
            
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $foto_nama_baru)) {
                // Hapus foto lama dari lokal folder jika filenya memang ada
                if (!empty($foto_lama) && file_exists($target_dir . $foto_lama)) {
                    unlink($target_dir . $foto_lama);
                }
            }
        } else {
            echo "<script>alert('Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau WEBP.'); window.history.back();</script>";
            exit();
        }
    }

    // 4. Logika Utama: Jika tabel kosong = INSERT, jika sudah ada data = UPDATE
    if ($jumlah_data == 0) {
        // Jalankan perintah ini jika database benar-benar masih kosong
        $sql_aksi = "INSERT INTO tentang (deskripsi, foto) VALUES ('$deskripsi', '$foto_nama_baru')";
    } else {
        // Jalankan perintah ini untuk memperbarui data yang sudah ada tanpa bergantung pada input ID hidden
        // Menggunakan LIMIT 1 agar lebih aman dan hanya mengubah baris pertama profil Anda
        $sql_aksi = "UPDATE tentang SET deskripsi = '$deskripsi', foto = '$foto_nama_baru' LIMIT 1";
    }

    // 5. Eksekusi ke Database MySQL
    if (mysqli_query($conn, $sql_aksi)) {
        echo "<script>alert('Perubahan data profil berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui database: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>