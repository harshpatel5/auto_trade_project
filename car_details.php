<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo "No email found in session. Please log in.";
    exit;
}
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

// Get the car ID from the query parameter
$carId = $_GET['id'] ?? null;

if (!$carId) {
    echo "Car not found.";
    exit();
}

// Fetch car details from the database
try {
    $sql = "SELECT * FROM car_listings WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $carId]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        echo "Car not found.";
        exit();
    }
} catch (PDOException $e) {
    echo "Failed to fetch car details: " . $e->getMessage();
    exit();
}

// Initialize the images array
$images = [];
for ($i = 1; $i <= 5; $i++) {
    if (!empty($car["img{$i}_data"])) {
        $images[] = [
            'name' => $car["img{$i}_name"],
            'data' => $car["img{$i}_data"]
        ];
    }
}

// Fetch random cars from the database
try {
    $randomCarsQuery = "SELECT * FROM car_listings WHERE id != :id ORDER BY RAND() LIMIT 4";
    $randomCarsStmt = $pdo->prepare($randomCarsQuery);
    $randomCarsStmt->execute([':id' => $carId]);
    $randomCars = $randomCarsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Failed to fetch random cars: " . $e->getMessage();
    $randomCars = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?></title>
    <link rel="stylesheet" href="car_details_style.css">
</head>
<body>
    <div class="page-container">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="navbar-container">
                <div class="logo">
                    <h1>Auto Trade</h1>
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

        <main>
            <!-- Content Container -->
            <div class="content-container">
            <!-- Carousel Section -->
                <div class="carousel-box">
                    <div class="carousel-container">
                        <div class="carousel-images" id="carousel">
                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $image): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($image['data']) ?>" alt="<?= htmlspecialchars($image['name']) ?>">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <img src="no-image-available.jpg" alt="No Image Available">
                            <?php endif; ?>
                        </div>
                        <?php if (count($images) > 1): ?>
                            <button class="carousel-arrow left" onclick="prevSlide()">&#10094;</button>
                            <button class="carousel-arrow right" onclick="nextSlide()">&#10095;</button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Car Details Section -->
                <div class="details-box">
                    <div class="car-details">
                        <h1><?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?></h1>
                        <p style="font-size: 25px;"><strong>Price:</strong> $<?= htmlspecialchars(number_format($car['price'], 2)) ?></p>
                        <p><strong>Year:</strong> <?= htmlspecialchars($car['year']) ?></p>
                        <p><strong>Seller Name:</strong> <?= htmlspecialchars($car['seller_name']) ?></p>
                        <p><strong>Contact:</strong> <?= htmlspecialchars($car['phone']) ?> | <?= htmlspecialchars($car['email']) ?></p>
                        <p><strong>MSRP:</strong> $<?= htmlspecialchars(number_format($car['msrp'], 2)) ?></p>
                        <p><strong>Car Type:</strong> <?= htmlspecialchars($car['cartype']) ?></p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($car['description']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Contact Seller Button -->
            <div class="contact-seller-section">
                <button class="contact-seller-button" onclick="openModal()">Contact Seller</button>
            </div>

            <!-- Contact Seller Modal -->
            <div id="contactModal" class="modal">
                <div class="modal-content">
                    <h2>Contact Seller</h2>
                    <form onsubmit="sendMessage(event)">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" readonly>

                        <label for="contactNumber">Contact Number</label>
                        <input type="number" id="contactNumber" name="contactNumber" placeholder="Enter your contact number" required>

                        <label for="message">Message</label>
                        <textarea id="message" name="message" placeholder="Enter your message" required></textarea>

                        <button type="submit">Send Message</button>
                    </form>
                    <button class="close-modal" onclick="closeModal()">Cancel</button>
                </div>
            </div>
            
            <!-- Success Message -->
            <div id="successMessage" class="success-message">
                Seller successfully contacted!
            </div>

            <!-- Suggested Cars Section -->
            <section class="suggested-cars">
                <h2 class="section-heading">A Few Other Cars You Might Like</h2>
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

        </main>

        

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
        </footer>
    </div>

    <script>
    const modal = document.getElementById('contactModal');
    const contactNumberField = document.getElementById('contactNumber');
    const messageField = document.getElementById('message');
    const successMessage = document.getElementById('successMessage');

    function openModal() {
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';

        // Clear the fields when the modal is closed
        contactNumberField.value = '';
        messageField.value = '';
    }

    function sendMessage(event) {
        event.preventDefault();

        // Show the success message
        successMessage.style.display = 'block';

        // Hide the modal and clear the fields
        closeModal();

        // Hide the success message after 3 seconds
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000);
    }
    let currentSlide = 0;
    const totalSlides = <?= count($images) ?>;

    function showSlide(index) {
        const carousel = document.getElementById('carousel');
        const slideWidth = carousel.offsetWidth;
        currentSlide = (index + totalSlides) % totalSlides;
        const offset = -currentSlide * slideWidth;
        carousel.style.transform = `translateX(${offset}px)`;
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    window.addEventListener("resize", () => {
        showSlide(currentSlide);
    });

    </script>
</body>
</html>
