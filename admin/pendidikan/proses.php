<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/session.php';
include '../../config/koneksi.php';

$get_aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// 1. PROSES TAMBAH DATA
if ($get_aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $institusi     = mysqli_real_escape_string($conn, $_POST['institusi']);
    $jurusan       = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $tahun_mulai   = mysqli_real_escape_string($conn, $_POST['tahun_mulai']);
    $tahun_selesai = mysqli_real_escape_string($conn, $_POST['tahun_selesai']);
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Validasi Dasar Pengiriman File
    if (!isset($_FILES['ijazah']) || $_FILES['ijazah']['error'] == UPLOAD_ERR_NO_FILE) {
        echo "<script>alert('Gagal: Anda belum memilih file ijazah atau file terlalu besar melampaui limit Laragon!'); window.history.back();</script>";
        exit();
    }

    $nama_file = $_FILES['ijazah']['name'];
    $tmp_name  = $_FILES['ijazah']['tmp_name'];
    $file_error = $_FILES['ijazah']['error'];
    $file_final = null;

    // Cek apakah ada error bawaan server PHP/Laragon
    if ($file_error !== UPLOAD_ERR_OK) {
        echo "<script>alert('Eror PHP Upload Code: " . $file_error . ". Silakan coba file gambar yang ukurannya lebih kecil.'); window.history.back();</script>";
        exit();
    }

    // Otomatis bikin folder 'uploads' jika belum terbuat di direktori yang pas
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $ekstensi_diizinkan = array('pdf', 'jpg', 'jpeg', 'png');

    if (in_array($ekstensi, $ekstensi_diizinkan)) {
        // Enkripsi nama file agar aman dan unik
        $file_final = "ijazah_" . time() . "_" . uniqid() . "." . $ekstensi;
        $target_pemindahan = "uploads/" . $file_final;
        
        // Eksekusi pemindahan file fisik
        if (!move_uploaded_file($tmp_name, $target_pemindahan)) {
            echo "<script>alert('Eror: PHP gagal memindahkan file ke folder uploads! Periksa izin folder (permission) Anda atau jalur folder.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Format file salah! Hanya menerima PDF, JPG, JPEG, dan PNG.'); window.history.back();</script>";
        exit();
    }

    // Masukkan data jika file fisiknya terbukti sukses ter-upload
    $query = "INSERT INTO pendidikan (institusi, jurusan, tahun_mulai, tahun_selesai, deskripsi, ijazah) 
              VALUES ('$institusi', '$jurusan', '$tahun_mulai', '$tahun_selesai', '$deskripsi', '$file_final')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data riwayat pendidikan dan ijazah berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal simpan ke database: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }

// 2. PROSES HAPUS DATA
} elseif ($get_aksi == 'hapus') {
    $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

    $test_query = mysqli_query($conn, "SELECT * FROM pendidikan LIMIT 1");
    $fields = mysqli_fetch_assoc($test_query);
    $kolom_id = isset($fields['id']) ? 'id' : (isset($fields['id_pendidikan']) ? 'id_pendidikan' : array_key_first($fields));

    $check_file = mysqli_query($conn, "SELECT ijazah FROM pendidikan WHERE $kolom_id = '$id'");
    $data_file = mysqli_fetch_assoc($check_file);
    if (!empty($data_file['ijazah']) && file_exists("uploads/" . $data_file['ijazah'])) {
        unlink("uploads/" . $data_file['ijazah']); 
    }

    $query = "DELETE FROM pendidikan WHERE $kolom_id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location='index.php';</script>";
    }
}
?>