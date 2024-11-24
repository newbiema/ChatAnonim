<?php
session_start();
include 'db.php';

// Proses penyimpanan pesan jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $komentar = $_POST['komentar'];

    $stmt = $conn->prepare("INSERT INTO pesan (nama, komentar) VALUES (?, ?)");
    $stmt->bind_param('ss', $nama, $komentar); // 'ss' artinya kedua parameter adalah string
    $stmt->execute();
    $stmt->close();

    // Set session untuk menandakan pesan berhasil dikirim
    $_SESSION['message'] = 'Pesan Anda telah berhasil dikirim.';
    
    // Redirect ke halaman yang sama tanpa query string
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Ambil data pesan dari database
$result = $conn->query("SELECT * FROM pesan ORDER BY tanggal DESC");
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert CDN -->
  <title>Pesan Rahasia</title>
</head>
<body class="bg-gray-50 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
      <a href="#" class="text-white text-2xl font-bold hover:text-indigo-200 transition">Pesan Rahasia</a>
      <div class="space-x-4">
        <a href="#home" class="text-white hover:text-indigo-200 transition">Home</a>
        <a href="#about" class="text-white hover:text-indigo-200 transition">About</a>
        <a href="#contact" class="text-white hover:text-indigo-200 transition">Contact</a>
      </div>
    </div>
  </nav>

  <!-- Content Section -->
  <div class="max-w-4xl mx-auto p-6">

    <!-- Form Pesan -->
    <section class="mb-8 bg-white p-6 rounded-lg shadow-lg">
      <h2 class="text-3xl font-semibold text-gray-800 mb-4">Isi Pesan Rahasia</h2>
      <form action="" method="POST" class="space-y-4">
        <div>
          <label for="nama" class="block text-lg font-medium text-gray-700">Nama</label>
          <input type="text" id="nama" name="nama" required 
            class="mt-2 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label for="komentar" class="block text-lg font-medium text-gray-700">Komentar</label>
          <textarea id="komentar" name="komentar" rows="4" required 
            class="mt-2 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>
        <button type="submit" 
          class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 rounded-lg text-lg font-semibold shadow hover:shadow-lg transform hover:scale-105 transition">
          Kirim
        </button>
      </form>
    </section>

    <!-- Daftar Pesan -->
    <section class="mb-8 bg-white p-6 rounded-lg shadow-lg">
      <h2 class="text-3xl font-semibold text-gray-800 mb-4">Pesan Rahasia</h2>
      <?php if ($result->num_rows > 0): ?>
        <div class="space-y-6">
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-gray-50 p-4 rounded-lg shadow hover:shadow-lg transition">
              <p class="font-semibold text-xl text-gray-900"><?= htmlspecialchars($row['nama']); ?></p>
              <p class="text-sm text-gray-500"><?= htmlspecialchars($row['tanggal']); ?></p>
              <p class="mt-2 text-gray-700"><?= htmlspecialchars($row['komentar']); ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500 text-center">Belum ada pesan yang dikirim.</p>
      <?php endif; ?>
    </section>

  </div>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-6">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
      <p class="text-lg">© 2024 Pesan Rahasia. All rights reserved.</p>
      <div class="flex space-x-6">
        <a href="#" class="hover:text-indigo-400 transition">Privacy Policy</a>
        <a href="#" class="hover:text-indigo-400 transition">Terms of Service</a>
        <a href="#" class="hover:text-indigo-400 transition">Help</a>
      </div>
    </div>
  </footer>

  <script>
    // Menampilkan SweetAlert jika pesan berhasil dikirim
    <?php if (isset($_SESSION['message'])): ?>
      Swal.fire({
        title: 'Pesan Terkirim!',
        text: '<?php echo $_SESSION['message']; ?>',
        icon: 'success',
        confirmButtonText: 'OK'
      });

      // Menghapus pesan dari session setelah menampilkan alert
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
  </script>

</body>
</html>
