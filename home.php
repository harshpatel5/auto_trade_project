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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Trade</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Logout function
        function logout() {
            window.location.href = 'index.php?logout=true'; // Redirect to logout URL
        }

        function checkLoginStatus() {
            <?php if (!isUserLoggedIn()) : ?>
                // Redirect to login page with a message if not logged in
                alert("Please log in to access the Sell page.");
                window.location.href = "login.php";
            <?php endif; ?>
        }
    </script>
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
                <!-- Show Sell option only if the user is logged in -->
                <?php if (isUserLoggedIn()) : ?>
                    <a href="sell.php" id="sell-option">Sell</a>
                <?php else : ?>
                    <a href="javascript:void(0);" onclick="checkLoginStatus()" id="sell-option">Sell</a>
                <?php endif; ?>
            </div>
        </li>
        <li><a href="contact.html">Contact Us</a></li>

        <!-- Login/Logout and Username Display -->
        <li id="user-menu">
            <?php if (isUserLoggedIn()) : ?>
                <div class="dropdown">
                    <a href="javascript:void(0);">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    <div class="dropdown-content">
                        <a href="javascript:void(0);" onclick="logout()" style="color: red;">Logout</a>
                    </div>
                </div>
            <?php else : ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <a href="buy.html"><button>BUY</button></a>
        <?php if (isUserLoggedIn()) : ?>
            <a href="sell.php"><button>SELL</button></a>
        <?php else : ?>
            <a href="javascript:void(0);" onclick="checkLoginStatus()"><button>SELL</button></a>
        <?php endif; ?>
    </div>
</section>    

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
