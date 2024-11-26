<?php
require_once __DIR__."/vendor/autoload.php"; // Include autoloader dari Composer

function generateRdfGraph($novelData)
{
    // Inisialisasi Graph RDF
    $graph = new \EasyRdf\Graph();

    foreach ($novelData as $novel) {
        $novelResource = $graph->newBNode('schema:Book'); // Node untuk novel
        
        $novelResource->addLiteral('schema:name', $novel['title']); // Judul novel
        $novelResource->addLiteral('schema:author', $novel['author']); // Pengarang
        $novelResource->addLiteral('schema:datePublished', $novel['year']); // Tahun terbit
        $novelResource->addLiteral('schema:genre', $novel['genre']); // Genre
        $novelResource->addLiteral('schema:publisher', $novel['publisher']); // Penerbit

        if (!empty($novel['description'])) {
            $novelResource->addLiteral('schema:description', $novel['description']); // Deskripsi
        }
    }

    return $graph->serialise('rdfxml'); // Serialisasi ke RDF/XML
}

// Contoh data novel
$novelData = [
    [
        'title' => 'Pride and Prejudice',
        'author' => 'Jane Austen',
        'year' => '1813',
        'genre' => 'Romance',
        'publisher' => 'T. Egerton',
        'description' => 'A classic novel about love and society.'
    ],
    [
        'title' => '1984',
        'author' => 'George Orwell',
        'year' => '1949',
        'genre' => 'Dystopian',
        'publisher' => 'Secker & Warburg',
        'description' => 'A novel about surveillance and totalitarianism.'
    ]
];

// Cetak output RDF
header("Content-Type: application/rdf+xml");
echo generateRdfGraph($novelData);
?>

