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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Caveat:wght@500&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Chat Anonim</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(180deg, #0f0f0f, #1c1c1c);
      color: #d1d5db;
    }
    h2 {
      font-family: 'Caveat', cursive;
      color: #e0e7ff;
    }
    .shadow-custom {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
    }
    .bg-gradient-mystic {
      background: linear-gradient(135deg, #312e81, #1e293b);
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Navbar -->
  <nav class="bg-gradient-mystic shadow-custom">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
      <h2 class="text-xl font-semibold text-white">Chat Anonim</h2>
    </div>
  </nav>

  <!-- Content Section -->
  <div class="max-w-4xl mx-auto p-6">

    <!-- Form Pesan -->
    <section class="mb-8 bg-gray-900 p-6 rounded-lg shadow-custom">
      <h2 class="text-3xl font-semibold text-center mb-4">Isi Pesan Rahasia</h2>
      <form action="" method="POST" class="space-y-4">
        <div>
          <label for="nama" class="block text-lg text-gray-300">Username</label>
          <input type="text" id="nama" name="nama" required 
            class="mt-2 w-full p-3 border border-gray-700 rounded-lg bg-gray-800 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label for="komentar" class="block text-lg text-gray-300">Pesan</label>
          <textarea id="komentar" name="komentar" rows="4" required 
            class="mt-2 w-full p-3 border border-gray-700 rounded-lg bg-gray-800 text-gray-200 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
        </div>
        <button type="submit" 
          class="w-full bg-gradient-to-r from-indigo-500 to-purple-700 text-white py-3 rounded-lg text-lg font-semibold shadow hover:shadow-lg transform hover:scale-105 transition">
          Kirim
        </button>
      </form>
    </section>

    <!-- Daftar Pesan -->
    <section class="mb-8 bg-gray-900 p-6 rounded-lg shadow-custom">
      <h2 class="text-3xl font-semibold mb-4 text-center">Pesan Rahasia</h2>
      <?php if ($result->num_rows > 0): ?>
        <div class="space-y-6">
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="p-4 bg-gray-800 rounded-lg shadow hover:shadow-lg transition">
              <p class="font-semibold text-lg text-gray-100"><?= htmlspecialchars($row['nama']); ?></p>
              <p class="text-sm text-gray-400"><?= htmlspecialchars($row['tanggal']); ?></p>
              <p class="mt-2 text-gray-300"><?= htmlspecialchars($row['komentar']); ?></p>
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
    <div class="max-w-7xl mx-auto px-6  items-center">
      <h2 class="text-center text-xl">Created by Evan👑</h2>
    </div>
  </footer>

  <script>
    <?php if (isset($_SESSION['message'])): ?>
      Swal.fire({
        title: 'Pesan Terkirim!',
        text: '<?php echo $_SESSION['message']; ?>',
        icon: 'success',
        confirmButtonText: 'OK'
      });
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
  </script>

</body>
</html>
