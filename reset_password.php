<?php
header('Content-Type: application/json');
require 'Database.php';

if (!isset($_POST['email'], $_POST['new_password'])) {
    echo json_encode(["status" => "error", "message" => "Email and password required"]);
    exit;
}

$email = $_POST['email'];
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

/* Update password */
$stmt = $conn->prepare("
    UPDATE users 
    SET password = :password 
    WHERE email = :email
");

$stmt->execute([
    ':password' => $new_password,
    ':email'    => $email
]);

echo json_encode([
    "status"  => "success",
    "message" => "Password updated"
]);
