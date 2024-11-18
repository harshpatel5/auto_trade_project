<?php
session_start();

// Function to check if the user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Sent - Auto Trade</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Success Message Styling */
        .success-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 80vh;
            text-align: center;
            background-color: #f9f9f9;
        }

        .success-container h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }

        .success-container p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .success-container a {
            text-decoration: none;
            color: white;
            background-color: #cc3c3c;
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .success-container a:hover {
            background-color: #a12828;
        }
    </style>
</head>
<body>



<!-- Success Message Section -->
<div class="success-container">
    <h1>Thank You!</h1>
    <p>Your message has been sent successfully.</p>
    <p>We appreciate your interest, and one of our representatives will contact you shortly.</p>
    <a href="home.php">Back to Home</a>
</div>

</body>
</html>
