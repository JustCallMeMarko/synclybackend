<?php
header('Content-Type: application/json');
require 'Database.php';

/* Cleanup expired OTPs */
$conn->exec("DELETE FROM otps WHERE expiration_date < NOW()");

if (!isset($_POST['otp'], $_POST['email'])) {
    echo json_encode(["status" => "error", "message" => "OTP and email required"]);
    exit;
}

$otp   = $_POST['otp'];
$email = $_POST['email'];

/* Check OTP */
$stmt = $conn->prepare("
    SELECT otp_id 
    FROM otps 
    WHERE email = :email 
      AND user_otp = :otp 
      AND expiration_date >= NOW()
    ORDER BY otp_id DESC
    LIMIT 1
");

$stmt->execute([
    ':email' => $email,
    ':otp'   => $otp
]);

if ($stmt->rowCount() === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid or expired OTP"]);
    exit;
}

/* OTP is valid */
echo json_encode(["status" => "success", "message" => "OTP verified"]);
