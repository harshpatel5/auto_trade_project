<?php
// Start the session to manage user authentication
session_start();

// Database connection
$host = 'localhost';
$dbname = 'auto_trade';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed to connect to the database: " . $e->getMessage();
    exit();
}

// Check if the user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fetch car listings from the database
try {
    $sql = "SELECT id, make, model, year, price, odometer, img1_name, img1_data FROM car_listings";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $carListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Failed to fetch car listings: " . $e->getMessage();
    exit();
}

// Calculate the number of empty boxes needed to fill the row
$totalListings = count($carListings);
$emptyBoxes = (4 - ($totalListings % 4)) % 4; // Ensures row is always full
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Cars</title>
    <link rel="stylesheet" href="buy-style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <h1>Trade Wheels</h1>
            </div>
            <div class="navbar-search">
                <form action="search.php" method="GET">
                    <input type="text" name="query" placeholder="Search cars..." required>
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="navbar-options">
                <a href="home.php">Home</a>
                <a href="active_listings.php">Your Listings</a>
                <a href="sell.php">Sell Your Car</a>
                <a href="contact.php">Contact Us</a>
            </div>
        </div>
    </nav>

    <!-- Listing Section -->
    <section class="listing-container">
        <h2>Available Cars for Sale</h2>
        <div class="cards-container">
            <?php foreach ($carListings as $car): ?>
                <div class="card" onclick="viewDetails(<?= $car['id'] ?>)">
                    <?php if (!empty($car['img1_data'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($car['img1_data']) ?>" alt="<?= htmlspecialchars($car['img1_name']) ?>" class="car-image">
                    <?php else: ?>
                        <img src="default-car.jpg" alt="No Image Available" class="car-image">
                    <?php endif; ?>
                    <div class="card-content">
                        <h3><?= htmlspecialchars($car['year']) ?> <?= htmlspecialchars($car['make']) ?> <?= htmlspecialchars($car['model']) ?></h3>
                        <p class="car-distance"><?= htmlspecialchars(number_format($car['odometer'], 0)) ?> km</p>
                        <p class="car-price">$<?= htmlspecialchars(number_format($car['price'], 2)) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Add empty cards to fill the row -->
            <?php for ($i = 0; $i < $emptyBoxes; $i++): ?>
                <div class="card">
                    <img src="comingsoon.jpg" alt="Coming Soon" class="car-image">
                    <div class="card-content">
                        <h3>Coming Soon</h3>
                        <p class="car-distance">-</p>
                        <p class="car-price">-</p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="sell.php">Sell</a></li>
                <li><a href="active_listings.php">Your Listings</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Contact</h3>
            <p>2000 Simcoe St, Oshawa, ON<br>L1G0C5</p>
            <p>Email: info@autotrade.com</p>
        </div>
        <div class="footer-section">
            <h3>Social Links</h3>
            <p><a href="#">Facebook</a> | <a href="#">Twitter</a> | <a href="#">Instagram</a></p>
        </div>
    </footer>
    <script>
        // Function to redirect to the car details page
        function viewDetails(id) {
            window.location.href = "car_details.php?id=" + id;
        }
    </script>
</body>
</html>
