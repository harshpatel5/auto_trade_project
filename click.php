<?php 
session_start(); 

$db = mysqli_connect('localhost', 'root', '', 'auto_trade');

$get_data = " SELECT * FROM login_info"; 
$result = mysqli_query($db, $get_data);

// Assuming $result is your query result
if ($result->num_rows > 0) {
    // Start HTML table
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>";
    
    // Loop through all rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
    
        echo "</tr>";
    }
    
    echo "  </tbody>
          </table>";
} else {
    echo "No records found.";
}
?>



