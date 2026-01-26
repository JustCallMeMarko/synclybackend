<?php
    include("Database.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "SELECT user_id FROM users WHERE email = :email AND password = :pass";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pass', $hashedPassword);

            $stmt->execute();


            echo json_encode(["status" => "success", "message" => "User logged in successfully"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "User does not exist"]);
        }
    }
?>