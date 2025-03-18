<?php
session_start();

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
}

// Define threshold for low stock
$threshold = 20; 

// Initialize low stock products array
$lowStockProducts = array();

// Check for AJAX requests
if (isset($_POST['refresh_notifications'])) {
    // Query to fetch low stock products based on inv_qty
    $sql = "SELECT productID, product_name, inv_qty, category FROM product WHERE inv_qty < $threshold";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lowStockProducts[] = $row;
        }
    }

    // Store low stock products in session
    $_SESSION['lowStockProducts'] = $lowStockProducts;

    echo generateNotificationHTML($lowStockProducts); // Return HTML for AJAX
    exit;
} elseif (isset($_POST['clear_notifications'])) {
    // Clear notifications
    $_SESSION['lowStockProducts'] = [];
    echo '<p class="notification-message">No products low on stock.</p>';
    exit;
}

// Retrieve current low stock products from session
$lowStockProducts = $_SESSION['lowStockProducts'] ?? [];

function getCategoryPrefix($category) {
    $prefixes = [
        'Dairy' => 'D',
        'Vegetable' => 'V',
        'Fruit' => 'F',
        'Beverage' => 'B',
        'Fruits' => 'F',
        'Pastry' => 'P',
        'Meat' => 'M',
        'Personal Care' => 'PC',
        'Snacks' => 'S',
        'Grains' => 'G',
        'Household Supplies' => 'HS',

    ];
    
    return $prefixes[$category] ?? '';
}

function generateNotificationHTML($products) {
    if (empty($products)) {
        return '<p class="notification-message">No products low on stock.</p>';
    }

    $html = ''; // Initialize the HTML variable

    // Loop through each product to generate HTML
    foreach ($products as $product) {
        // Get the product ID, name, and quantity
        $displayID = getCategoryPrefix($product['category']) . $product['productID'];

        // Generate HTML for each product notification
        $html .= '
        <div class="notification" data-product-id="' . htmlspecialchars($product['productID']) . '" id="product-' . htmlspecialchars($product['productID']) . '" onclick="redirectAndScrollToProduct(' . htmlspecialchars($product['productID']) . ')">
            <p>
                <strong>Low stock alert! </strong><br>
                <strong>Product ID: </strong>' . $displayID . '<br>
                <strong>Product Name: </strong>' . htmlspecialchars($product['product_name']) . '<br>
                <strong>Quantity: </strong>' . htmlspecialchars($product['inv_qty']) . '
            </p>
        </div>';
    }

    return $html; // Return the complete list after the loop finishes
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="description" content="Notification Page">
    <meta name="keywords" content="grocery, notification">
    <meta name="author" content="Pookie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/stylefornotification.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>Notifications | GotoGro </title>
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

    <main>
        <h1>Notifications</h1>
        <div id="notification-container">
            <?php echo generateNotificationHTML($lowStockProducts); ?>
        </div>

        <button class="refresh-button"><img src="styles/images/refresh.png">Refresh</button>
    </main>

    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>

    <script src="javascript/notification.js"></script>
</body>
</html>