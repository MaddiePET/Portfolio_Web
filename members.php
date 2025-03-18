<?php
session_start(); // Start the session to access session variables

// Include the config.php file for database connection
include 'php/config.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email']; // Get email from session


if (isset($_GET['memberID'])) {
    $memberID = $_GET['memberID'];

    // Step 1: Delete related records in transaction_item first
    $deleteTransactionItemsSql = "DELETE FROM transaction_item WHERE transactionID IN (SELECT transactionID FROM sales_transactions WHERE memberID = ?)";
    $stmt = $conn->prepare($deleteTransactionItemsSql);
    $stmt->bind_param("i", $memberID);
    $stmt->execute();

    // Step 2: Delete related records in sales_transactions
    $deleteSalesTransactionsSql = "DELETE FROM sales_transactions WHERE memberID = ?";
    $stmt = $conn->prepare($deleteSalesTransactionsSql);
    $stmt->bind_param("i", $memberID);
    $stmt->execute();

    // Step 3: Now delete the member
    $deleteMemberSql = "DELETE FROM member WHERE memberID = ?";
    $stmt = $conn->prepare($deleteMemberSql);
    $stmt->bind_param("i", $memberID);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "M$memberID's records deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting member: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch staff details for header
$sql = "SELECT fname, profile_picture FROM staff WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($fname, $profile_picture);
$stmt->fetch();
$stmt->close();

// Handle add or update member requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $fname = $conn->real_escape_string($_POST['fname']);
            $lname = $conn->real_escape_string($_POST['lname']);
            $dob = $conn->real_escape_string($_POST['dob']);
            $gender = $conn->real_escape_string($_POST['gender']);
            $address = $conn->real_escape_string($_POST['addy']);
            $email = $conn->real_escape_string($_POST['email']);
            $phno = $conn->real_escape_string($_POST['ph']);
            $registration_date = date("Y-m-d");
        
            // Initialize message variable
            $_SESSION['message'] = ""; 
        
            // Check for duplicate name
            $nameCheckStmt = $conn->prepare("SELECT * FROM member WHERE fname = ? AND lname = ?");
            $nameCheckStmt->bind_param("ss", $fname, $lname);
            $nameCheckStmt->execute();
            $nameResult = $nameCheckStmt->get_result();
        
            if ($nameResult->num_rows > 0) {
                $_SESSION['message'] .= "A member with the same first and last name already exists.<br>";
            }
            $nameCheckStmt->close();
        
            // Check for duplicate email
            $emailCheckStmt = $conn->prepare("SELECT * FROM member WHERE email = ?");
            $emailCheckStmt->bind_param("s", $email);
            $emailCheckStmt->execute();
            $emailResult = $emailCheckStmt->get_result();
        
            if ($emailResult->num_rows > 0) {
                $_SESSION['message'] .= "A member with the same email already exists.<br>";
            }
            $emailCheckStmt->close();
        
            // Check for duplicate phone number
            $phoneCheckStmt = $conn->prepare("SELECT * FROM member WHERE phno = ?");
            $phoneCheckStmt->bind_param("s", $phno);
            $phoneCheckStmt->execute();
            $phoneResult = $phoneCheckStmt->get_result();
        
            if ($phoneResult->num_rows > 0) {
                $_SESSION['message'] .= "A member with the same phone number already exists.<br>";
            }
            $phoneCheckStmt->close();
        
            // Proceed to insert if there are no errors
            if (empty($_SESSION['message'])) {
                $stmt = $conn->prepare(
                    "INSERT INTO member (fname, lname, dob, gender, address, email, phno, registration_date)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param("ssssssss", $fname, $lname, $dob, $gender, $address, $email, $phno, $registration_date);
        
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Member has been registered successfully.";
                } else {
                    $_SESSION['message'] = "Failed to add member. Error: " . $stmt->error;
                }
        
                $stmt->close();
            }
        }elseif ($_POST['action'] == 'update') {
            $memberID = $conn->real_escape_string($_POST['memberID']);
            $email = $conn->real_escape_string($_POST['email']);
            $phno = $conn->real_escape_string($_POST['ph']);
            $address = $conn->real_escape_string($_POST['addy']);

            // Update member details
            $sql = "UPDATE member SET email='$email', phno='$phno', address='$address' WHERE memberID='$memberID'";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "M$memberID's details updated successfully.";
            } else {
                $_SESSION['message'] = "Failed to update member. Error: " . $conn->error;
            }
        }
    }
}

