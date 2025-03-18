<?php
session_start();

// Include the config.php file for database connection
include 'php/config.php';

header('Content-Type: application/json');

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$oldPassword = $data['password'];

// Assuming the user email is stored in the session
$email = $_SESSION['email'];



// Fetch the stored password hash for the user
$sql = "SELECT password_hash FROM staff WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($currentPassword);
$stmt->fetch();
$stmt->close();

if (password_verify($oldPassword, $currentPassword)) {
    echo json_encode(['isValid' => true]);
} else {
    echo json_encode(['isValid' => false]);
}

$conn->close();
?>