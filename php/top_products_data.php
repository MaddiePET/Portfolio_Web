<?php
// Database connection
include('config.php');
 
$filter = $_GET['filter'] ?? 'overall';  // Default to "overall" if no filter is provided
$date = $_GET['date'] ?? $_GET['month'] ?? null;  // Daily date or monthly month if provided
 
// Predefined colors for products
$colors = ['#A2D2DF', '#CBD2A4', '#E4C087', '#EDDFE0', '#F6EFBD'];
 
// Base query to get top 5 products by category
$query = "
    SELECT p.product_name, SUM(ti.quantity_sold) AS units_sold, p.category
    FROM sales_transactions st
    JOIN transaction_item ti ON st.transactionID = ti.transactionID
    JOIN product p ON ti.productID = p.productID
    WHERE ";
 
// Apply filtering conditions based on the filter type
if ($filter === 'daily') {
    $query .= "DATE(st.purchase_date) = '$date' ";
} elseif ($filter === 'monthly') {
    $query .= "DATE_FORMAT(st.purchase_date, '%Y-%m') = '$date' ";
} elseif ($filter === 'overall') {
    // No additional WHERE condition needed for overall, so we can end the WHERE clause here
    $query = rtrim($query, " WHERE ");
}
 
$query .= "GROUP BY p.category, p.product_name
            ORDER BY units_sold DESC
            LIMIT 5"; // Top 5 products per category
 
$result = mysqli_query($conn, $query);
 
$topProducts = [];
$colorIndex = 0; // To cycle through the colors
 
while ($row = mysqli_fetch_assoc($result)) {
    // Assign a color from the predefined array to each product
    $topProducts[] = [
        'product_name' => $row['product_name'],
        'units_sold' => (int) $row['units_sold'],
        'color' => $colors[$colorIndex]
    ];
    $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through colors
}
 
echo json_encode($topProducts);
 
?>
 