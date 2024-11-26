<?php
// Namespace internal
\EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
\EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
\EasyRdf\RdfNamespace::set('xsd', 'http://www.w3.org/2001/XMLSchema#');
\EasyRdf\RdfNamespace::set('schema', 'http://schema.org/');

// Namespace untuk data buku novel dari Wikidata
\EasyRdf\RdfNamespace::set('wd', 'http://www.wikidata.org/entity/');
\EasyRdf\RdfNamespace::set('wdt', 'http://www.wikidata.org/prop/direct/');
\EasyRdf\RdfNamespace::set('wikibase', 'http://wikiba.se/ontology#');
\EasyRdf\RdfNamespace::set('p', 'http://www.wikidata.org/prop/');
\EasyRdf\RdfNamespace::set('ps', 'http://www.wikidata.org/prop/statement/');
\EasyRdf\RdfNamespace::set('pq', 'http://www.wikidata.org/prop/qualifier/');

// $jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/negara/sparql');

// Endpoint internal (local Jena Fuseki)
$jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/testbook/sparql');

// Endpoint eksternal (Wikidata)
$external_endpoint = new \EasyRdf\Sparql\Client('https://query.wikidata.org/sparql');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Explorer</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style2.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <!-- Header -->
  <header style="background-color: #8D6E63; color: #fff; padding: 20px; text-align: center;">
    <h1>📚 Library Explorer</h1>
    <p>Discover and explore a vast collection of books</p>
  </header>

  <!-- Search Section -->
  <section style="background-color: #FBE9E7; padding: 50px 20px;">
    <div style="display: flex; align-items: center;">
      <div style="flex: 1; margin-left: 50px;"> 
        <h2>Find Your Next Favorite Book</h2>
        <p style="font-family: 'Open Sans'; font-size: 18px;">
          Search by title, author, genre, or ISBN to explore our library collection.
        </p>

        <!-- Search Form -->
        <form action="buku_details.php" method="GET">
          <div class="search-container">
            <i class="fa fa-search search-icon"></i>
            <input type="text" name="search_term" placeholder="Search for books..." class="search-input">
            <select name="criteria" class="form-select form-select-sm">
              <option value="title">Title</option>
              <option value="author">Author</option>
              <option value="genre">Genre</option>
              <option value="isbn">ISBN</option>
            </select>
            <button type="submit" class="search-button">Search</button>
          </div>
        </form>
      </div>

      <div style="flex: 1; text-align: center;">
        <img src="img/library_books.png" alt="Library" style="width: 400px;">
      </div>
    </div>
  </section>

  <!-- Book Categories -->
  <h2 style="text-align: center; margin-top: 40px;">Explore Popular Book Categories</h2>
  <div class="data-container" style="display: flex; justify-content: center; gap: 20px; margin: 20px;">
    <div class="category">
      <b><a href="hasil.php?category=Fiction" style="color: black; text-decoration: none;"> Fiction <br> 120</a></b>
    </div>
    <div class="category">
      <b><a href="hasil.php?category=Science" style="color: black; text-decoration: none;"> Science <br> 80</a></b>
    </div>
    <div class="category">
      <b><a href="hasil.php?category=Biography" style="color: black; text-decoration: none;"> Biography <br> 60</a></b>
    </div>
    <div class="category">
      <b><a href="hasil.php?category=History" style="color: black; text-decoration: none;"> History <br> 50</a></b>
    </div>
    <div class="category">
      <b><a href="hasil.php?category=Fantasy" style="color: black; text-decoration: none;"> Fantasy <br> 90</a></b>
    </div>
  </div>

  <!-- Footer -->
  <footer class="site-footer" style="background-color: #6D4C41; color: #fff; padding: 20px;">
    <div class="container">
      <div class="row">
        <img src="img/library_logo.png" alt="Library Logo" style="width:100px; height: 50px; margin-right: 20px;">
        <div class="col-sm-12 col-md-6">
          <h6>About Us</h6>
          <div class="info">
            <p>Welcome to Library Explorer!<br> Your gateway to a world of books. Discover, explore, and learn from our vast collection.</p>
          </div>
        </div>

        <div class="col-xs-6 col-md-3">
          <h6>Contact</h6>
          <div class="info">
            <p>Email: support@libraryexplorer.com</p>
            <p>Phone: 08566690428</p>
          </div>
        </div>
      </div>
      <hr>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-8 col-sm-6 col-xs-12">
          <p class="copyright-text" style="text-align: center; color: white;">
            &copy; 2024 Library Explorer. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.js"></script>
</body>
</html>
