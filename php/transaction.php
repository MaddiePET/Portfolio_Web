<?php
// Start a new session
session_start(); 

// Include the config.php file for database connection
include 'config.php';

// Retrieve form data sent via POST
$memberID = $_POST['memberID'];
$numProducts = $_POST['numProducts'];
$paymentMethod = $_POST['paymentMethod'];
$transactionDate = $_POST['transactionDate'];

// Begin a transaction to ensure all operations succeed together or fail together
$conn->begin_transaction();

try {
    // Insert a new transaction record with initial total_price of 0
    $insertTransaction = "INSERT INTO sales_transactions (memberID, total_price, purchase_date, payment_method) VALUES (?, 0, ?, ?)";
    $stmt = $conn->prepare($insertTransaction);
    $stmt->bind_param("iss", $memberID, $transactionDate, $paymentMethod);
    $stmt->execute();

    // Get the ID of the new transaction record for use in related entries
    $transactionID = $conn->insert_id;

    // Initialize total price for the transaction
    $totalPrice = 0; 

    // Loop through each product in the transaction
    for ($i = 1; $i <= $numProducts; $i++) {
        // Get product ID and quantity from the form data
        $productID = $_POST['productID' . $i];
        $quantity = $_POST['quantity' . $i];

        // Retrieve product price and current inventory quantity from the database
        $result = $conn->query("SELECT product_price, inv_qty FROM product WHERE productID = $productID");
        $row = $result->fetch_assoc();
        $productPrice = $row['product_price'];
        $inventoryQuantity = $row['inv_qty'];

        // Check if the product exists in the database
        if ($result->num_rows === 0) {
            throw new Exception("Product ID: $productID could not be found.");
        }

        // Calculate subtotal for the product and add it to the total price
        $subtotal = $productPrice * $quantity;
        $totalPrice += $subtotal;

        // Insert the product details into the transaction_item table
        $insertItem = "INSERT INTO transaction_item (transactionID, productID, quantity_sold, subtotal_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertItem);
        $stmt->bind_param("iiid", $transactionID, $productID, $quantity, $subtotal);
        $stmt->execute();

        // Check if there is sufficient stock for the requested quantity
        if ($inventoryQuantity < $quantity) {
            throw new Exception("Insufficient stock for Product ID: $productID");
        }

        // Update inventory quantity by deducting the sold quantity
        $newQuantity = $inventoryQuantity - $quantity;
        $updateInventory = "UPDATE product SET inv_qty = ? WHERE productID = ?";
        $stmt = $conn->prepare($updateInventory);
        $stmt->bind_param("ii", $newQuantity, $productID);
        $stmt->execute();
    }

    // Update the total price of the transaction after processing all items
    $updateTransaction = "UPDATE sales_transactions SET total_price = ? WHERE transactionID = ?";
    $stmt = $conn->prepare($updateTransaction);
    $stmt->bind_param("di", $totalPrice, $transactionID);
    $stmt->execute();

    // Commit the transaction to make all changes permanent
    $conn->commit();

    // Set a success message in the session and redirect to the sales page
    $_SESSION['message'] = "Transaction has been recorded successfully!";
    header("Location: ../sales.php");
    exit();
} catch (Exception $e) {
    // If an error occurs, rollback all database changes
    $conn->rollback();

    // Set an error message in the session with details and redirect to the sales page
    $_SESSION['message'] = $e->getMessage();
    header("Location: ../sales.php");
    exit();
}
?>