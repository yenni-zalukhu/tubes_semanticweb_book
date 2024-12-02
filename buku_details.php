<?php
require_once 'function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Digital Resource</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style2.css">
    <link href='https://fonts.googleapis.com/css?family=Lilita One' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Solway' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places"></script>
    <script>
        function initMap() {
            // HTML element to display the map
            const mapContainer = document.getElementById('googleMap');
            const map = new google.maps.Map(mapContainer, {
                center: { lat: -6.200000, lng: 106.816666 }, // Default to Jakarta
                zoom: 13
            });

            // Try to get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Center map on user's location
                    map.setCenter(userLocation);

                    // Add marker for user's location
                    new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "Your Location"
                    });

                    // Search for bookstores nearby
                    const service = new google.maps.places.PlacesService(map);
                    service.textSearch({
                        location: userLocation,
                        radius: 5000, // 5 km
                        query: 'bookstore'
                    }, (results, status) => {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            results.forEach((place) => {
                                new google.maps.Marker({
                                    position: place.geometry.location,
                                    map: map,
                                    title: place.name
                                });
                            });
                        } else {
                            console.error('Error finding bookstores:', status);
                        }
                    });
                }, (error) => {
                    console.error('Error getting location:', error);
                });
            } else {
                console.warn('Geolocation is not supported by this browser.');
            }
        }
    </script>
</head>
<body onload="initMap()">
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
            <header style="background-color: #8D6E63; color: #fff; padding: 20px; text-align: center;">
    <h1>📚 Library Digital Resource</h1>
    <p>Discover and explore a vast collection of books</p>
  </header>
                <!-- <img src="img/1-removebg-preview (1).png" alt="logo" width="190px"> -->
            </a>
            <form action="details2.php" method="GET">
                <div class="search-container">
                    <i class="fa fa-search search-icon"></i>
                    <input type="text" name="search_term" placeholder="Search for a book..." class="search-input">
                    <button type="submit" class="search-button">Go</button>
                </div>
            </form>
        </div>
    </nav>

    <section class="search-result">
        <h3 class="search-title"><b>Find Bookstores Near You</b></h3>
        <hr>
    </section>

    <div class="container">
        <div id="googleMap" style="height: 500px; width: 100%;"></div>
    </div>

    <footer class="site-footer" style="margin-top: 200px;">
        <div class="container">
            <p class="copyright-text" style="text-align: center;">
                &copy; 2024 Library Digital Resource. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
