<?php
session_start();

// Include the config.php file for database connection
include 'config.php';

// Get filter and date parameters from the request
$filter = $_GET['filter'] ?? 'overall';
$date = $_GET['date'] ?? '';
$month = $_GET['month'] ?? '';

// Adjust SQL query based on filter type
switch ($filter) {
    case 'daily':
        if ($date) {
            $sql = "SELECT payment_method, COUNT(*) AS count FROM sales_transactions WHERE DATE(purchase_date) = '$date' GROUP BY payment_method";
        }
        break;
    case 'monthly':
        if ($month) {
            $sql = "SELECT payment_method, COUNT(*) AS count FROM sales_transactions WHERE DATE_FORMAT(purchase_date, '%Y-%m') = '$month' GROUP BY payment_method";
        }
        break;
    default:
        $sql = "SELECT payment_method, COUNT(*) AS count FROM sales_transactions GROUP BY payment_method";
}

// Execute the query and prepare data for JSON
$data = [];
if (isset($sql)) {
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [$row['payment_method'], (int)$row['count']];
        }
    }
}

// Send data as JSON
header('Content-Type: application/json');
echo json_encode($data);

// Close the connection
$conn->close();
?>