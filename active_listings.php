<?php
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
    return isset($_SESSION['email']); // Assuming user's email is stored in the session
}

if (!isUserLoggedIn()) {
    echo "Please log in to view your listings.";
    exit();
}

// Get the logged-in user's email
$email = $_SESSION['email'];

// Handle Remove Listing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $removeId = $_POST['remove_id'];

    try {
        $deleteQuery = "DELETE FROM car_listings WHERE id = :id AND email = :email";
        $stmt = $pdo->prepare($deleteQuery);
        $stmt->execute([':id' => $removeId, ':email' => $email]);

        // Refresh the page after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo "Failed to delete listing: " . $e->getMessage();
        exit();
    }
}

// Fetch car listings from the database for the logged-in user
try {
    $sql = "SELECT id, make, model, year, price, odometer, img1_name, img1_data 
            FROM car_listings 
            WHERE email = :email"; // 'email' is the column in your database
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $carListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Failed to fetch car listings: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Car Listings</title>
    <link rel="stylesheet" href="activelisting-style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <h1>Trade Wheels</h1>
            </div>
            
            <div class="navbar-options">
                <a href="home.php">Home</a>
                <a href="sell.php">Sell Your Car</a>
                <a href="contact.php">Contact Us</a>
            </div>
        </div>
    </nav>

    <!-- Listing Section -->
    <section class="listing-container">
        <h2>Your Car Listings</h2>
        <div class="cards-container">
            <?php if (empty($carListings)): ?>
                <p>No listings found.</p>
            <?php else: ?>
                <?php foreach ($carListings as $car): ?>
                    <div class="card">
                        <?php if (!empty($car['img1_data'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($car['img1_data']) ?>" alt="<?= htmlspecialchars($car['img1_name']) ?>" class="car-image">
                        <?php else: ?>
                            <img src="default-car.jpg" alt="No Image Available" class="car-image">
                        <?php endif; ?>
                        <div class="card-content">
                            <h3><?= htmlspecialchars($car['year']) ?> <?= htmlspecialchars($car['make']) ?> <?= htmlspecialchars($car['model']) ?></h3>
                            <p class="car-distance"><?= htmlspecialchars(number_format($car['odometer'], 0)) ?> km</p>
                            <p class="car-price">$<?= htmlspecialchars(number_format($car['price'], 2)) ?></p>
                            <!-- Remove Listing Button -->
                            <form method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="remove_id" value="<?= $car['id'] ?>">
                                <button type="submit" class="remove-btn">Remove Listing</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="home.php">Home</a></li>
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

    <style>
        .remove-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .remove-btn:hover {
            background-color: #e63939;
        }
    </style>
</body>
</html>
