<?php
session_start();

// Include the config.php file for database connection
include 'php/config.php';


// Start the session for managing notifications or session variables if needed
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Generate Reports Page">
    <meta name="keywords" content="grocery, report, generator">
    <meta name="author" content="Pookie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/styleforreport.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Generate Reports | GotoGro</title>
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

    <!-- Notification section -->
    <div class="notification" id="notification"></div>

<main>
    <section class="report">
        <h1>Export CSV Report</h1>
        <form class="report-form" id="reportForm" action="php/export.php" method="POST">
            <p>
                <label for="table">Table</label>
                <select name="table" id="table" required onchange="updateCheckboxes()">
                    <option value="">Select a table</option>
                    <option value="Members">Members</option>
                    <option value="Products">Products</option>
                    <option value="Sales">Sales</option>
                </select>
            </p>

            <p>
                <label for="startDate">Start Date</label>
                <input type="date" name="startDate" id="startDate" required>
            </p>

            <p>
                <label for="endDate">End Date</label>
                <input type="date" name="endDate" id="endDate" required>
            </p>

            <!-- Column checkboxes with improved layout and visibility -->
            <div id="checkboxes" class="checkbox-item"></div>

            <!-- Export button stays below checkboxes -->
            <button type="submit" class="export-button">Export CSV</button>
        </form>
    </section>
</main>

    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>
<!-- External JavaScript for form handling and updates -->
<script src="javascript/report.js"></script>

</body>
</html>