// Fetch members for display
$sql = "SELECT * FROM member";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Member Profiles Page">
    <meta name="keywords" content="grocery, members, profile">
    <meta name="author" content="Pookie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/styleformembers.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>Member Profiles | GotoGro </title>
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
        <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='notification'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']); 
            }
        ?>
    
        <!-- Members Table -->
        <section class="member-profiles">
            <h1>Member Profiles</h1>
            <div class="search-container">
                <div class="input-with-button">

                    <select id="searchType">
                        <option value="" disabled selected>By</option>
                        <option value="id">ID</option>
                        <option value="name">Name</option>
                    </select>

                    <input type="text" id="searchInput" placeholder="Search members...">
                    <button class="search-button" onclick="searchMembers()">
                    <img src="styles/images/membersearch.png" alt="Search">
                    </button>
                </div>

                <button class="add-member" id="addMember"><img src="styles/images/add-user.png" alt="Add Member">Add Member</button>
            </div>

            <?php
            // Fetch members from the database
            $sql = "SELECT * FROM member";
            $result = $conn->query($sql);
            
            ?>

            <table>
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Member Since</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): 
                            $memberID = htmlspecialchars($row['memberID']);
                            $name = htmlspecialchars($row['fname'] . " " .$row['lname']);
                            $dob = htmlspecialchars($row['dob']);
                            $gender = htmlspecialchars($row['gender']);
                            $address = htmlspecialchars($row['address']);
                            $email = htmlspecialchars($row['email']);
                            $phone = htmlspecialchars($row['phno']);
                            $regDate = htmlspecialchars($row['registration_date']);
                        ?>
                            <tr>
                                <td>M<?= $memberID ?></td>
                                <td><?= $name ?></td>
                                <td><?= $dob ?></td>
                                <td><?= $gender ?></td>
                                <td><?= $address ?></td>
                                <td><?= $email ?></td>
                                <td><?= $phone ?></td>
                                <td><?= $regDate ?></td>
                                <td>
                                    <button class="btn view-button" onclick="location.href='purchasehistory.php?memberID=<?= $memberID ?>'">
                                        <img src="styles/images/view.png" alt="View Purchase History">
                                    </button>
                                    <button class="btn edit-button" onclick="openUpdateForm(
                                        '<?= $memberID ?>',
                                        '<?= addslashes($row['fname']) ?>',
                                        '<?= addslashes($row['lname']) ?>',
                                        '<?= $dob ?>',
                                        '<?= $gender ?>',
                                        '<?= addslashes($address) ?>',
                                        '<?= $email ?>',
                                        '<?= $phone ?>',
                                        '<?= $regDate ?>'
                                    )">
                                        <img src="styles/images/edit.png" alt="Edit Member">
                                    </button>
                                    <button class="btn delete-button" onclick="if(confirm('Are you sure you want to delete this member?')) location.href='members.php?memberID=<?= $memberID ?>'">
                                        <img src="styles/images/delete.png" alt="Delete Member">
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9">No members found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php
            $conn->close();
            ?>
            <div class="form" id="regForm">
                <div class="error-messages"></div>
                    <div class="reg-form">
                        <span class="close-button" id="closeRegForm">&times;</span>
                        <h2>Registration Form</h2>
                        <form method="POST" action="members.php">
                            <input type="hidden" name="action" value="add">
                            <p>
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname" maxlength="30" placeholder="Enter first name" required>
                            </p>

                            <p>
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" maxlength="30" placeholder="Enter last name" required>
                            </p>

                            <p>
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" id="dob" required>
                            </p>
                            
                            <fieldset>
                                <legend>Gender</legend>
                                <div class="radio-group">
                                    <p>
                                        <input type="radio" id="male" name="gender" value="male">
                                        <label for="male">Male</label>
                                    </p>
                                    <p>
                                        <input type="radio" id="female" name="gender" value="female">
                                        <label for="female">Female</label>
                                    </p>
                                    <p>
                                        <input type="radio" id="non-binary" name="gender" value="non-binary">
                                        <label for="non-binary">Prefer Not to Say</label>
                                    </p>
                                </div>
                            </fieldset>

                            <p>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Enter email" required>
                            </p>

                            <p>
                                <label for="ph">Phone Number</label>
                                <input type="text" name="ph" id="ph" placeholder="Enter phone number" required>
                            </p>

                            <p>
                                <label for="addy">Address</label>
                                <input type="text" name="addy" id="addy" maxlength="200" placeholder="Enter address" required>
                            </p>
                            
                            <p>
                                <label for="membership_start">Date of Membership</label>
                                <input type="date" name="membership_start" id="membership_start" readonly>
                            </p>
                                    
                            <button type="submit" class="register-button">Register</button>
                        </form>
                    </div>
            </div>

            <div class="form" id="updateForm">
                <div class="uperror-messages"></div>
                    <div class="update-form">
                        <span class="close-button" id="closeUpdateForm">&times;</span>
                        <h2 id="updateFormTitle"></h2>
                        <form id="updForm" method="POST" action="members.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" id="update_member_id" name="memberID">

                            <label for="fname">First Name</label>
                            <input type="text" id="update_fname" name="fname" readonly>

                            <label for="lname">Last Name</label>
                            <input type="text" id="update_lname" name="lname" readonly>

                            <label for="dob">Date of Birth</label>
                            <input type="date" id="update_dob" name="dob" readonly>

                            <fieldset>
                                <legend>Gender</legend>
                                <label class="radio-label">
                                    <input type="radio" id="update_male" name="gender" value="male" disabled>
                                    <span class="radio-custom"></span> Male
                                </label>
                                <label class="radio-label">
                                    <input type="radio" id="update_female" name="gender" value="female" disabled>
                                    <span class="radio-custom"></span> Female
                                </label>
                                <label class="radio-label">
                                    <input type="radio" id="update_nonbinary" name="gender" value="non-binary" disabled>
                                    <span class="radio-custom"></span> Prefer Not to Say
                                </label>
                            </fieldset>


                            <label for="email">Email</label>
                            <input type="email" id="update_email" name="email">

                            <label for="ph">Phone Number</label>
                            <input type="text" id="update_ph" name="ph">

                            <label for="addy">Address</label>
                            <input type="text" id="update_addy" name="addy" maxlength="200">

                            <label for="membership_start">Date of Membership</label>
                            <input type="date" id="update_membership_start" name="membership_start" readonly>

                            <div >
                                <button type="submit" class="but update-button">Update</button>
                                <button type="button" class="but cancel-button" onclick="resetForm()">Cancel</button>
                            </div>
                        </form>
                    </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>

    <script src="javascript/members.js"></script>
</body>
</html>