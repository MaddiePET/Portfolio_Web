<?php
session_start();

// Include config.php for database connection
include 'config.php';

// Get filter and date parameters
$filter = $_GET['filter'] ?? 'overall';
$date = $_GET['date'] ?? '';
$month = $_GET['month'] ?? '';

// Adjust the SQL queries based on the filter type
$conditionSales = '';
if ($filter === 'daily' && $date) {
    $conditionSales = "WHERE DATE(purchase_date) = '$date'";
} elseif ($filter === 'monthly' && $month) {
    $conditionSales = "WHERE DATE_FORMAT(purchase_date, '%Y-%m') = '$month'";
}

$conditionProd = '';
if ($filter === 'daily' && $date) {
    $conditionProd = "WHERE DATE(last_restock_date) = '$date'";
} elseif ($filter === 'monthly' && $month) {
    $conditionProd = "WHERE DATE_FORMAT(last_restock_date, '%Y-%m') = '$month'";
}

$conditionMember = '';
if ($filter === 'daily' && $date) {
    $conditionMember = "WHERE DATE(registration_date) = '$date'";
} elseif ($filter === 'monthly' && $month) {
    $conditionMember = "WHERE DATE_FORMAT(registration_date, '%Y-%m') = '$month'";
}

// Total sales and total units sold
$salesQuery = "SELECT 
                    SUM(total_price) AS total_sales,
                    SUM(quantity_sold) AS total_units
               FROM sales_transactions
               LEFT JOIN transaction_item ON sales_transactions.transactionID = transaction_item.transactionID
               $conditionSales";
$salesResult = $conn->query($salesQuery);
$salesData = $salesResult->fetch_assoc();

// New members
$membersQuery = "SELECT COUNT(*) AS new_members FROM member $conditionMember";
$membersResult = $conn->query($membersQuery);
$membersData = $membersResult->fetch_assoc();

// Stock-in data for products
$stockQuery = "SELECT COUNT(DISTINCT productID) AS total_products_restocked FROM product $conditionProd";
$stockResult = $conn->query($stockQuery);
$stockData = $stockResult->fetch_assoc();


if ($filter== 'overall'){
    // Total sales and total units sold
        $salesQuery = "SELECT 
        SUM(total_price) AS total_sales,
        SUM(quantity_sold) AS total_units
        FROM sales_transactions
        LEFT JOIN transaction_item ON sales_transactions.transactionID = transaction_item.transactionID";
        $salesResult = $conn->query($salesQuery);
        $salesData = $salesResult->fetch_assoc();

        // New members
        $membersQuery = "SELECT COUNT(*) AS new_members FROM member";
        $membersResult = $conn->query($membersQuery);
        $membersData = $membersResult->fetch_assoc();

        // Stock-in data for products
        $stockQuery = "SELECT COUNT(DISTINCT productID) AS total_products_restocked FROM product";
        $stockResult = $conn->query($stockQuery);
        $stockData = $stockResult->fetch_assoc();

}

// Prepare data for JSON
$data = [
    'total_sales' => $salesData['total_sales'] ?? 0,
    'total_units' => $salesData['total_units'] ?? 0,
    'new_members' => $membersData['new_members'] ?? 0,
    'total_products_restocked' => $stockData['total_products_restocked'] ?? 0,
];

// Send data as JSON
header('Content-Type: application/json');
echo json_encode($data);

// Close the connection
$conn->close();