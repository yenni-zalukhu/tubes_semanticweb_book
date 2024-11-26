<?php
error_reporting(E_ALL & ~E_DEPRECATED && ~E_STRICT);

require_once realpath(__DIR__ . '/.') . "/vendor/autoload.php";

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

$jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/testbook/sparql');

$isError = false;

// Menangani pencarian berdasarkan kriteria user
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['criteria']) && isset($_GET['search_term'])) {
    $criteria = $_GET['criteria'];
    $search_term = trim($_GET['search_term']);

    $sparql_query = '';
    switch ($criteria) {
        case 'judul': // Pencarian berdasarkan judul buku
            $sparql_query = '
            SELECT ?buku ?judul ?penulis ?genre ?tahun ?cover WHERE {
                ?buku dcterms:title ?judul.
                OPTIONAL { ?buku dcterms:creator ?penulis. }
                OPTIONAL { ?buku schema:genre ?genre. }
                OPTIONAL { ?buku dcterms:date ?tahun. }
                OPTIONAL { ?buku foaf:depiction ?cover. }
                FILTER(CONTAINS(LCASE(STR(?judul)), "' . strtolower($search_term) . '"))
            } ORDER BY ASC(?judul)
            ';
            break;

        case 'penulis': // Pencarian berdasarkan penulis
            $sparql_query = '
            SELECT ?buku ?judul ?penulis ?genre ?tahun ?cover WHERE {
                ?buku dcterms:title ?judul.
                ?buku dcterms:creator ?penulis.
                OPTIONAL { ?buku schema:genre ?genre. }
                OPTIONAL { ?buku dcterms:date ?tahun. }
                OPTIONAL { ?buku foaf:depiction ?cover. }
                FILTER(CONTAINS(LCASE(STR(?penulis)), "' . strtolower($search_term) . '"))
            } ORDER BY ASC(?judul)
            ';
            break;

        case 'genre': // Pencarian berdasarkan genre
            $sparql_query = '
            SELECT ?buku ?judul ?penulis ?genre ?tahun ?cover WHERE {
                ?buku dcterms:title ?judul.
                ?buku schema:genre ?genre.
                OPTIONAL { ?buku dcterms:creator ?penulis. }
                OPTIONAL { ?buku dcterms:date ?tahun. }
                OPTIONAL { ?buku foaf:depiction ?cover. }
                FILTER(CONTAINS(LCASE(STR(?genre)), "' . strtolower($search_term) . '"))
            } ORDER BY ASC(?judul)
            ';
            break;

        default:
            $isError = true;
            $error_message = "Kriteria pencarian tidak valid.";
            break;
    }

    if (!$isError) {
        try {
            $results = $jena_endpoint->query($sparql_query);
            $books = [];
            foreach ($results as $row) {
                $books[] = [
                    'judul' => (string)$row->judul,
                    'penulis' => (string)($row->penulis ?? 'Tidak Diketahui'),
                    'genre' => (string)($row->genre ?? 'Tidak Diketahui'),
                    'tahun' => (string)($row->tahun ?? 'Tidak Diketahui'),
                    'cover' => (string)($row->cover ?? 'default_cover.jpg'),
                ];
            }
            if (empty($books)) {
                $isError = true;
                $error_message = "Tidak ditemukan buku sesuai kriteria.";
            }
        } catch (Exception $e) {
            $isError = true;
            $error_message = "Terjadi kesalahan saat mengambil data: " . $e->getMessage();
        }
    }
}

if ($isError) {
    echo "<script>alert('{$error_message}');</script>";
} else {
    // Mengirim data hasil pencarian ke front-end
    header('Content-Type: application/json');
    echo json_encode(['books' => $books]);
    exit;
}
?>
