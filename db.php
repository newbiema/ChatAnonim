<?php
// Konfigurasi database
$host = 'localhost';       // Host database
$username = 'root';        // Username database (default: root)
$password = '';            // Password database (default: kosong)
$dbname = 'anonim_chat';   // Nama database

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
