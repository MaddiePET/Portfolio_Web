<?php
session_start(); // Start session
// Include the config.php file for database connection
include 'php/config.php';

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Fetch staff details
    $sql = "SELECT staffID, fname, lname, dob, email, phone, address, profile_picture FROM staff WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($staffID, $fname, $lname, $dob, $email, $phone, $address, $profile_picture);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "No user is logged in.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Staff Account Page">
    <meta name="keywords" content="grocery, account, staff">
    <meta name="author" content="Pookie">
    <link href="styles/styleforaccount.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Staff Account | GotoGro</title>
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
    <!-- Main Content -->
    <main>
        <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='notification success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
                unset($_SESSION['message']);
            }
        ?>
        <section class="content">
            <h1>My Account</h1>
                    <script>function previewImage(event) {
                        var reader = new FileReader();
                        reader.onload = function() {
                            var output = document.getElementById('imagePreview');
                            output.src = reader.result;
                        }
                        reader.readAsDataURL(event.target.files[0]); // Read the selected file as a Data URL
                    } </script>
                        <form id="regform" class="reg-form" action="php/update_account.php" method="POST" enctype="multipart/form-data">
                            <div class="error-messages"></div>
                        <div class="user-avatar">
                            <img id="imagePreview" src="<?php echo file_exists($profile_picture) ? htmlspecialchars($profile_picture) : 'staff_profile_picture/default.jpg'; ?>" alt="Profile Picture" class="preview-img">
                        </div>
                        <h3 class="user-name"><?php echo htmlspecialchars($fname . ' ' . $lname); ?></h3>
                        <p>
                            <label for=staffid">Staff ID<label>
                                <input type="text" name=staffid" id="staffid" class="form-control" value="<?php echo htmlspecialchars('S' .$staffID); ?>" readonly>
                        </p>
                        <p>
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" class="form-control" value="<?php echo htmlspecialchars($fname); ?>" readonly>
                        </p>
                        <p>
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" id="lname" class="form-control" value="<?php echo htmlspecialchars($lname); ?>" readonly>
                        </p>

                        <!-- Date of Birth -->
                        <p>
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="form-control" value="<?php echo htmlspecialchars($dob); ?>" readonly>
                        </p>

                        <!-- Address -->
                        <p>
                            <label for="addy">Address</label>
                            <input type="text" id="addy" name="addy" class="form-control" value="<?php echo htmlspecialchars($address); ?>">
                        </p>

                        <!-- Email -->
                        <p>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                        </p>

                        <!-- Phone -->
                        <p>
                            <label for="ph">Phone</label>
                            <input type="text" name="ph" id="ph" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                        </p>
                        
                        <p>
                            <label for="password">Password</label>
                            <div class="password-field">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter old password">
                                <i class="fa-solid fa-eye toggle-password" onclick="togglePasswordVisibility('password', this)"></i>
                            </div>

                            <label for="new-password">New Password</label>
                            <div class="password-field">
                                <input type="password" id="new-password" name="new-password" class="form-control" placeholder="Enter new password">
                                <i class="fa-solid fa-eye toggle-password" onclick="togglePasswordVisibility('new-password', this)"></i>
                            </div>

                            <div class="password-field">
                                <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Confirm password">
                                <i class="fa-solid fa-eye toggle-password" onclick="togglePasswordVisibility('confirm-password', this)"></i>
                            </div>
                        </p>

                        <!-- Profile Picture -->
                        <p>
                            <label for="profilePicture">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" id="profilePicture" accept="image/*" onchange="previewImage(event)">
                        </p>

                        <div class="text-right">
                        <button type="button" class="btn cancel-button" onclick="resetFormAndImage();">Cancel</button>
                        <button type="submit" class="btn update-button">Update</button>
                        </div>

                        <script>
                            // Store the initial profile picture URL in a JavaScript variable
                            const initialProfilePicture = "<?php echo file_exists($profile_picture) ? htmlspecialchars($profile_picture) : 'staff_profile_picture/default.jpg'; ?>";

                            function resetFormAndImage() {
                                // Reset form fields
                                document.getElementById('regform').reset();
                                
                                // Reset the image preview to the initial profile picture
                                document.getElementById('imagePreview').src = initialProfilePicture;
                            }
                        </script>

                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>

    <script src="javascript/account.js"></script>

</body>
</html>