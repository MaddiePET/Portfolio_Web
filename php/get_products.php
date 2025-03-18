<?php
// Include the config.php file for database connection
include 'config.php';

// Get the selected category from the query parameter
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Query to get products based on the selected category
$query = "SELECT productID, product_name FROM product WHERE category = ?";
$stmt = $conn->prepare($query); // Use $conn from config.php
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

// Check if query was successful
if ($result) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo "Error: " . $conn->error; // Use $conn for error handling
}

// Close connection
$conn->close(); // Close the connection using $conn
?>
