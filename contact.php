<?php
session_start();

// Function to check if the user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['username']); // Adjust based on the session variable you're using for logged-in users
}

// Handle logout
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
    <title>Contact Us - Trade Wheels</title>
    <link rel="stylesheet" href="stylecontact.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-container">
        <div class="logo">
            <h1>Trade Wheels</h1>
        </div>
        <div class="navbar-options">
            <a href="home.php">Home</a>
            <a href="active_listings.php">Your Listings</a>
            <a href="buy.php">Shop Cars</a>
            <a href="sell.php">Sell</a>
           
            <?php if (isUserLoggedIn()): ?>
                <a href="?logout=true">Logout</a>
            <?php else: ?>
                <a href="login.php">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Contact Information Section -->
<section class="section" id="contact-info">
    <h2>Get in Touch</h2>
    <p>Feel free to reach out via our contact form, visit us in person, or give us a call during business hours.</p>

    <div class="form-container">
        <!-- Contact Form -->
        <div class="form-fields">
            <h3>Contact Form</h3>
            <form action="submit_contact_form.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone:</label>
                <input type="number" id="phone" name="phone" placeholder="Enter your phone number" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Contact Details -->
        <div class="upload-box" style="background-color: #ffffff;">
            <h3>Contact Details</h3>
            <p><strong>Address:</strong> 2000 Simcoe St, Oshawa, ON <br>L1G0C5</p>
            <p><strong>Phone:</strong> (123) 456-7890</p>
            <p><strong>Email:</strong> <a href="mailto:info@autotrade.com">info@autotrade.com</a></p>
            <p><strong>Business Hours:</strong></p>
            <p>Mon-Fri: 9:00 AM - 6:00 PM</p>
            <p>Sat: 10:00 AM - 4:00 PM</p>
            <p>Sun: Closed</p>

            <!-- Google Maps Embed -->
            <div style="margin-top: 20px;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2730.551589352703!2d-78.89480390000001!3d43.9450912!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89d51b9a81c6966d%3A0x6a0a644e41d65520!2s2000%20Simcoe%20St%20N%2C%20Oshawa%2C%20ON%20L1G%200C5!5e1!3m2!1sen!2sca!4v1731527234404!5m2!1sen!2sca" 
                    width="100%" 
                    height="200" 
                    style="border:0; border-radius: 8px;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="home.php">Home</a></li>
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
