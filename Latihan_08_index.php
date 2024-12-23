<?php
session_start();

// Inisialisasi data alumni jika belum ada di session
if (!isset($_SESSION['alumni'])) {
    $_SESSION['alumni'] = []; // Array kosong untuk menampung data alumni
}

// Proses tambah data
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $pekerjaan = $_POST['pekerjaan'];

    // Validasi input (sederhana)
    if (!empty($nama) && !empty($tahun_lulus) && !empty($pekerjaan)) {
      $_SESSION['alumni'][] = [
        'nama' => $nama,
        'tahun_lulus' => $tahun_lulus,
        'pekerjaan' => $pekerjaan
      ];
      header("Location: index.php"); // Redirect untuk mencegah submit ganda
      exit();
    } else {
      $error_message = "Semua field harus diisi!";
    }
}

// Proses hapus data
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    if (isset($_SESSION['alumni'][$index])) {
        unset($_SESSION['alumni'][$index]);
        // Reindex array setelah penghapusan
        $_SESSION['alumni'] = array_values($_SESSION['alumni']);
        header("Location: index.php");
        exit();
    }
}

// Proses edit data
if (isset($_POST['edit'])) {
    $index = $_POST['index'];
    $nama = $_POST['nama'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $pekerjaan = $_POST['pekerjaan'];
    if (isset($_SESSION['alumni'][$index])) {
        $_SESSION['alumni'][$index] = [
          'nama' => $nama,
          'tahun_lulus' => $tahun_lulus,
          'pekerjaan' => $pekerjaan
        ];
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tracker Alumni Sederhana</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        #form-container { border: 1px solid #ccc; padding: 10px; margin-top: 20px; }
        .error { color: red; }
    </style>
</head>
<body>

    <h1>Data Alumni</h1>

    <?php if (isset($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tahun Lulus</th>
                <th>Pekerjaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($_SESSION['alumni'])): ?>
                <tr><td colspan="4">Belum ada data alumni.</td></tr>
            <?php else: ?>
                <?php foreach ($_SESSION['alumni'] as $index => $alumni): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumni['nama']) ?></td>
                        <td><?= htmlspecialchars($alumni['tahun_lulus']) ?></td>
                        <td><?= htmlspecialchars($alumni['pekerjaan']) ?></td>
                        <td>
                            <a href="?hapus=<?= $index ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            <a href="?edit=<?= $index ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Tambah Data Alumni</h2>
    <form method="post">
        Nama: <input type="text" name="nama" required><br>
        Tahun Lulus: <input type="number" name="tahun_lulus" required><br>
        Pekerjaan: <input type="text" name="pekerjaan" required><br>
        <button type="submit" name="tambah">Simpan</button>
    </form>

    <?php if (isset($_GET['edit'])):
        $index_edit = $_GET['edit'];
        if (isset($_SESSION['alumni'][$index_edit])):
            $alumni_edit = $_SESSION['alumni'][$index_edit];
        ?>
        <div id="form-container">
            <h2>Edit Data Alumni</h2>
            <form method="post">
                <input type="hidden" name="index" value="<?= $index_edit ?>">
                Nama: <input type="text" name="nama" value="<?= htmlspecialchars($alumni_edit['nama']) ?>" required><br>
                Tahun Lulus: <input type="number" name="tahun_lulus" value="<?= htmlspecialchars($alumni_edit['tahun_lulus']) ?>" required><br>
                Pekerjaan: <input type="text" name="pekerjaan" value="<?= htmlspecialchars($alumni_edit['pekerjaan']) ?>" required><br>
                <button type="submit" name="edit">Simpan Perubahan</button>
                <a href="index.php">Batal Edit</a>
            </form>
        </div>
        <?php endif;
    endif; ?>

</body>
</html>