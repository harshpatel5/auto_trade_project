<!DOCTYPE html>
<html>
<head>
    <title>Add Car Listing</title>
</head>
<body>
    <form method="POST" action="">
        Make: <input type="text" name="make"><br>
        Model: <input type="text" name="model"><br>
        Year: <input type="number" name="year"><br>
        Odometer: <input type="number" name="odometer"><br>
        Condition: <input type="text" name="condition"><br>
        Price: <input type="number" name="price"><br>
        Seller Name: <input type="text" name="seller_name"><br>
        Phone: <input type="text" name="phone"><br>
        Email: <input type="email" name="email"><br>
        Description: <textarea name="description"></textarea><br>
        <input type="submit" value="Submit">
    </form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost';
    $dbname = 'auto_trade';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully<br>";
        
        $sql = "INSERT INTO car_listings (make, model, year, odometer, `condition`, price, seller_name, phone, email, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['make'],
            $_POST['model'],
            $_POST['year'],
            $_POST['odometer'],
            $_POST['condition'],
            $_POST['price'],
            $_POST['seller_name'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['description']
        ]);
        
        echo "Data inserted successfully";
        
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
</body>
</html>