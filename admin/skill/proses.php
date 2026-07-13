<?php
include '../../config/koneksi.php';

// Deteksi Primary Key otomatis agar tidak error
$pk_query = mysqli_query($conn, "SHOW KEYS FROM skill WHERE Key_name = 'PRIMARY'");
$pk_row = mysqli_fetch_assoc($pk_query);
$pk = $pk_row['Column_name'];

$aksi = $_GET['aksi'];
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

// 1. TAMBAH DATA
if ($aksi == 'tambah') {
    $gambar = $_FILES['gambar']['name'];
    if($gambar != "") {
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads_skill/'.$gambar);
    }
    mysqli_query($conn, "INSERT INTO skill (nama_skill, tingkat, persentase, gambar) VALUES ('$_POST[nama_skill]', '$_POST[tingkat]', '$_POST[persentase]', '$gambar')");
    header("location:index.php");

// 2. HAPUS DATA
} elseif ($aksi == 'hapus') {
    $q = mysqli_query($conn, "SELECT gambar FROM skill WHERE $pk='$id'");
    $d = mysqli_fetch_assoc($q);
    if ($d && file_exists('uploads_skill/'.$d['gambar'])) {
        unlink('uploads_skill/'.$d['gambar']);
    }
    mysqli_query($conn, "DELETE FROM skill WHERE $pk = '$id'");
    header("location:index.php");

// 3. EDIT DATA
} elseif ($aksi == 'edit') {
    $id = $_POST['id'];
    if($_FILES['gambar']['name'] != "") {
        // Hapus file lama dulu
        $q = mysqli_query($conn, "SELECT gambar FROM skill WHERE $pk='$id'");
        $d = mysqli_fetch_assoc($q);
        if ($d && file_exists('uploads_skill/'.$d['gambar'])) {
            unlink('uploads_skill/'.$d['gambar']);
        }
        
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads_skill/'.$gambar);
        mysqli_query($conn, "UPDATE skill SET nama_skill='$_POST[nama_skill]', tingkat='$_POST[tingkat]', persentase='$_POST[persentase]', gambar='$gambar' WHERE $pk='$id'");
    } else {
        mysqli_query($conn, "UPDATE skill SET nama_skill='$_POST[nama_skill]', tingkat='$_POST[tingkat]', persentase='$_POST[persentase]' WHERE $pk='$id'");
    }
    header("location:index.php");
}
?>