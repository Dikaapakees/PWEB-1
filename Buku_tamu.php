<h3>Form Buku Tamu</h3>
<hr>

<?php
include 'Latihan_09_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitorName = $_POST['visitor_name'];
    $visitorEmail = $_POST['visitor_email'];
    $message = $_POST['message'];
    $prodi = $_POST['prodi'];
    $angkatan = $_POST['angkatan'];

    $sql = "INSERT INTO bukuutamu (name, email, message, prodi, angkatan) 
            VALUES ('$visitorName', '$visitorEmail', '$message', '$prodi', '$angkatan')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Pesan berhasil ditambahkan ke buku tamu!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Isi Buku Tamu</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="visitor_name" class="form-label">Nama Anda</label>
            <input type="text" class="form-control" id="visitor_name" name="visitor_name" required>
        </div>

        <div class="mb-3">
            <label for="visitor_email" class="form-label">Email Anda</label>
            <input type="email" class="form-control" id="visitor_email" name="visitor_email" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Pesan</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="prodi" class="form-label">Program Studi</label>
            <input type="text" class="form-control" id="prodi" name="prodi" required>
        </div>

        <div class="mb-3">
            <label for="angkatan" class="form-label">Angkatan</label>
            <input type="number" class="form-control" id="angkatan" name="angkatan" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
    </form>

    <h2 class="mt-5 mb-4">Data Buku Tamu</h2> <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th>Prodi</th>
                <th>Angkatan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_select = "SELECT name, email, message, prodi, angkatan FROM bukuutamu";
            $result_select = $conn->query($sql_select);

            if ($result_select->num_rows > 0) {
                while ($row = $result_select->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["message"] . "</td>";
                    echo "<td>" . $row["prodi"] . "</td>";
                    echo "<td>" . $row["angkatan"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data buku tamu.</td></tr>";
            }
            $conn->close(); // Tutup koneksi setelah selesai digunakan
            ?>
        </tbody>
    </table>
    </div>
</div>
