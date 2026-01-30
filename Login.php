<?php
    include("Database.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "SELECT user_id, password FROM users WHERE email = :email";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':email', $email);

            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "User logged in successfully",
                    "user_id" => $user['user_id']
                ]);
            } else {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Invalid email or password"
                ]);
            }

        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "User does not exist"]);
        }
    }
?>