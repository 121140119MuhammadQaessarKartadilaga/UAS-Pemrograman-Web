<?php
include 'koneksi.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $nama_prodi = $_POST['nama_prodi'];

    if (empty($nim) || empty($nama) || empty($nama_prodi)) {
        $error = "Harap isi semua field!";
    } else {
        $checkNIM = "SELECT * FROM mahasiswa WHERE NIM = '$nim'";
        $resultNIM = $conn->query($checkNIM);
        if ($resultNIM->num_rows > 0) {
            $error = "NIM sudah ada dalam database!";
        }

        $getKodeProdi = "SELECT kode_prodi FROM prodi WHERE nama_prodi = '$nama_prodi'";
        $resultKodeProdi = $conn->query($getKodeProdi);
        if ($resultKodeProdi->num_rows == 0) {
            $error = "Nama Prodi tidak ditemukan!";
        }

        if (empty($error)) {

                $row = $resultKodeProdi->fetch_assoc();
                $kode_prodi = $row['kode_prodi'];
        
                $sql = "INSERT INTO mahasiswa (NIM, Nama, Kode_Prodi) VALUES ('$nim', '$nama', '$kode_prodi')";
                if ($conn->query($sql) === TRUE) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
        
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Data Mahasiswa</title>
    <link rel="stylesheet" href="./style.css" />
    <script>
        function validateForm() {
            var nim = document.forms["mahasiswaForm"]["nim"].value;
            var nama = document.forms["mahasiswaForm"]["nama"].value;
            var nama_prodi = document.forms["mahasiswaForm"]["nama_prodi"].value;

            if (nim == "" || nama == "" || nama_prodi == "") {
                alert("All fields must be filled out");
                return false;
            }
        }
    </script>
  </head>
<body>
<div class = "container">
    <h2>Tambah Data Mahasiswa</h2>

    <form name="mahasiswaForm" method="POST" action="" onsubmit="return validateForm()">
            NIM: <input type="text" name="nim" onfocus="this.value=''" value="Masukkan NIM" onblur="if(this.value=='') this.value='Masukkan NIM'"><br>
            Nama: <input type="text" name="nama" onfocus="this.value=''" value="Masukkan Nama" onblur="if(this.value=='') this.value='Masukkan Nama'"><br>
            Nama Prodi: <input type="text" name="nama_prodi" onfocus="this.value=''" value="Masukkan Nama Prodi" onblur="if(this.value=='') this.value='Masukkan Nama Prodi'"><br>
            <input type="submit" value="Tambah">
        </form>
    <?php
    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>
    </div>
</body>
</html>