<?php
session_start();


// Prevent caching to handle back button correctly
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Function to check if user is logged in
function checkLogin() {
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
        exit();
    }
}

// Check login status
checkLogin();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!-- Add meta tags to prevent caching -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Add JavaScript to handle back button -->
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from cache (back button)
                window.location.reload();
            }
        });

        // Prevent back button
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    </script>
</head>
<body>
    <div class="header">
        <h2>Home Page</h2>
    </div>
    Welcome to my project page   
     
    <div class="content">
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error success">
                <h3>
                    <?php 
                        echo htmlspecialchars($_SESSION['success']); 
                        unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- logged in user information -->
        <?php if (isset($_SESSION['username'])) : ?>
            <p>Welcome <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

        
            <p><a href="click.php" style="color: red;">click-me</a></p>
            <p><a href="index.php?logout='1'" style="color: red;">logout</a></p>
        <?php endif ?>
    </div>
</body>
</html>