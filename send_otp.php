<?php
header('Content-Type: application/json');

require 'Database.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/mailer_config.php';

if (!isset($_POST['email'])) {
    echo json_encode(["status" => "error", "message" => "Email required"]);
    exit;
}

$email = trim($_POST['email']);
$otp   = random_int(10000, 99999);

try {
    $mail = getMailer();
    $mail->addAddress($email);
    $mail->Subject = 'Syncly Password Reset OTP';
    $mail->Body    = "<h2>Your OTP</h2><h1>$otp</h1><p>Expires in 5 minutes.</p>";

    if($mail->send()) {
        /* Insert OTP only after successful mail send to keep DB clean */
        $stmt = $conn->prepare("
            INSERT INTO otps (user_otp, email, expiration_date)
            VALUES (:otp, :email, DATE_ADD(NOW(), INTERVAL 5 MINUTE))
        ");

        $stmt->execute([
            ':otp'   => $otp,
            ':email' => $email
        ]);

        echo json_encode([
            "status"  => "success",
            "message" => "OTP sent and stored"
        ]);
    }

} catch (Exception $e) {
    // Return the actual error so you can see why it's failing
    echo json_encode([
        "status"  => "error",
        "message" => "Mailer Error: " . $mail->ErrorInfo
    ]);
}