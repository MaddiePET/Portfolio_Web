<?php
// Include the config.php file for database connection
include 'config.php';

// Query to get unique categories from the product table
$query = "SELECT DISTINCT category FROM product";
$result = $conn->query($query); // Use $conn from config.php

// Check if query was successful
if ($result) {
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode($categories);
} else {
    echo "Error: " . $conn->error; // Use $conn for error handling
}

// Close connection
$conn->close(); // Close the connection using $conn
?>
