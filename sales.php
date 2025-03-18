<?php
session_start(); // Start the session to access session variables

// Include the config.php file for database connection
include 'php/config.php';

// Check if the user is logged in by verifying that email is set in the session
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

// Check if there is a message in the session to display a notification
if (isset($_SESSION['message'])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const notification = document.getElementById('notification');
                notification.innerText = '{$_SESSION['message']}';
                notification.style.display = 'block';
            });
          </script>";
    unset($_SESSION['message']); // Clear the message after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta and stylesheet links for the page setup -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sales Transaction Page">
    <meta name="keywords" content="grocery, sales, transactions">
    <meta name="author" content="Pookie">
    <link href="styles/styleforsales.css" rel="stylesheet">
    <!-- Google Fonts preconnect and imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>Sales Transaction | GotoGro</title>
</head>

<body>

<!-- Top Bar -->
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
    <!-- Header section with navigation links -->
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

    <!-- Sales transactions section -->
    <main>
        <div class="notification" id="notification"></div>
        <section class="sales-transactions">
            <h1>Sales Transaction</h1>

            <!-- Sales form to record transactions -->
            <div class="sales-form">
                <form id="salesForm" method="POST" action="php/transaction.php" onsubmit="prepareMemberID()">
                    <p>
                        <label for="memberID">Member ID</label>
                        <input type="text" name="memberID" id="memberID" value="M" required>
                    </p>
                    <p>
                        <label for="numProducts">Number of Products</label>
                        <input type="number" name="numProducts" id="numProducts" placeholder="Enter Number of Products" required min="1">
                    </p>
                    <p>
                        <button type="button" class="ok-button" onclick="generateProductFields()">OK</button>
                    </p>

                    <div id="productFields"></div>

                    <p>
                        <label for="paymentMethod">Payment Method</label>
                        <select name="paymentMethod" id="paymentMethod" required>
                            <option value="">Select</option>
                            <option value="credit">Credit Card</option>
                            <option value="debit">Debit Card</option>
                            <option value="cash">Cash</option>
                        </select>
                    </p>
                    <p>
                        <label for="transactionDate">Date of Transaction</label>
                        <input type="date" name="transactionDate" id="transactionDate" readonly>
                    </p>

                    <!-- Buttons to record sale and clear form -->
                    <div class="button-container">
                        <button type="submit" class="btn record-button">Record Sale</button>
                        <button type="reset" class="btn clear-button">Clear Form</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Footer section -->
    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>

    <script src="javascript/sales.js"></script>
</body>
</html>