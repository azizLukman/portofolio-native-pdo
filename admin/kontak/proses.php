<?php
include '../../config/koneksi.php';

// Fungsi untuk menentukan ikon otomatis
function getIkon($platform) {
    $map = [
        'WhatsApp'  => 'fa-brands fa-whatsapp',
        'Instagram' => 'fa-brands fa-instagram',
        'TikTok'    => 'fa-brands fa-tiktok',
        'YouTube'   => 'fa-brands fa-youtube',
        'Email'     => 'fa-solid fa-envelope',
        'GitHub'    => 'fa-brands fa-github',
        'LinkedIn'  => 'fa-brands fa-linkedin'
    ];
    return isset($map[$platform]) ? $map[$platform] : 'fa-solid fa-globe';
}

$aksi = $_GET['aksi'];

// --- 1. PROSES TAMBAH ---
if ($aksi == 'tambah') {
    // GANTI [NAMA_KOLOM_DI_DATABASE] SESUAI HASIL CEK PHPMYADMIN
    $platform = mysqli_real_escape_string($conn, $_POST['nama']); 
    $link     = mysqli_real_escape_string($conn, $_POST['link']);
    $ikon     = getIkon($platform);

    // SESUAIKAN NAMA KOLOM DI QUERY DI BAWAH INI
    mysqli_query($conn, "INSERT INTO kontak (nama, link, ikon) VALUES ('$platform', '$link', '$ikon')");
    
    header("location:index.php");
}

// --- 2. PROSES EDIT ---
elseif ($aksi == 'edit') {
    $id       = $_POST['id_kontak'];
    $platform = mysqli_real_escape_string($conn, $_POST['nama']);
    $link     = mysqli_real_escape_string($conn, $_POST['link']);
    $ikon     = getIkon($platform);

    // SESUAIKAN NAMA KOLOM DI QUERY DI BAWAH INI
    mysqli_query($conn, "UPDATE kontak SET nama='$platform', link='$link', ikon='$ikon' WHERE id_kontak='$id'");
    
    header("location:index.php");
}

// --- 3. PROSES HAPUS ---
elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM kontak WHERE id_kontak = '$id'");
    header("location:index.php");
}
?>