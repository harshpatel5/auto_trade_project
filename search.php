<?php
session_start();
// Database connection
$host = 'localhost';
$dbname = 'auto_trade';
$username = 'root';
$password = '';

// Function to check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed to connect to the database: " . $e->getMessage();
    exit();
}

// Get search query
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$carListings = [];

if ($searchQuery) {
    try {
        // Search in multiple columns using LIKE
        $sql = "SELECT id, make, model, year, price, description, seller_name, phone, email, img1_name, img1_data 
                FROM car_listings 
                WHERE make LIKE :query 
                OR model LIKE :query 
                OR year LIKE :query 
                OR description LIKE :query";
        
        $stmt = $pdo->prepare($sql);
        $searchParam = "%{$searchQuery}%";
        $stmt->bindParam(':query', $searchParam);
        $stmt->execute();
        $carListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Search failed: " . $e->getMessage();
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Auto Trade</title>
    <link rel="stylesheet" href="buy-style.css">
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .search-results {
            flex: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .no-results {
            text-align: center;
            padding: 40px;
            font-size: 1.2em;
            color: #666;
        }
        
        .search-summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 cards per row, wider cards */
            gap: 20px;
        }


        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
            background-color: #fff;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .car-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
            text-align: left;
        }

        /* Footer Styling */
        footer {
            display: flex;
            justify-content: space-around;
            background-color: #212529;
            color: #fff;
            padding: 40px 20px;
            text-align: center;
        }

        footer .footer-section h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #f8f9fa;
            border-bottom: 2px solid #f8f9fa;
            display: inline-block;
            padding-bottom: 5px;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li {
            margin: 8px 0;
        }

        footer ul li a {
            color: #adb5bd;
            text-decoration: none;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        footer ul li a:hover {
            color: #ff4d4d;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="logo">
            <h1>Auto Trade</h1>
        </div>
        <div class="navbar-search">
            <form action="search.php" method="GET" style="display: flex; gap: 10px; align-items: center;">
                <input type="text" name="query" placeholder="Search cars..." value="<?= htmlspecialchars($searchQuery) ?>" required>
                <button type="submit">Search</button>
                <button type="button" onclick="window.location.href='buy.php'">Clear</button>
            </form>
        </div>

        <div class="navbar-options">
            <a href="home.php">Home</a>
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

<!-- Search Results Section -->
<section class="search-results">
    <div class="search-summary">
        <h2>Search Results for "<?= htmlspecialchars($searchQuery) ?>"</h2>
        <p>Found <?= count($carListings) ?> matching vehicles</p>
    </div>

    <?php if (empty($carListings)): ?>
        <div class="no-results">
            <p>No vehicles found matching your search. Please try different keywords.</p>
        </div>
    <?php else: ?>
        <div class="cards-container">
            <?php foreach ($carListings as $car): ?>
                <div class="card" onclick="location.href='car_details.php?id=<?= $car['id'] ?>'">
                    <img src="data:image/jpeg;base64,<?= base64_encode($car['img1_data']) ?>"
                        alt="<?= htmlspecialchars($car['img1_name']) ?>" 
                        class="car-image">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($car['make']) ?> <?= htmlspecialchars($car['model']) ?></h3>
                        <p>Year: <?= htmlspecialchars($car['year']) ?></p>
                        <p>Price: $<?= htmlspecialchars(number_format($car['price'], 2)) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</section>

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


<script>
    function logout() {
        window.location.href = "logout.php";
    }
</script>
</body>
</html>
