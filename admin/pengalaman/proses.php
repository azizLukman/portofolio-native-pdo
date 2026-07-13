<?php
include '../../config/koneksi.php';

$aksi = $_GET['aksi'];

if ($aksi == 'tambah') {
    $posisi     = mysqli_real_escape_string($conn, $_POST['posisi']);
    $perusahaan = mysqli_real_escape_string($conn, $_POST['perusahaan']);
    $mulai      = mysqli_real_escape_string($conn, $_POST['tahun_mulai']);
    $selesai    = mysqli_real_escape_string($conn, $_POST['tahun_selesai']);
    $deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    mysqli_query($conn, "INSERT INTO pengalaman (posisi, perusahaan, tahun_mulai, tahun_selesai, deskripsi) 
                         VALUES ('$posisi', '$perusahaan', '$mulai', '$selesai', '$deskripsi')");
    header("location:index.php");
}
elseif ($aksi == 'edit') {
    $id         = $_POST['id_pengalaman'];
    $posisi     = mysqli_real_escape_string($conn, $_POST['posisi']);
    $perusahaan = mysqli_real_escape_string($conn, $_POST['perusahaan']);
    $mulai      = mysqli_real_escape_string($conn, $_POST['tahun_mulai']);
    $selesai    = mysqli_real_escape_string($conn, $_POST['tahun_selesai']);
    $deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    mysqli_query($conn, "UPDATE pengalaman SET posisi='$posisi', perusahaan='$perusahaan', tahun_mulai='$mulai', tahun_selesai='$selesai', deskripsi='$deskripsi' WHERE id_pengalaman='$id'");
    header("location:index.php");
}
elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM pengalaman WHERE id_pengalaman = '$id'");
    header("location:index.php");
}
?>