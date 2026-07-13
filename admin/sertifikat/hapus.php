<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/session.php';
include '../../config/koneksi.php';

if (isset($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. Deteksi dinamis nama primary key
    $columns_check = mysqli_query($conn, "SHOW COLUMNS FROM sertifikat");
    $id_column_key = 'id';
    $existing_columns = [];
    while ($col = mysqli_fetch_assoc($columns_check)) {
        $existing_columns[] = $col['Field'];
        if ($col['Key'] == 'PRI') {
            $id_column_key = $col['Field'];
        }
    }

    // 2. Ambil informasi berkas gambar lama untuk dihapus dari folder assets
    $res_file = mysqli_query($conn, "SELECT * FROM sertifikat WHERE $id_column_key = '$id_hapus'");
    if ($row_file = mysqli_fetch_assoc($res_file)) {
        $nama_foto = $row_file['gambar'] ?? $row_file['file_gambar'] ?? $row_file['foto'] ?? '';
        if ($nama_foto != "" && file_exists("../../assets/images/sertifikat/" . $nama_foto)) {
            unlink("../../assets/images/sertifikat/" . $nama_foto); // Hapus berkas fisik
        }
    }

    // 3. Eksekusi hapus baris data di database
    $sql_delete = "DELETE FROM sertifikat WHERE $id_column_key = '$id_hapus'";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('Sertifikat berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data dari database: " . mysqli_error($conn) . "'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}