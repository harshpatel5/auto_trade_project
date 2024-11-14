<?php
header("Content-Type: text/plain"); // Set plain text response

// Database connection
$host = 'localhost';
$dbname = 'auto_trade';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!\n"; // Temporary success message for debugging
} catch (PDOException $e) {
    // Detailed error message to help diagnose
    http_response_code(500);
    echo "Could not connect to the database. Error: " . $e->getMessage();
    exit();
}

// Check required fields are not empty
$required_fields = ['make', 'model', 'year', 'odometer', 'cc', 'price'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo "Please fill in all required fields.";
        exit();
    }
}

// Retrieve form data
$make = $_POST['make'];
$model = $_POST['model'];
$year = $_POST['year'];
$odometer = $_POST['odometer'];
$cc = $_POST['cc'];
$price = $_POST['price'];
$seller_name = $_POST['seller_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$description = $_POST['description'];

// Insert data into the database
try {
    $sql = "INSERT INTO car_listings (make, model, year, odometer, cc, seller_name, phone, email, description, price)
            VALUES (:make, :model, :year, :odometer, :cc, :seller_name, :phone, :email, :description, :price)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':make' => $make,
        ':model' => $model,
        ':year' => $year,
        ':odometer' => $odometer,
        ':cc' => $cc,
        ':seller_name' => $seller_name,
        ':phone' => $phone,
        ':email' => $email,
        ':description' => $description,
        ':price' => $price
    ]);

    // Success message
    echo "Listing submitted successfully!";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Failed to submit listing. Error: " . $e->getMessage();
}
?>