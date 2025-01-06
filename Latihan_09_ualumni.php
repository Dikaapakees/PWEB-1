<?php
include 'Latihan_09_config.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "SELECT * FROM alumni WHERE id=$id";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    }else{
        echo "<div class = 'alert alert-danger'>Data tidak ditemukan.</div>";
    }
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $jurusan = $_POST['jurusan'];

    if(isset($_FILES['foto']) && $_FILES['foto']['error']==0){
        $target_dir = "";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false){
            $uploadOk = 1;
        }else{
            echo"<div class = 'alert alert-danger'>File buka gambar.</div>";
            $uploadOk = 0;
        }

        if($_FILES["foto"]["size"] > 5000000){
            echo "<div class='alert alert-danger'>Ukuran file terlalu besar.</div>";
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
            echo "<div class='alert alert-danger'>Hanya file JPG, JPEG, PNG & GIF yang di izinkan.</div>";
            $uploadOk = 0;
        }

        if($uploadOk==0){
            echo "<div class='alert alert-danger'>File tidak berhasil diunggah.</div>";
        }else{
            if(move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)){
              
                $sql = "UPDATE alumni SET nama = '$nama', tahun_lulus= '$tahun_lulus', jurusan= '$jurusan', foto= '$target_file' WHERE id=$id";
                if($conn->query($sql)=== TRUE){
                    echo "<div class='alert alert-success'>Data berhasil perbarui.</div>";
                }else{
                    echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>Terjadi Kesalahan saat mengunggah file.</div>";
            }
        }
    }else{
    
        $sql = "UPDATE alumni SET nama= '$nama', tahun_lulus = '$tahun_lulus', jurusan = '$jurusan' WHERE id = $id";
        if($conn->query($sql)=== TRUE){
            echo "<div class='alert alert-success'>Data berhasil perbarui.</div>";
        }else{
            echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
    $conn->close();
}
?>

<div class= "container mt-5">
    <h2 class= "mb-4">Update Data Alumni</h2>
    <!-- form update data -->
     <?php if(isset($row)){?>
        <form method = "POST" action= "" enctype= "multipart/form-data">
            <input type= "hidden" name="id" value = "<?php echo $row['id'];?>">
        <div class = "mb-3">
            <label for="nama" class = "form-label">Nama</label>
            <input type= "text" class= "form-control" id = "nama" name = "nama" value= "<?php echo $row['nama'];?>" required>
        </div>
        <div class = "mb-3">
            <label for="tahun_lulus" class = "form-label">Tahun Lulus</label>
            <input type= "number" class= "form-control" id = "tahun_lulus" name = "tahun_lulus" value= "<?php echo $row['tahun_lulus'];?>" required>
        </div>
        <div class = "mb-3">
            <label for="jurusan" class = "form-label">Jurusan</label>
            <input type= "text" class= "form-control" id = "jurusan" name = "jurusan" value= "<?php echo $row['jurusan'];?>" required>
        </div>
        <div class = "mb-3">
            <label for="foto" class = "form-label">Foto (biarkan kosong jika tidak diubah)</label>
            <input type= "file" class= "form-control" id = "foto" name = "foto" >
        </div>
        <button type="submit" class="btn btn-primary">Perbarui Data</button>
     </form>
     <?php }?>
</div>
