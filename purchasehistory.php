<?php
session_start(); // Start the session to access session variables

// Include the config.php file for database connection
include 'php/config.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];  // Get email from session

    // Fetch staff details from the database based on the email
    $sql = "SELECT fname, profile_picture FROM staff WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // Bind the email parameter
    $stmt->execute();
    $stmt->bind_result($fname, $profile_picture);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Purchase History Page">
    <meta name="keywords" content="grocery, purchase, history">
    <meta name="author" content="Pookie">
    <link href="styles/styleforpurchasehistory.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>Purchase History | GotoGro</title>
</head>

<body>
<header class="topbar">
    <div class="profile-logout-container">
        <div class="profile-picture">
            <img src="<?php echo file_exists($profile_picture) ? htmlspecialchars($profile_picture) : 'staff_profile_picture/default.jpg'; ?>" alt="Profile Picture" class="profile-img">
            <span class="greeting">Welcome, <?php echo htmlspecialchars($fname); ?>!</span>
        </div>
        <div class="logout-button">
            <a href="login.php" class="btn-logout">Logout</a>
        </div>
    </div>
</header>
    <!-- Sidebar Navigation -->
    <header>
        <nav class="navbar">
            <div class="logo-container">
                <img src="styles/images/logo.png" alt="GotoGro Logo" class="logo">
                <a href="index.html" class="nav-title">GotoGro-MRMS</a>
            </div>
            <div class="nav-links">
                <a href="data.php"><img src="styles/images/analytics.png">Dashboard</a>
                <a href="members.php"><img src="styles/images/members.png">Members</a>
                <a href="inventory.php"><img src="styles/images/inventory.png">Inventory</a>
                <a href="sales.php"><img src="styles/images/sales.png">Sales</a>
                <a href="report.php"><img src="styles/images/report.png">Report</a>
                <a href="notification.php"><img src="styles/images/notification.png">Notifications</a>
                <a href="account.php"><img src="styles/images/account.png">Account</a>
            </div>
        </nav>
    </header>

    <!-- Purchase History -->
    <main>
        <section class="purchase-history">
            <?php
            
            // Include the config.php file for database connection
            include 'php/config.php';


            // Get memberID
            $memberID = isset($_GET['memberID']) ? intval($_GET['memberID']) : 0;

            // Fetch member's name
            $memberSql = "SELECT fname, lname FROM member WHERE memberID = ?";
            $memberStmt = $conn->prepare($memberSql);
            $memberStmt->bind_param("i", $memberID);
            $memberStmt->execute();
            $memberResult = $memberStmt->get_result();

            if ($memberResult->num_rows > 0) {
                $memberRow = $memberResult->fetch_assoc();
                $memberName = htmlspecialchars($memberRow['fname'] . ' ' . $memberRow['lname']);
                echo "<div class='header-container'>
                    <a href='members.php'><img src='styles/images/back.png' class='back-button'></a>
                    <h1 class='history-title'>Purchase History for $memberName</h1>
                </div>";
            }

            // Prepare SQL statement to fetch purchase history
            $sql = "SELECT st.transactionID, st.purchase_date, p.product_name, ti.quantity_sold, p.product_price, st.total_price, st.payment_method
                    FROM sales_transactions st
                    INNER JOIN transaction_item ti ON st.transactionID = ti.transactionID
                    INNER JOIN product p ON ti.productID = p.productID
                    WHERE st.memberID = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $memberID);
            $stmt->execute();
            $result = $stmt->get_result();

            // Initialize variable to track current transaction ID
            $currentTransactionRowspan = 0; 

            if ($result->num_rows > 0) {
                // Count the rows for each transaction to set the rowspan correctly
                $transactions = [];
                while ($row = $result->fetch_assoc()) {
                    $transactions[$row['transactionID']][] = $row;
                }

                // Create the table structure
                echo "<table class='history-table'>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>";

                // Loop through each transaction
                foreach ($transactions as $transactionID => $items) {
                    $currentTransactionRowspan = count($items);

                    // Output the first row with transaction details
                    $firstItem = $items[0];
                    echo "<tr>
                            <td rowspan='{$currentTransactionRowspan}' class='transaction-details'>" . htmlspecialchars($transactionID) . "</td>
                            <td rowspan='{$currentTransactionRowspan}'>" . htmlspecialchars($firstItem['purchase_date']) . "</td>
                            <td>" . htmlspecialchars($firstItem['product_name']) . "</td>
                            <td>" . htmlspecialchars($firstItem['quantity_sold']) . "</td>
                            <td>$" . htmlspecialchars($firstItem['product_price']) . "</td>
                            <td rowspan='{$currentTransactionRowspan}'>$" . htmlspecialchars($firstItem['total_price']) . "</td>
                            <td rowspan='{$currentTransactionRowspan}'>" . htmlspecialchars($firstItem['payment_method']) . "</td>
                        </tr>";

                    // Output the rest of the items for the same transaction
                    for ($i = 1; $i < $currentTransactionRowspan; $i++) {
                        $item = $items[$i];
                        echo "<tr>
                                <td>" . htmlspecialchars($item['product_name']) . "</td>
                                <td>" . htmlspecialchars($item['quantity_sold']) . "</td>
                                <td>$" . htmlspecialchars($item['product_price']) . "</td>
                            </tr>";
                    }
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No purchase history found for this member.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>
</body>
</html>