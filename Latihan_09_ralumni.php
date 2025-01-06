<h3>DAFTAR ALUMNI</h3>
<hr>

<!-- Form Pencarian -->
<form method="GET" action="" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari nama, jurusan, atau tahun lulus" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button class="btn btn-primary" type="submit">Cari</button>
    </div>
</form>

<a href="?menu=calumni" class="btn btn-primary mb-3">Tambah</a>

<?php
include 'Latihan_09_config.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM alumni";
if (!empty($search)) {
    $sql .= " WHERE nama LIKE '%$search%' OR jurusan LIKE '%$search%' OR tahun_lulus LIKE '%$search%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'><tr><th>ID</th><th>Nama</th><th>Tahun Lulus</th><th>Jurusan</th><th>Foto</th><th>Aksi</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["nama"] . "</td>
                <td>" . $row["tahun_lulus"] . "</td>
                <td>" . $row["jurusan"] . "</td>
                <td><img src='" . $row["foto"] . "' width='50'></td>
                <td>
                    <a class='btn btn-warning' href='Latihan_09_index.php?menu=ualumni&id=" . $row["id"] . "'>Edit</a>
                    |
                    <a class='btn btn-danger' href='Latihan_09_dalumni.php?id=" . $row["id"] . "'>Hapus</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='alert alert-warning'>Tidak ada data ditemukan.</div>";
}

$conn->close();
?>
