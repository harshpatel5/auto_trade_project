<?php 
require('server.php');

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
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
    <title>Sell Your Car</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Logout function
        function logout() {
            window.location.href = 'index.php?logout=true'; // Redirect to logout URL
        }
    </script>
</head>
<body>
    <nav>
        <h1>Auto Trading</h1>
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

    <section class="form-container">
        <!-- Left Side: Image Upload Box -->
        <div class="upload-box">
            <div class="upload-instructions">Upload up to 5 images</div>
            <div class="image-upload-container">
                <div class="image-upload-slot" id="upload1">
                    <label for="image1" class="upload-placeholder" id="preview1">+</label>
                    <input type="file" id="image1" name="images[]" class="image-input" accept="image/*" onchange="previewImage(event, 'preview1')">
                    <button class="remove-button" onclick="removeImage('preview1')" style="display: none;">Remove</button>
                </div>
                <div class="image-upload-slot" id="upload2">
                    <label for="image1" class="upload-placeholder" id="preview2">+</label>
                    <input type="file" id="image2" name="images[]" class="image-input" accept="image/*" onchange="previewImage(event, 'preview2')">
                    <button class="remove-button" onclick="removeImage('preview2')" style="display: none;">Remove</button>
                </div>
                <div class="image-upload-slot" id="upload4">
                    <label for="image4" class="upload-placeholder" id="preview4">+</label>
                    <input type="file" id="image2" name="images[]" class="image-input" accept="image/*" onchange="previewImage(event, 'preview4')">
                    <button class="remove-button" onclick="removeImage('preview2')" style="display: none;">Remove</button>
                </div>
                <div class="image-upload-slot" id="upload3">
                    <label for="image3" class="upload-placeholder" id="preview3">+</label>
                    <input type="file" id="image3" name="images[]" class="image-input" accept="image/*" onchange="previewImage(event, 'preview3')">
                    <button class="remove-button" onclick="removeImage('preview2')" style="display: none;">Remove</button>
                </div>
                <!-- Additional image upload slots omitted for brevity -->
            </div>
        </div>

        <!-- Right Side: Form Fields -->
        <form id="listing-form" action="submit.php" method="POST" enctype="multipart/form-data">
            <div class="form-fields">
                <label for="make">Make:</label>
                <select id="make" name="make" required>
                    <option value="">Select Make</option>
                    <option value="Toyota">Toyota</option>
                </select>

                <label for="model">Model:</label>
                <select id="model" name="model" required>
                    <option value="">Select Model</option>
                    <option value="Corolla">Corolla</option>
                </select>

                <label for="year">Year:</label>
                <input type="number" id="year" name="year" placeholder="Enter Year" required min="1900" max="2023">

                <label for="odometer">Odometer (Kilometers):</label>
                <input type="number" id="odometer" name="odometer" placeholder="Enter Odometer Reading" required min="0">

                <label for="cc">Condition:</label>
                <select id="cc" name="cc" required>
                    <option value="">Select Condition</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="needs-repair">Needs Repair Work</option>
                </select>

                <!-- New Seller Name Field -->
                <label for="seller_name">Seller Name:</label>
                <input type="text" id="seller_name" name="seller_name" placeholder="Enter Seller Name" required>

                <!-- New Contact Details Section with Box -->
                <div class="contact-details-box">
                    <h4>Contact Details</h4>
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number" required>
                    
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" readonly>
                </div>

                <!-- New Description Field -->
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Enter a brief description of the car" required></textarea>

                <label for="price">Listing Price:</label>
                <input type="number" id="price" name="price" placeholder="Enter Listing Price" required min="0">

                <button type="submit" >Submit Listing</button>

                <!-- Message container for displaying submission status, moved below the button -->
                <div id="form-message" style="color: green; font-weight: bold; margin-top: 20px;"></div>
            </div>
        </form>
    </section>

    <footer>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="home.html#about">About Us</a></li>
                <li><a href="#contact">Contact Us</a></li>
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

    <script src="scripts.js"></script>
    <script>
        document.getElementById('listing-form').onsubmit = async function (event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const message = await response.text();
                const formMessageDiv = document.getElementById('form-message');

                if (response.ok) {
                    formMessageDiv.style.color = 'green';
                    formMessageDiv.innerHTML = "Listing submitted successfully!";
                } else {
                    formMessageDiv.style.color = 'red';
                    formMessageDiv.innerHTML = message || "Failed to submit listing. Please try again.";
                }

                formMessageDiv.style.display = 'block';
            } catch (error) {
                const formMessageDiv = document.getElementById('form-message');
                formMessageDiv.style.color = 'red';
                formMessageDiv.innerHTML = 'Could not connect to the database.';
                formMessageDiv.style.display = 'block';
            }
        };
    </script>
</body>
</html>
