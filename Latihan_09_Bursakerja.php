<?php
include 'Latihan_09_config.php';
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Koneksi database gagal: " . $conn->connect_error . "</div>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $perusahaan = $conn->real_escape_string($_POST['perusahaan']);
    $posisi = $conn->real_escape_string($_POST['posisi']);
    $lokasi = $conn->real_escape_string($_POST['lokasi']);
    $tanggal = $_POST['tanggal'];

    $tanggal_valid = DateTime::createFromFormat('Y-m-d', $tanggal);
    if (!$tanggal_valid) {
        die("<div class='alert alert-danger'>Format tanggal tidak valid.</div>");
    }

    $sql_insert = $conn->prepare("INSERT INTO lowongan (perusahaan, posisi, lokasi, tanggal) VALUES (?, ?, ?, ?)");
    if ($sql_insert === false) {
        die("<div class='alert alert-danger'>Prepare statement gagal: " . $conn->error . "</div>");
    }
    $sql_insert->bind_param("ssss", $perusahaan, $posisi, $lokasi, $tanggal);

    if ($sql_insert->execute()) {
        echo "<div class='alert alert-success'>Lowongan berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql_insert->error . "</div>";
    }
    $sql_insert->close();
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    if (is_numeric($id)) {
        $id = (int)$id;

        $sql_delete = $conn->prepare("DELETE FROM lowongan WHERE id = ?");
        if ($sql_delete === false) {
            die("<div class='alert alert-danger'>Prepare statement gagal: " . $conn->error . "</div>");
        }
        $sql_delete->bind_param("i", $id);

        if ($sql_delete->execute()) {
            echo "<div class='alert alert-success'>Lowongan berhasil dihapus!</div>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: " . $sql_delete->error . "</div>";
        }
        $sql_delete->close();
    } else {
        echo "<div class='alert alert-warning'>ID yang diterima tidak valid!</div>";
    }
}

// Menangani lamaran
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lamar'])) {
    $lowongan_id = $_POST['lowongan_id'];
    $nama_pelamar = $conn->real_escape_string($_POST['nama_pelamar']);
    $email_pelamar = $conn->real_escape_string($_POST['email_pelamar']);

    // Validasi email
    if (!filter_var($email_pelamar, FILTER_VALIDATE_EMAIL)) {
        die("<div class='alert alert-danger'>Email tidak valid.</div>");
    }

    if (is_numeric($lowongan_id)) {
        $sql_insert_lamaran = $conn->prepare("INSERT INTO lamaran (lowongan_id, nama_pelamar, email_pelamar) VALUES (?, ?, ?)");
        if ($sql_insert_lamaran === false) {
            die("<div class='alert alert-danger'>Prepare statement gagal: " . $conn->error . "</div>");
        }
        $sql_insert_lamaran->bind_param("iss", $lowongan_id, $nama_pelamar, $email_pelamar);

        if ($sql_insert_lamaran->execute()) {
            echo "<div class='alert alert-success'>Lamaran berhasil dikirim!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $sql_insert_lamaran->error . "</div>";
        }
        $sql_insert_lamaran->close();
    }
}

?>

<div class="container mt-5">
    <h2 class="mb-4">Tambah Lowongan Baru</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="perusahaan" class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-control" id="perusahaan" name="perusahaan" required>
        </div>
        <div class="mb-3">
            <label for="posisi" class="form-label">Posisi</label>
            <input type="text" class="form-control" id="posisi" name="posisi" required>
        </div>
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Batas Lamaran</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>
        <button type="submit" name="tambah" class="btn btn-primary">Tambah Lowongan</button>
    </form>

    <h2 class="mt-5 mb-4">Daftar Lowongan yang Tersedia</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Perusahaan</th>
                    <th>Posisi</th>
                    <th>Lokasi</th>
                    <th>Tanggal Batas Lamaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_select = "SELECT * FROM lowongan";
                $result_select = $conn->query($sql_select);

                if ($result_select->num_rows > 0) {
                    while ($row = $result_select->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["perusahaan"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["posisi"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["lokasi"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["tanggal"]) . "</td>";
                        echo "<td><a href='?hapus=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus lowongan?\")'>Hapus</a></td>";
                        echo "<td><a href='?lamar=" . $row["id"] . "' class='btn btn-success'>Lamar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada lowongan yang tersedia.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    if (isset($_GET['lamar'])) {
        $lowongan_id = $_GET['lamar'];
        ?>
        <h2 class="mt-5 mb-4">Form Lamaran</h2>
        <form method="POST" action="">
            <input type="hidden" name="lowongan_id" value="<?php echo $lowongan_id; ?>">
            <div class="mb-3">
                <label for="nama_pelamar" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama_pelamar" name="nama_pelamar" required>
            </div>
            <div class="mb-3">
                <label for="email_pelamar" class="form-label">Email</label>
                <input type="email" class="form-control" id="email_pelamar" name="email_pelamar" required>
            </div>
            <button type="submit" name="lamar" class="btn btn-primary">Kirim Lamaran</button>
        </form>
        <?php
    }
    ?>
</div>
