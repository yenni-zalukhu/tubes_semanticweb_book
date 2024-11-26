<?php
require_once 'function.php';
include 'function.php';
?>

<?php
require_once realpath(__DIR__ . '/.') . "/vendor/autoload.php";

// Setup RDF namespace
\EasyRdf\RdfNamespace::set('schema', 'http://schema.org/');

// RDF file location
$rdf_file = 'data/books.rdf'; // Lokasi file RDF Anda
$graph = new \EasyRdf\Graph();
$graph->parseFile($rdf_file, 'rdfxml');

// Get search term and criteria from URL
$search_term = isset($_GET['search_term']) ? strtolower($_GET['search_term']) : '';
$criteria = isset($_GET['criteria']) ? $_GET['criteria'] : 'title';

// Map criteria to RDF property
$criteria_map = [
    'title' => 'schema:name',
    'author' => 'schema:author',
    'genre' => 'schema:genre',
    'isbn' => 'schema:isbn'
];
$criteria_property = isset($criteria_map[$criteria]) ? $criteria_map[$criteria] : 'schema:name';

// Search logic
$results = [];
if (!empty($search_term)) {
    foreach ($graph->allOfType('schema:Book') as $book) {
        $property_value = strtolower($book->get($criteria_property));
        if (strpos($property_value, $search_term) !== false) {
            $results[] = $book;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Explorer - Search Results</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header style="background-color: #8D6E63; color: #fff; padding: 20px; text-align: center;">
        <h1>📚 Library Explorer</h1>
        <p>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</p>
    </header>

    <div class="container" style="margin-top: 30px;">
        <?php if (!empty($results)): ?>
            <div class="row">
                <?php foreach ($results as $book): ?>
                    <div class="col-md-4">
                        <div class="card" style="margin-bottom: 20px;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $book->get('schema:name'); ?></h5>
                                <p><strong>Author:</strong> <?php echo $book->get('schema:author'); ?></p>
                                <p><strong>Published:</strong> <?php echo $book->get('schema:datePublished'); ?></p>
                                <p><strong>ISBN:</strong> <?php echo $book->get('schema:isbn'); ?></p>
                                <p><strong>Pages:</strong> <?php echo $book->get('schema:numberOfPages'); ?></p>
                                <a href="#" class="btn btn-primary">Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found for "<?php echo htmlspecialchars($search_term); ?>"</p>
        <?php endif; ?>
    </div>

    <footer class="site-footer" style="background-color: #6D4C41; color: #fff; padding: 20px; text-align: center;">
        <p>&copy; 2024 Library Explorer. All rights reserved.</p>
    </footer>
</body>
</html>
