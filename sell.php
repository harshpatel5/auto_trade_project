<?php
session_start();

// Function to check if the user is logged in
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

// Retrieve email for pre-populating the form
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Car</title>
    <link rel="stylesheet" href="csssell.css">
    <script>
        async function submitForm(event) {
            event.preventDefault(); // Prevent form redirection
            const form = event.target;
            const formData = new FormData(form);
            const responseMessage = document.getElementById("form-message");

            // Check if at least one image is uploaded
            const images = Array.from(form.querySelectorAll("input[type='file']"));
            const hasImage = images.some(input => input.files.length > 0);
            if (!hasImage) {
                responseMessage.style.color = "red";
                responseMessage.innerHTML = "You must upload at least one image!";
                return;
            }

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    responseMessage.style.color = "green";
                    responseMessage.innerHTML = "Listing successfully submitted!";
                    setTimeout(() => {
                        window.location.href = "home.php"; // Redirect to home after success
                    }, 2000);
                } else {
                    responseMessage.style.color = "red";
                    responseMessage.innerHTML = result.message || "Failed to submit listing. Please try again.";
                }
            } catch (error) {
                responseMessage.style.color = "red";
                responseMessage.innerHTML = "Error connecting to the server. Please try again.";
            }
        }

        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const previewElement = document.getElementById(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function () {
                    previewElement.style.backgroundImage = `url('${reader.result}')`;
                    previewElement.style.backgroundSize = "cover";
                    previewElement.style.backgroundPosition = "center";
                    previewElement.innerText = "";
                };
                reader.readAsDataURL(file);
            } else {
                previewElement.style.backgroundImage = "none";
                previewElement.innerText = "+";
            }
        }
    </script>
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
                <a href="buy.php">Shop Cars</a>
                <a href="contact.php">Contact Us</a>
                <?php if (isUserLoggedIn()): ?>
                    <a href="?logout=true">Logout</a>
                <?php else: ?>
                    <a href="login.php">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Sell Form Section -->
    <section class="form-container">
        <h2>Sell Your Car</h2>
        <form id="listing-form" action="submit.php" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <div class="form-fields">
                <label for="make">Company:</label>
                <input type="text" id="make" name="make" placeholder="Enter Make" required>

                <label for="model">Model:</label>
                <input type="text" id="model" name="model" placeholder="Enter Model" required>

                <label for="year">Year:</label>
                <input type="number" id="year" name="year" placeholder="Enter Year" required min="1900" max="<?= date('Y') ?>">

                <label for="odometer">Odometer (Kilometers):</label>
                <input type="number" id="odometer" name="odometer" placeholder="Enter Odometer Reading" required min="0">

                <label for="cc">Condition:</label>
                <select id="cc" name="cc" required>
                    <option value="">Select Condition</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="needs-repair">Needs Repair</option>
                </select>

                <label for="cartype">Car Type:</label>
                <select id="cartype" name="cartype" required>
                    <option value="">Select Car Type</option>
                    <option value="Gas">Gas</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Electric">Electric</option>
                    <option value="Hybrid">Hybrid</option>
                </select>

                <label for="msrp">MSRP:</label>
                <input type="number" id="msrp" name="msrp" placeholder="Enter MSRP" required min="0">

                <label for="seller_name">Seller Name:</label>
                <input type="text" id="seller_name" name="seller_name" placeholder="Enter Seller Name" required>

                <label for="phone">Phone:</label>
                <input type="number" id="phone" name="phone" placeholder="Enter Phone Number" required pattern="\d*">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>

                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Enter a brief description of the car" required></textarea>

                <label for="price">Listing Price:</label>
                <input type="number" id="price" name="price" placeholder="Enter Listing Price" required min="0">
            </div>

            <!-- Image Upload Section -->
            <div class="upload-box">
                <h3>Upload up to 5 images</h3>
                <div class="image-upload-container">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <div class="image-upload-slot" id="upload<?= $i ?>">
                            <label for="image<?= $i ?>" class="upload-placeholder" id="preview<?= $i ?>">+</label>
                            <input type="file" id="image<?= $i ?>" name="images[]" class="image-input" accept="image/*" onchange="previewImage(event, 'preview<?= $i ?>')">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <button type="submit">Submit Listing</button>
            <div id="form-message" style="margin-top: 20px; color: green; font-weight: bold;"></div>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="buy.php">Buy</a></li>
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
</body>
</html>
