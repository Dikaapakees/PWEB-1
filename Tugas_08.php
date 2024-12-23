<?php
// Nama file JSON untuk menyimpan data
$file = 'data.json';

// Jika file tidak ada, buat file baru
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// Baca data dari file JSON
$data_alumni = json_decode(file_get_contents($file), true);

// Tambah data alumni
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $pekerjaan = $_POST['pekerjaan'];

    if (!empty($nama) && !empty($tahun_lulus) && !empty($pekerjaan)) {
        $data_alumni[] = [
            'nama' => $nama,
            'tahun_lulus' => $tahun_lulus,
            'pekerjaan' => $pekerjaan
        ];
        file_put_contents($file, json_encode($data_alumni, JSON_PRETTY_PRINT));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error_message = "Semua field harus diisi!";
    }
}

// Hapus data alumni
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    if (isset($data_alumni[$index])) {
        unset($data_alumni[$index]);
        $data_alumni = array_values($data_alumni); // Reindex array
        file_put_contents($file, json_encode($data_alumni, JSON_PRETTY_PRINT));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Ambil data untuk edit
if (isset($_GET['edit'])) {
    $index_edit = $_GET['edit'];
    if (isset($data_alumni[$index_edit])) {
        $alumni_edit = $data_alumni[$index_edit];
    }
}

// Edit data alumni
if (isset($_POST['edit_submit'])) {
    $index = $_POST['index'];
    $nama = $_POST['nama'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $pekerjaan = $_POST['pekerjaan'];

    if (isset($data_alumni[$index])) {
        $data_alumni[$index] = [
            'nama' => $nama,
            'tahun_lulus' => $tahun_lulus,
            'pekerjaan' => $pekerjaan
        ];
        file_put_contents($file, json_encode($data_alumni, JSON_PRETTY_PRINT));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tracker Alumni Sederhana</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: auto;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .form-container {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
        }
        .form-container input, .form-container button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Tracker Alumni</h1>

    <?php if (isset($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tahun Lulus</th>
                <th>Pekerjaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data_alumni)): ?>
                <tr><td colspan="4" style="text-align: center;">Belum ada data alumni.</td></tr>
            <?php else: ?>
                <?php foreach ($data_alumni as $index => $alumni): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumni['nama']) ?></td>
                        <td><?= htmlspecialchars($alumni['tahun_lulus']) ?></td>
                        <td><?= htmlspecialchars($alumni['pekerjaan']) ?></td>
                        <td>
                            <a href="?edit=<?= $index ?>" style="color: green;">Edit</a> |
                            <a href="?hapus=<?= $index ?>" style="color: red;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (!isset($alumni_edit)): ?>
        <div class="form-container">
            <h2>Tambah Data Alumni</h2>
            <form method="post">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" required>

                <label for="tahun_lulus">Tahun Lulus</label>
                <input type="number" name="tahun_lulus" id="tahun_lulus" required>

                <label for="pekerjaan">Pekerjaan</label>
                <input type="text" name="pekerjaan" id="pekerjaan" required>

                <button type="submit" name="tambah">Simpan</button>
            </form>
        </div>
    <?php else: ?>
        <div class="form-container">
            <h2>Edit Data Alumni</h2>
            <form method="post">
                <input type="hidden" name="index" value="<?= $index_edit ?>">

                <label for="nama_edit">Nama</label>
                <input type="text" name="nama" id="nama_edit" value="<?= htmlspecialchars($alumni_edit['nama']) ?>" required>

                <label for="tahun_lulus_edit">Tahun Lulus</label>
                <input type="number" name="tahun_lulus" id="tahun_lulus_edit" value="<?= htmlspecialchars($alumni_edit['tahun_lulus']) ?>" required>

                <label for="pekerjaan_edit">Pekerjaan</label>
                <input type="text" name="pekerjaan" id="pekerjaan_edit" value="<?= htmlspecialchars($alumni_edit['pekerjaan']) ?>" required>

                <button type="submit" name="edit_submit">Simpan Perubahan</button>
            </form>
        </div>
    <?php endif; ?>

</body>
</html>
