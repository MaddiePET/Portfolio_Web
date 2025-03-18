<?php
include 'config.php';

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    // Query to get sales trend for a particular product
    $sql = "SELECT DATE(purchase_date) AS purchase_date, SUM(total_price) AS sales
            FROM sales_transactions
            JOIN transaction_item ON sales_transactions.transactionID = transaction_item.transactionID
            WHERE productID = ?
            GROUP BY DATE(purchase_date)
            ORDER BY purchase_date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    $salesTrend = [];
    while ($row = $result->fetch_assoc()) {
        $salesTrend[] = $row;
    }

    // Return sales trend as JSON response
    echo json_encode($salesTrend);
}
?>
