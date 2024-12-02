<?php

// Mengimpor library EasyRdf dan konfigurasi namespace
require_once realpath(__DIR__ . '/vendor/autoload.php');

\EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
\EasyRdf\RdfNamespace::set('ns0', 'https:///schema/Tokoh#');

// Konfigurasi SPARQL Endpoint
$jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/tokoh_indonesia/sparql');

// Fungsi untuk pencarian berdasarkan nama tokoh
function searchByName($searchTerm) {
    return "
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX ns0: <https:///schema/Tokoh#>
        
        SELECT ?person ?name ?birthDate ?birthPlace ?occupation ?latitude ?longitude ?image
        WHERE {
          ?person ns0:personLabel ?name ;
                  ns0:birthDate ?birthDate ;
                  ns0:birthPlaceLabel ?birthPlace ;
                  ns0:occupation ?occupation ;
                  ns0:latitude ?latitude ;
                  ns0:longitude ?longitude .
          OPTIONAL { ?person ns0:image ?image . }
          FILTER (CONTAINS(LCASE(?name), LCASE(\"$searchTerm\")))
        }
    ";
}

// Fungsi untuk pencarian berdasarkan pekerjaan tokoh
function searchByOccupation($occupation) {
    return "
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX ns0: <https:///schema/Tokoh#>
        
        SELECT ?person ?name ?birthDate ?birthPlace ?occupation ?latitude ?longitude ?image
        WHERE {
          ?person ns0:personLabel ?name ;
                  ns0:birthDate ?birthDate ;
                  ns0:birthPlaceLabel ?birthPlace ;
                  ns0:occupation ?occupation ;
                  ns0:latitude ?latitude ;
                  ns0:longitude ?longitude .
          OPTIONAL { ?person ns0:image ?image . }
          FILTER (CONTAINS(LCASE(?occupation), LCASE(\"$occupation\")))
        }
    ";
}


// Fungsi untuk pencarian berdasarkan kategori pekerjaan (Pemain Bulutangkis)
function searchByOccupationCategory($category) {
  // Sanitasi input kategori pekerjaan untuk menghindari masalah keamanan
  $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');

  // Menyusun query untuk mencari tokoh berdasarkan pekerjaan
  return "
      PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
      PREFIX ns0: <https:///schema/Tokoh#>

      SELECT ?person ?name ?birthDate ?birthPlace ?occupation ?latitude ?longitude ?image
      WHERE {
        ?person ns0:personLabel ?name ;
                ns0:birthDate ?birthDate ;
                ns0:birthPlaceLabel ?birthPlace ;
                ns0:occupation ?occupation ;
                ns0:latitude ?latitude ;
                ns0:longitude ?longitude . 
        OPTIONAL { ?person ns0:image ?image . }
        FILTER (CONTAINS(LCASE(?occupation), LCASE(\"$category\")))
      }
  ";
}



// Fungsi untuk pencarian berdasarkan tempat lahir tokoh
function searchByBirthPlace($birthPlace) {
    return "
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX ns0: <https:///schema/Tokoh#>
        
        SELECT ?person ?name ?birthDate ?birthPlace ?occupation ?latitude ?longitude ?image
        WHERE {
          ?person ns0:personLabel ?name ;
                  ns0:birthDate ?birthDate ;
                  ns0:birthPlaceLabel ?birthPlace ;
                  ns0:occupation ?occupation ;
                  ns0:latitude ?latitude ;
                  ns0:longitude ?longitude .
          OPTIONAL { ?person ns0:image ?image . }
          FILTER (CONTAINS(LCASE(?birthPlace), LCASE(\"$birthPlace\")))
        }
    ";
}

?>
