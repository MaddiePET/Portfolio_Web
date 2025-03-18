<?php
session_start(); // Start the session to store notifications


// Include the config.php file for database connection
include 'php/config.php';

// Initialize a message variable and an errors array
$message = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'signup') {
        // Collect form data
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $address = $_POST['address'];

        // Check if the name already exists
        $nameCheckStmt = $conn->prepare("SELECT * FROM staff WHERE fname = ? AND lname = ?");
        $nameCheckStmt->bind_param("ss", $fname, $lname);
        $nameCheckStmt->execute();
        $nameResult = $nameCheckStmt->get_result();

        if ($nameResult->num_rows > 0) {
            $errors[] = "An account with this name already exists.";
        }
        $nameCheckStmt->close();

        // Check if the email already exists
        $emailCheckStmt = $conn->prepare("SELECT * FROM staff WHERE email = ?");
        $emailCheckStmt->bind_param("s", $email);
        $emailCheckStmt->execute();
        $emailResult = $emailCheckStmt->get_result();

        if ($emailResult->num_rows > 0) {
            $errors[] = "An account with this email already exists.";
        }
        $emailCheckStmt->close();

        // Check if the phone number already exists
        $phoneCheckStmt = $conn->prepare("SELECT * FROM staff WHERE phone = ?");
        $phoneCheckStmt->bind_param("s", $phone);
        $phoneCheckStmt->execute();
        $phoneResult = $phoneCheckStmt->get_result();

        if ($phoneResult->num_rows > 0) {
            $errors[] = "An account with this phone number already exists.";
        }
        $phoneCheckStmt->close();

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO staff (fname, lname, dob, email, phone, password_hash, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $fname, $lname, $dob, $email, $phone, $password, $address);
            
            if ($stmt->execute()) {
                $message = "$fname $lname's account has been created successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }

            $stmt->close(); 
        }
    }
}

// Fetch products from the database
$sql = "SELECT * FROM staff";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error); // Output error message if query fails
}
$conn->close(); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Staff Sign Up Page">
    <meta name="keywords" content="staff, signup">
    <meta name="author" content="Pookie">
    <link href="styles/styleforsignup.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>Sign Up | GotoGro</title>
</head>
<body>
    <div class="background-img" style="background-image: url('styles/images/background.jpg');"></div>

    <!-- Notification Section -->
    <?php if (!empty($message)): ?>
        <div class="notification">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="notification errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="error-messages"></div>

    <form class="signup-form" method="POST">
        <input type="hidden" name="action" value="signup">
        <header>
            <img src="styles/images/logo.png" alt="GotoGro Logo" class="logo">
            <h1>Create Your Account</h1>
            <p>Provide your details to sign up for staff access.</p>
        </header>

        <div class="form-container">
            <div class="form-section">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" placeholder="Enter first name" required>
                
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" placeholder="Enter last name" required>
                
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" placeholder="dd/mm/yyyy" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-section">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" placeholder="Enter phone number" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter address" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" placeholder="Confirm password" required>
            </div>
        </div>

        <div class="button-container"> 
            <button type="submit" class="btn signup-button">Sign Up</button>
            <button type="reset" class="btn clear-button">Clear Form</button>
        </div>

        <p class="login-link">Already have an account? <a href="login.php">Log in here</a>.</p>
        <footer class="footer">
            <p>&#169; 2024 GotoGro by Pookie</p>
        </footer>
    </form>

    <script src="javascript/signup.js"></script>
</body>
</html>