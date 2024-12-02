<?php
require_once 'function.php';

$isError = false;
$data = [];

// Ambil parameter pencarian
if (isset($_GET['search_term'])) {
    $searchTerm = $_GET['search_term'];
    $sparql_query = searchByName($searchTerm);
} elseif (isset($_GET['occupation'])) {
    $occupation = $_GET['occupation'];
    $sparql_query = searchByOccupation($occupation);
} elseif (isset($_GET['birth_place'])) {
    $birthPlace = $_GET['birth_place'];
    $sparql_query = searchByBirthPlace($birthPlace);
} elseif  (isset($_GET['category'])) {
$category = $_GET['category'];
// Pencarian berdasarkan kategori pekerjaan
$sparql_query = searchByOccupationCategory($category);
} 
else {
    $isError = true;
    $error_message = "Kriteria pencarian tidak valid.";
}

// Eksekusi query dan proses hasilnya
if (!$isError) {
    try {
        $results = $jena_endpoint->query($sparql_query);

        foreach ($results as $row) {
            $data[] = [
                'name' => (string)($row->name ?? 'Tidak Diketahui'),
                'birthDate' => (string)($row->birthDate ?? 'Tidak Diketahui'),
                'birthPlace' => (string)($row->birthPlace ?? 'Tidak Diketahui'),
                'occupation' => (string)($row->occupation ?? 'Tidak Diketahui'),
                'latitude' => (string)($row->latitude ?? null),
                'longitude' => (string)($row->longitude ?? null),
                'image' => (string)($row->image ?? null),
            ];
        }

        if (empty($data)) {  
            $isError = true;
            $error_message = "Tidak ditemukan data sesuai kriteria.";
        }
    } catch (Exception $e) {
       
        $isError = true;
        $error_message = "Terjadi kesalahan saat mengambil data: " . $e->getMessage();
    }
    
    if ($isError) {

        echo "<script>
                alert('{$error_message}');
                window.location.href = 'index.php';  // Mengarahkan ke halaman index.php
              </script>";
        exit;  
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - Tokoh Terkenal</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<div class="header-banner text-center mb-5">
        <img src="img/garuda-pancasila.png"  class="ornamen-header ornamen-kiri" alt="Ornamen Garuda">
        <h1 class="display-4 font-weight-bold" style = "font-family: 'Lilita One';" >Hasil Pencarian Tokoh Terkenal</h1>
        <img src="img/garuda-pancasila.png"  class="ornamen-header ornamen-kanan" alt="Ornamen Garuda">
    </div>

<style>
        :root {
            --merah-indonesia: #FF0000;
            --putih-indonesia: #FFFFFF;
            --krem: #F4F4F4;
        }

        body {
        font-family: 'Merriweather', serif;
        margin-bottom: 30px; 
        padding: 0;
        background-color: #FFF5F3;
        font-size: 1rem;
    }

        .header-banner {
            background-color: var(--merah-indonesia);
            color: var(--putih-indonesia);
            padding: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .header-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('img/batik.png') repeat;
            opacity: 0.1;
    
        }

        .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-top: 20px;
    }


    h3 {
        font-size: 2.3rem;
        font-weight: bold; 
        text-align: center; 
        margin-bottom: 5px; 
    }
        .ornamen-header {
            position: absolute;
            width: 100px;
            height: 100px;
        }

        .ornamen-kiri {
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .ornamen-kanan {
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }


    .btn-kembali {
        background-color: var(--merah-indonesia);
        color: var(--putih-indonesia);
        padding: 10px 30px;
        border-radius: 25px;
        border: none;
        transition: all 0.3s ease;
        display: inline-block;
        text-align: center;
        font-size: 1rem;
        text-decoration: none;
    }

    .btn-kembali-container {
        margin-top: 30px; 
        margin-bottom: 30px; 

    }

.btn-kembali:hover {
    color: var(--krem);
    background-color: #D10000;
    transform: translateY(-2px);
}

 /* Styling for the table */
 table {
        width: 100%;
        border-collapse: collapse; /* Ensures no space between borders */
        margin-top: 20px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border: 1px solid #ddd; /* Adds borders around table cells */
    }

    th {
        background-color: var(--merah-indonesia);
        color: #000000;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9; /* Zebra striping for rows */
    }

    tr:hover {
        background-color: #f1f1f1; /* Light hover effect for rows */
    }

    /* Styling for the table headings */
    th {
        font-size: 23px;
    }

    /* Styling for table data */
    td {
        font-size: 20px;
    }

</style>
<section>
    <div class="container mt-5">
        <?php if (!empty($data)): ?>
            <h3>Daftar Tokoh</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Tempat Lahir</th>
                        <th>Pekerjaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $index => $tokoh): ?>
                        <tr>
                            <td><?= htmlspecialchars($tokoh['name']) ?></td>
                            <td><?= htmlspecialchars($tokoh['birthDate']) ?></td>
                            <td><?= htmlspecialchars($tokoh['birthPlace']) ?></td>
                            <td><?= htmlspecialchars($tokoh['occupation']) ?></td>
                            <td>
                            <a href="detail.php?name=<?= urlencode($tokoh['name']) ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Tidak ada hasil ditemukan untuk kriteria pencarian Anda.</p>
        <?php endif; ?>

<!-- Tombol Kembali ke Halaman Awal -->
<div class="text-center mt-4">
    <a href="index.php" class="btn-kembali">
        Kembali ke Halaman Awal
    </a>
</div>

    </div>
</section>
</body>
</html>
