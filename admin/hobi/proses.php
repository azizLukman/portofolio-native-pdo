<?php
include '../../config/koneksi.php';

$aksi = $_GET['aksi'];

// TAMBAH
if ($aksi == 'tambah') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_hobi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $ikon = 'fa-solid fa-heart'; 
    mysqli_query($conn, "INSERT INTO hobi (nama_hobi, deskripsi, ikon) VALUES ('$nama', '$deskripsi', '$ikon')");
    header("location:index.php");
}

// EDIT
elseif ($aksi == 'edit') {
    $id = $_POST['id_hobi'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_hobi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    mysqli_query($conn, "UPDATE hobi SET nama_hobi='$nama', deskripsi='$deskripsi' WHERE id_hobi='$id'");
    header("location:index.php");
}

// HAPUS
elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM hobi WHERE id_hobi = '$id'");
    header("location:index.php");
}
?>