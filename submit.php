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
} catch (PDOException $e) {
    http_response_code(500);
    echo "Failed to connect to the database: " . $e->getMessage();
    exit();
}

// Check required fields are not empty
$required_fields = ['make', 'model', 'year', 'odometer', 'cc', 'price', 'cartype', 'msrp'];
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
$cartype = $_POST['cartype'];
$msrp = $_POST['msrp'];
$price = $_POST['price'];
$seller_name = $_POST['seller_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$description = $_POST['description'];

// Handle image uploads (names and binary data)
$image_data = [];
$has_image = false; // Flag to check if at least one image is uploaded

for ($i = 0; $i < 5; $i++) {
    $image_key = 'images';
    if (isset($_FILES[$image_key]['tmp_name'][$i]) && !empty($_FILES[$image_key]['tmp_name'][$i])) {
        $image_name = $_FILES[$image_key]['name'][$i];
        $image_content = file_get_contents($_FILES[$image_key]['tmp_name'][$i]);
        $image_data[] = ['name' => $image_name, 'data' => $image_content];
        $has_image = true; // At least one image is uploaded
    } else {
        $image_data[] = ['name' => null, 'data' => null];
    }
}

// Ensure at least one image is uploaded
if (!$has_image) {
    http_response_code(400);
    echo "You must upload at least one image.";
    exit();
}

// Insert data into the database
try {
    $sql = "INSERT INTO car_listings (
        make, model, year, odometer, cc, cartype, msrp, seller_name, phone, email, description, price,
        img1_name, img1_data, img2_name, img2_data, img3_name, img3_data, img4_name, img4_data, img5_name, img5_data
    ) VALUES (
        :make, :model, :year, :odometer, :cc, :cartype, :msrp, :seller_name, :phone, :email, :description, :price,
        :img1_name, :img1_data, :img2_name, :img2_data, :img3_name, :img3_data, :img4_name, :img4_data, :img5_name, :img5_data
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':make' => $make,
        ':model' => $model,
        ':year' => $year,
        ':odometer' => $odometer,
        ':cc' => $cc,
        ':cartype' => $cartype,
        ':msrp' => $msrp,
        ':seller_name' => $seller_name,
        ':phone' => $phone,
        ':email' => $email,
        ':description' => $description,
        ':price' => $price,
        ':img1_name' => $image_data[0]['name'],
        ':img1_data' => $image_data[0]['data'],
        ':img2_name' => $image_data[1]['name'],
        ':img2_data' => $image_data[1]['data'],
        ':img3_name' => $image_data[2]['name'],
        ':img3_data' => $image_data[2]['data'],
        ':img4_name' => $image_data[3]['name'],
        ':img4_data' => $image_data[3]['data'],
        ':img5_name' => $image_data[4]['name'],
        ':img5_data' => $image_data[4]['data'],
    ]);

    echo json_encode(['success' => true, 'message' => 'Listing submitted successfully!']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Failed to submit listing. Error: " . $e->getMessage()]);
}
?>
