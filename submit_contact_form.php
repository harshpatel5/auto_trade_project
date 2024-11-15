<?php
session_start();

// Function to check if user is logged in (for any additional redirects, etc., you can modify as needed)
function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

// Handle logout if requested
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php"); // Redirect to login page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Trade</title>
    <link rel="stylesheet" href="style.css">
    <script></script>
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <h1>Auto Trade</h1>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li class="dropdown">
            <a href="#">Listings</a>
            <div class="dropdown-content">
                <a href="buy.html">Buy</a>
                <a href="sell.html">Sell</a>
            </div>
        </li>
        <li><a href="contact.html">Contact Us</a></li>
        <?php if (isUserLoggedIn()) : ?>
            <!-- Show username and logout link if logged in -->
            <li class="dropdown">
                <a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                <div class="dropdown-content">
                    <a href="index.php?logout=1" style="color: red;">Logout</a>
                </div>
            </li>
                    
        <?php else : ?>
            <!-- Show login link if not logged in -->
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Hero Section -->
<h1>thanks for reaching out someone will reach out to you soon.</h1>



<!-- About Us Section -->
<section class="section" id="about">
    <h2>About Us</h2>
    <p>Learn more about our company, our values, and how we strive to connect buyers and sellers in the auto trading industry.</p>
</section>

<!-- Our Mission Section -->
<section class="section" id="mission">
    <h2>Our Mission</h2>
    <p>Our mission is to provide a seamless and trustworthy platform for car enthusiasts to buy and sell vehicles with ease.</p>
</section>

<!-- Our Vision Section -->
<section class="section" id="vision">
    <h2>Our Vision</h2>
    <p>Our vision is to become the leading auto trading platform by prioritizing transparency, innovation, and customer satisfaction.</p>
</section>

<!-- Footer -->
<footer>
    <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="contact.html">Contact Us</a></li>
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
