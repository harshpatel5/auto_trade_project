<?php
session_start();

// Function to check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

// Handle logout if requested
if (isset($_GET['logout'])) {
    session_unset(); // Clears all session variables
    session_destroy(); // Destroys the session
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=auto_trade", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the total number of cars in the database
    $carCountQuery = "SELECT COUNT(*) FROM car_listings";
    $carCountStmt = $pdo->prepare($carCountQuery);
    $carCountStmt->execute();
    $totalCars = (int)$carCountStmt->fetchColumn();

    // Determine how many cars to show: 4, 8, or 12
    $limit = 4; // Default to 4 cars
    if ($totalCars > 4 && $totalCars <= 8) {
        $limit = 8;
    } elseif ($totalCars > 8) {
        $limit = 12;
    }

    // Fetch random cars from the database based on the determined limit
    $randomCarsQuery = "SELECT * FROM car_listings ORDER BY RAND() LIMIT :limit";
    $randomCarsStmt = $pdo->prepare($randomCarsQuery);
    $randomCarsStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $randomCarsStmt->execute();
    $randomCars = $randomCarsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Failed to connect to the database: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Wheels</title>
    <link rel="stylesheet" href="homestyle.css">
    <script>
        // Logout function
        function logout() {
            window.location.href = 'index.php?logout=true'; // Redirect to logout URL
        }
    </script>
</head>
<body>

<!-- Top Notification Bar -->
<div class="top-bar">
    <p>New deals every week. <a href="#">Shop deals.</a></p>
</div>

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
            <a href="active_listings.php">Your Listings</a>
            <a href="buy.php">Shop Cars</a>
            <a href="sell.php">Sell Your Car</a>
            <a href="contact.php">Contact Us</a>
            <?php if (isUserLoggedIn()) : ?>
                <a href="javascript:void(0);" onclick="logout()">Logout</a>
            <?php else : ?>
                <a href="login.php">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-background">
        <img src="3.jpg" alt="Background Image">
    </div>
    <div class="hero-content">
        <div class="button-card">
            <h3>Shop cars</h3>
            <p>Hundreds of vehicles to fit all tastes and budgets</p>
            <a href="buy.php">
                <div class="arrow-button">→</div>
            </a>
        </div>
        <div class="button-card">
            <h3>Sell Your Vehicle</h3>
            <p>Tell us about your ride and get a firm offer in minutes</p>
            <a href="sell.php">
                <div class="arrow-button">→</div>
            </a>
        </div>
    </div>
</section>



<!-- Suggested Cars Section -->
<section class="suggested-cars">
    <h2 class="section-heading">Featured Deals</h2>
    <div class="divider"></div>
    <div class="car-cards-container">
        <?php if (!empty($randomCars)): ?>
            <?php foreach ($randomCars as $randomCar): ?>
                <div class="car-card">
                    <img src="data:image/jpeg;base64,<?= base64_encode($randomCar['img1_data']) ?>" alt="<?= htmlspecialchars($randomCar['make'] . ' ' . $randomCar['model']) ?>">
                    <div class="car-card-content">
                        <h3><?= htmlspecialchars($randomCar['make'] . ' ' . $randomCar['model']) ?></h3>
                        <p><strong>Year:</strong> <?= htmlspecialchars($randomCar['year']) ?></p>
                        <p><strong>Price:</strong> $<?= htmlspecialchars(number_format($randomCar['price'], 2)) ?></p>
                        <a href="car_details.php?id=<?= $randomCar['id'] ?>" class="details-link">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No cars available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="buy.php">Buy</a></li>
            <li><a href="sell.php">Sell</a></li>
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

</body>
</html>
