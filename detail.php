<?php
require_once 'function.php';

// Ambil parameter 'name' dari URL
$name = $_GET['name'] ?? null;

if (!$name) {
    // Redirect ke halaman hasil jika parameter 'name' tidak ada
    header("Location: hasil.php");
    exit;
}

// Fungsi query SPARQL untuk mengambil detail berdasarkan nama
function getDetailByName($name) {
    return "
        PREFIX rdf: <http://www.w3.org/1999/02/22/rdf-syntax-ns#>
        PREFIX ns0: <https:///schema/Tokoh#>
        SELECT ?personLabel ?birthDate ?birthPlaceLabel ?occupation ?latitude ?longitude ?image
        WHERE {
            ?person ns0:personLabel ?personLabel;
                    ns0:birthDate ?birthDate;
                    ns0:birthPlaceLabel ?birthPlaceLabel;
                    ns0:occupation ?occupation;
                    ns0:latitude ?latitude;
                    ns0:longitude ?longitude;
                    ns0:image ?image.
            FILTER(CONTAINS(LCASE(?personLabel), LCASE(\"$name\")))
        }
        LIMIT 1
    ";
}

try {
    $sparql_query = getDetailByName($name);
    $results = $jena_endpoint->query($sparql_query);

    $tokoh = $results->current();

    if (!$tokoh) {
        header("Location: hasil.php"); // Redirect ke halaman hasil pencarian jika detail tokoh tidak ditemukan
        exit;
    }

    $name = $tokoh->personLabel ?? "Tidak Diketahui";
    $birthDate = formatDate($tokoh->birthDate) ?? "Tidak Diketahui";
    $birthPlace = $tokoh->birthPlaceLabel ?? "Tidak Diketahui";
    $occupation = $tokoh->occupation ?? "Tidak Diketahui";
    $latitude = ($tokoh->latitude) ? $tokoh->latitude->getValue() : 0;
    $longitude = ($tokoh->longitude) ? $tokoh->longitude->getValue() : 0;
    $image = $tokoh->image ?? null;
} catch (Exception $e) {
    header("Location: hasil.php"); // Redirect ke halaman hasil pencarian jika terjadi kesalahan
    exit;
}


function formatDate($date) {
    if (!$date) return null;
    return date('d F Y', strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tokoh: <?= htmlspecialchars($name) ?></title>
    <link rel="stylesheet" href="css/bootstrap.css">
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

        .profile-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border: 5px solid var(--putih-indonesia);
        }

        .info-card {
            background: var(--putih-indonesia);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            font-family: 'Merriweather', serif;
            font-size: 1.3rem;
        }

        /* Styling for the breadcrumb */
    .breadcrumb {
    background-color: #f8f9fa; 
    border-radius: 0.5rem; 
    padding: 10px 20px; 
    margin: 20px 0; 
    font-size: 1.1rem; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }


    .breadcrumb-item {
    display: inline-block;
    font-weight: 500;
    color: #6c757d; 
    } 

/* Hover effect for breadcrumb items */
    .breadcrumb-item a {
    color: #007bff; 
    font-family: 'Merriweather', serif;
    text-decoration: none; 
    transition: color 0.3s ease-in-out; 
    }

    .breadcrumb-item a:hover {
    color: #0056b3; 
}

/* Active breadcrumb item styling */
    .breadcrumb-item.active {
    color: #343a40;
    font-weight: bold; 
    font-family: 'Merriweather', serif;
    font-size: 1.2rem;
}

/* Responsive styling */
    @media (max-width: 768px) {
    .breadcrumb {
        font-size: 1rem; 
        padding: 8px 15px; 
    }
}

        .table-info td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-kembali {
            background-color: var(--merah-indonesia);
            color: var(--putih-indonesia);
            padding: 10px 30px;
            border-radius: 25px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-kembali:hover {
            color: var(--krem);
            background-color: #D10000;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="header-banner text-center mb-5">
        <img src="img/garuda.png" class="ornamen-header ornamen-kiri" alt="Ornamen Garuda">
        <h1 class="display-4 font-weight-bold" style = "font-family: 'Lilita One';" >Detail Tokoh Terkenal Indonesia</h1>
        <img src="img/garuda.png" class="ornamen-header ornamen-kanan" alt="Ornamen Garuda">
    </div>

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white shadow-sm">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"> <a href="<?= isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : 'hasil.php' ?>">Hasil Pencarian</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($name) ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-4">
                <div class="info-card text-center">
                    <?php if ($image): ?>
                        <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($name) ?>" class="profile-image">
                    <?php else: ?>
                        <div class="alert alert-info">Gambar tidak tersedia</div>
                    <?php endif; ?>
                    <h2 class="mt-4 mb-0"><?= htmlspecialchars($name) ?></h2>
                    <p class="text-muted"><?= htmlspecialchars($occupation) ?></p>
                </div>
            </div>

            <div class="col-md-8">
                <div class="info-card">
                    <h3 class="border-bottom pb-3 mb-4">Informasi Pribadi</h3>
                    <table class="table-info w-100">
                        <tr>
                            <td width="30%"><strong>Nama Lengkap</strong></td>
                            <td><?= htmlspecialchars($name) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Lahir</strong></td>
                            <td><?= htmlspecialchars($birthDate) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tempat Lahir</strong></td>
                            <td><?= htmlspecialchars($birthPlace) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Pekerjaan</strong></td>
                            <td><?= htmlspecialchars($occupation) ?></td>
                        </tr>
                    </table>
                </div>

                <div class="info-card map-container">
                    <h3 class="border-bottom pb-3 mb-4">Lokasi</h3>
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>

        <div class="text-center my-5">
    <a href="<?= isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : 'hasil.php' ?>" class="btn btn-kembali">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Hasil Pencarian
    </a>
</div>

    </div>

    <script src="https://cdn.jsdelivr.net/gh/somanchiu/Keyless-Google-Maps-API@v6.8/mapsJavaScriptAPI.js"></script>    <script>
        function initMap() {
            const location = {
                lat: <?= $latitude ?>,
                lng: <?= $longitude ?>
            };
            
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: location,
                mapTypeId: 'terrain'
            });

            new google.maps.Marker({
                position: location,
                map: map,
                title: "Lokasi <?= htmlspecialchars($name) ?>"
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>