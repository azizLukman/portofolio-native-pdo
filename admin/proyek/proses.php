<?php
include '../../config/session.php';
include '../../config/koneksi.php';

$aksi = $_GET['aksi'];

if ($aksi == 'tambah') {
    $nama = $_POST['nama_proyek'];
    $tech = $_POST['tech_stack'];
    $link = $_POST['link'];
    
    mysqli_query($conn, "INSERT INTO proyek (nama_proyek, tech_stack, link) VALUES ('$nama', '$tech', '$link')");
    header("location:index.php");
    
} elseif ($aksi == 'edit') {
    $id = $_POST['id_proyek'];
    $nama = $_POST['nama_proyek'];
    $tech = $_POST['tech_stack'];
    $link = $_POST['link'];
    
    mysqli_query($conn, "UPDATE proyek SET nama_proyek='$nama', tech_stack='$tech', link='$link' WHERE id_proyek='$id'");
    header("location:index.php");
    
} elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM proyek WHERE id_proyek='$id'");
    header("location:index.php");
}
?>