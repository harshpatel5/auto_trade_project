<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_trade";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch listings
$sql = "SELECT id, year, make, model, price, seller_name, image FROM cars";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="listing-card" onclick="openModal(' . $row["id"] . ')">
            <img src="' . $row["image"] . '" alt="Car Image">
            <div class="listing-info">
                <h3>' . $row["year"] . ' ' . $row["make"] . ' ' . $row["model"] . '</h3>
                <p>Price: $' . number_format($row["price"]) . '</p>
                <p>Seller: ' . $row["seller_name"] . '</p>
            </div>
        </div>
        ';
    }
} else {
    echo "<p>No listings available.</p>";
}

$conn->close();
?>
