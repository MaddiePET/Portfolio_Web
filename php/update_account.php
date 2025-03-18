<?php
session_start(); // Start the session

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the config.php file for database connection
include 'config.php';

// Verify if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];  // Get the logged-in user's email
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $phone = $_POST['ph'];
    $address = $_POST['addy'];

    // Check for password fields
    $oldPassword = $_POST['password'] ?? null;
    $newPassword = $_POST['new-password'] ?? null;
    $confirmPassword = $_POST['confirm-password'] ?? null;

    // Initialize profile picture path
    $profilePicturePath = null;

    // Check if a new profile picture was uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "staff_profile_picture/";
        $fileName = uniqid() . '_' . basename($_FILES["profile_picture"]["name"]);
        $profilePicturePath = $targetDir . $fileName;

        // Check if the directory exists and create it if not
        if (!is_dir("../" . $targetDir)) {
            mkdir("../" . $targetDir, 0777, true);
        }

        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], "../" . $profilePicturePath)) {
            $_SESSION['message'] = "Error uploading profile picture.";
            header("Location: ../account.php");
            exit();
        }
    } else {
        // If no new file is uploaded, retrieve the existing path from the database
        $sql_existing = "SELECT profile_picture FROM staff WHERE email = ?";
        $stmt_existing = $conn->prepare($sql_existing);
        $stmt_existing->bind_param("s", $email);
        $stmt_existing->execute();
        $stmt_existing->bind_result($existingPicture);
        $stmt_existing->fetch();
        $stmt_existing->close();

        $profilePicturePath = $existingPicture ?: 'staff_profile_picture/default.jpg'; // Use existing or default
    }

    // Prepare SQL with an optional profile picture update
    $sql = "UPDATE staff SET fname = ?, lname = ?, dob = ?, phone = ?, address = ?";
    if ($profilePicturePath) {
        $sql .= ", profile_picture = ?";
    }
    $sql .= " WHERE email = ?";

    $stmt = $conn->prepare($sql);

    if ($profilePicturePath) {
        $stmt->bind_param("sssssss", $fname, $lname, $dob, $phone, $address, $profilePicturePath, $email);
    } else {
        $stmt->bind_param("ssssss", $fname, $lname, $dob, $phone, $address, $email);
    }

    // Execute the statement for account update
    if ($stmt->execute()) {
        $_SESSION['message'] = "Your account has been updated successfully!";

        // Handle password update if provided
        if ($oldPassword && $newPassword && $confirmPassword) {
            // Retrieve the current password from the database for verification
            $sql_pw_check = "SELECT password_hash FROM staff WHERE email = ?";
            $stmt_pw_check = $conn->prepare($sql_pw_check);
            $stmt_pw_check->bind_param("s", $email);
            $stmt_pw_check->execute();
            $stmt_pw_check->bind_result($currentPassword);
            $stmt_pw_check->fetch();
            $stmt_pw_check->close();

            // Verify old password
            if (password_verify($oldPassword, $currentPassword)) {
                // Check if new passwords match
                if ($newPassword === $confirmPassword) {
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $sql_update_pw = "UPDATE staff SET password_hash = ? WHERE email = ?";
                    $stmt_update_pw = $conn->prepare($sql_update_pw);
                    $stmt_update_pw->bind_param("ss", $hashedNewPassword, $email);
                    if ($stmt_update_pw->execute()) {
                        $_SESSION['message'] .= " Your password has been updated successfully!";
                    } else {
                        $_SESSION['message'] .= " Error updating password.";
                    }
                    $stmt_update_pw->close();
                } else {
                    $_SESSION['message'] .= " New passwords do not match.";
                }
            } else {
                $_SESSION['error_message'] = "Incorrect old password.";
                header("Location: ../account.php"); // Redirect back with error
                exit();
            }
        }
    } else {
        $_SESSION['message'] = "Error updating account: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect to account.php with the session message
    header("Location: ../account.php");
    exit();
}
?>