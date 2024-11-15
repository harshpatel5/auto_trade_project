├── index.php            # Home page after login
├── register.php         # User registration page
├── login.php            # User login page
├── server.php           # Handles database connection and query execution
├── error.php            # Displays error messages
├── style.css            # Stylesheet for the web pages
└── README.md            # Project documentation

Prerequisites
PHP 7.x or above
MySQL database
Apache server (XAMPP, WAMP, etc.) or any web server running PHP

Clone the Repository:

git clone https://github.com/harshpatel5/auto_trade_project.git
cd auto_trade_project

Database Setup:

Create a MySQL database and import the required tables (use db.sql if you have an SQL script for it).
Open server.php and configure your database connection settings:
$db = mysqli_connect('host', 'username', 'password', 'database_name');


File Descriptions
server.php:
Handles database connection and interactions for user registration and login.
error.php:
Displays error messages to the user.
index.php:
The home page that welcomes the user after logging in.
login.php:
The login page where users enter their credentials.
register.php:
The registration page for creating new accounts.

