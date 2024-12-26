<?php
session_start();
include 'db.php';

// Periksa apakah form balas pesan di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesan = $_POST['id_pesan'];
    $balasan = $_POST['balasan'];

    // Update balasan pada pesan terkait
    $stmt = $conn->prepare("UPDATE pesan SET balasan = ? WHERE id = ?");
    $stmt->bind_param('si', $balasan, $id_pesan);
    $stmt->execute();

    // Set session untuk notifikasi sukses
    $_SESSION['message'] = 'Balasan berhasil dikirim.';

    // Redireksi kembali ke halaman utama
    header("Location: index.php");
    exit();
}
?>
