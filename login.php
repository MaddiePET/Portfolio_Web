<?php
session_start();

// Include the config.php file for database connection
include 'php/config.php';

// Initialize variables
$message = ""; // Initialize notification message
$password = ""; // Initialize password variable
$hashed_password = ""; // Initialize hashed password variable

// Check if session variables for login attempts and lockout time are set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0; // Initialize login attempts to zero
    $_SESSION['lockout_time'] = null; // No lockout initially
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the request method is POST
    if (isset($_POST['action']) && $_POST['action'] == 'login') { // Check if login action is triggered

        // Check if the user is currently locked out
        if ($_SESSION['lockout_time'] && time() < $_SESSION['lockout_time']) {
            $remaining_time = ($_SESSION['lockout_time'] - time()); // Calculate remaining lockout time
            $minutes = floor($remaining_time / 60); // Calculate minutes remaining
            $seconds = $remaining_time % 60; // Calculate seconds remaining
            $message = "Please wait {$minutes} minute(s) and {$seconds} second(s) before trying again."; // Set message for user
        } else {
            // Reset lockout time if the lockout period has expired
            $_SESSION['lockout_time'] = null;

            // Retrieve login credentials from the POST request
            $email = $_POST['email'];
            $password = $_POST['password']; // Set the password variable

            // Prepare SQL statement to fetch the hashed password for the provided email
            $stmt = $conn->prepare("SELECT password_hash FROM staff WHERE email = ?");
            
            if ($stmt) {
                $stmt->bind_param("s", $email); // Bind the email parameter
                $stmt->execute(); // Execute the prepared statement
                $stmt->store_result(); // Store the result for later use

                // Check if a user with the provided email exists
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($hashed_password); // Bind the result to get the hashed password
                    $stmt->fetch(); // Fetch the result

                    // Verify the entered password against the stored hashed password
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['email'] = $email; // Store email in session upon successful login
                        $_SESSION['login_attempts'] = 0; // Reset login attempts on success
                        $_SESSION['success_message'] = "You have logged in successfully!"; // Set success message
                        header("Location: account.php"); // Redirect to index.html after successful login
                        exit(); // Stop executing the script
                    } else {
                        $message = "Incorrect password."; // Set message for incorrect password
                        $_SESSION['login_attempts']++; // Increment login attempts
                    }
                } else {
                    $message = "Email is not registered."; // Set message for unregistered email
                    $_SESSION['login_attempts']++; // Increment login attempts
                }

                // Check if the maximum number of login attempts has been reached
                if ($_SESSION['login_attempts'] >= 3) {
                    $_SESSION['lockout_time'] = time() + 60; // Set lockout time for 1 minute
                    $message = "Too many failed attempts. Please wait 1 minute before trying again."; // Notify user of lockout
                }

                $stmt->close(); // Close the prepared statement to free resources
            } else {
                $message = "Database query failed."; // Set message for query failure
            }
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and stylesheet links for the page setup -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Staff Log In Page"> 
    <meta name="keywords" content="grocery, staff, login"> 
    <meta name="author" content="Pookie"> 
    <link href="styles/styleforlogin.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Log In | GotoGro</title> <!-- Page title -->
</head>
<body>
    <!-- Background image -->
    <div class="background-img" style="background-image: url('styles/images/background.jpg');"></div>

    <!-- Notification Section to display messages -->
    <?php if (!empty($message)): ?>
        <div class="error-message"> <!-- Error message display -->
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Login form -->
    <form class="login-form" method="POST"> 
        <input type="hidden" name="action" value="login">
        <header>
            <img src="styles/images/logo.png" alt="GotoGro Logo" class="logo"> 
            <h1>Login to Your Account</h1> 
            <p>Please provide your login details to access the system.</p>
        </header>

        <!-- Login form fields -->
        <div class="form-section"> 
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter email" required> 

            <label for="password">Password</label>
            <div class="password-field">
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePasswordVisibility('password', this)"></i>
            </div>
        </div>

        <!-- Submit button -->
        <div class="button-container">
            <button type="submit" class="btn login-button">Log In</button>
        </div>

        <!-- Link to signup page -->
        <p class="signup-link">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
        <!-- Footer section -->
        <footer class="footer">
            <p>&#169; 2024 GotoGro by Pookie</p>
        </footer>
    </form>

    <script src="javascript/login.js"></script>
</body>
</html>