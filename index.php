<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

require_once realpath(__DIR__ . '/.') . "/vendor/autoload.php";

// Namespace internal
\EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
\EasyRdf\RdfNamespace::set('ns0', 'https:///schema/Tokoh#');

// Endpoint internal (local Jena Fuseki)
$jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/tokoh_indonesia/sparql');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Tokoh Terkenal Indonesia</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    /* Global styling */
    body {
        font-family: 'Merriweather', serif;
        margin: 0;
        padding: 0;
        background-color: #FFF5F3;
    }

    /* Header styling */
    header {
        background-color: #FF0000;
        color: #FFFFFF;
        padding: 20px;
        text-align: center;
        font-family: 'Open Sans', sans-serif;
    }

    header h1 {
        font-size: 3rem;
        font-weight: bold;
    }

    header p {
        font-size: 1.2rem;
    }

    /* Search section styling */
    .search-section {
        position: relative;
        padding: 80px 20px 50px; /* Add padding for better spacing */
        background-color: #FFEFEB;
        text-align: center;
    }

    /* Garuda image styling using ::before pseudo-element */
    .search-section::before {
        content: "";
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 350px;
        height: 320px;
        background: url('img/garuda.png') no-repeat center;
        background-size: contain;
        opacity: 0.3; /* Make the Garuda semi-transparent */
        z-index: 1; /* Send it to the back */
    }

    .search-section h2 {
        color: #FF0000;
        font-size: 2.5rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 2; /* Ensure the text stays above the image */
    }

    .search-section p {
        font-size: 1.2rem;
        color: #333333;
        position: relative;
        z-index: 2; /* Ensure the text stays above the image */
    }

    .search-container {
        position: relative;
        z-index: 2; /* Ensure the search box stays above the image */
        margin: 20px auto 0;
        max-width: 600px;
        display: flex;
        gap: 10px;
    }

    .search-container input {
        flex: 1;
        padding: 10px;
        font-size: 1rem;
        border: 2px solid #FF0000;
        border-radius: 5px;
    }

    .search-container button {
        padding: 10px 20px;
        font-size: 1rem;
        background-color: #FF0000;
        color: #FFFFFF;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .search-container button:hover {
        background-color: #CC0000;
    }

    /* Categories section styling */
    .categories {
        text-align: center;
        margin: 40px 0;
    }

    .categories h3 {
        font-size: 2rem;
        color: #FF0000;
    }

    .categories .category-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .categories .category {
        background-color: #FFEFEB;
        border: 2px solid #FF0000;
        border-radius: 10px;
        padding: 20px;
        width: 200px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: bold;
        color: #333333;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .categories .category:hover {
        background-color: #FF0000;
        color: #FFFFFF;
    }

    /* Footer styling */
    footer {
        background-color: #FF0000;
        color: #FFFFFF;
        text-align: center;
        padding: 20px;
    }

    footer p {
        margin: 0;
    }
</style>
</head>
<body>
  <!-- Header -->
  <header>
    <h1> Tokoh Terkenal Indonesia </h1>
    <p>Mengenal Lebih Dekat Para Tokoh Inspiratif Indonesia</p>
  </header>

  <!-- Search Section -->
  <section class="search-section">
    <h2>Cari Tokoh Inspiratif</h2>
    <p>Masukkan nama tokoh yang ingin kamu cari</p>
    <form action="hasil.php" method="GET" id="searchForm">
        <div class="search-container">
            <input type="text" name="search_term" id="searchTerm" placeholder="Masukkan nama tokoh..." required>
            <button type="submit" id="searchButton" disabled>Cari</button>
        </div>
        <p id="error-message" style="color: red; display: none;">Kolom wajib diisi!</p>
    </form>
</section>

<!-- Kategori Pekerjaan -->
<section class="categories">
    <h3>Kategori Tokoh</h3>
    <div class="category-container">
        <div class="category"><a href="hasil.php?category=politician" style="text-decoration: none; color: inherit;">Politikus</a></div>
        <div class="category"><a href="hasil.php?category=actor" style="text-decoration: none; color: inherit;">Aktor</a></div>
        <div class="category"><a href="hasil.php?category=writer" style="text-decoration: none; color: inherit;">Penulis</a></div>
        <div class="category"><a href="hasil.php?category=badminton player" style="text-decoration: none; color: inherit;">Pemain Bulutangkis</a></div>
        <div class="category"><a href="hasil.php?category=military" style="text-decoration: none; color: inherit;">Personel Militer</a></div>
    </div>
</section>



  <!-- Footer -->
  <footer>
    <p>&copy; 2024 Tokoh Terkenal Indonesia. Dibangun dengan rasa cinta tanah air.</p>
  </footer>

  <script>
    // Mendapatkan elemen input dan tombol
    const searchInput = document.getElementById('searchTerm');
    const searchButton = document.getElementById('searchButton');
    const errorMessage = document.getElementById('error-message');

    // Fungsi untuk memeriksa apakah kolom pencarian kosong atau tidak
    searchInput.addEventListener('input', function() {
        if (searchInput.value.trim() === '') {
            // Nonaktifkan tombol jika kolom kosong
            searchButton.disabled = true;
            errorMessage.style.display = 'block'; // Tampilkan pesan error
        } else {
            // Aktifkan tombol jika kolom terisi
            searchButton.disabled = false;
            errorMessage.style.display = 'none'; // Sembunyikan pesan error
        }
    });

    // Validasi jika form disubmit tanpa kolom pencarian
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        if (searchInput.value.trim() === '') {
            event.preventDefault(); // Hentikan pengiriman form
            errorMessage.style.display = 'block'; // Tampilkan pesan error
        }
    });
</script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.js"></script>
</body>
</html>
