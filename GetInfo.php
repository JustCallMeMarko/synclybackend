<?php
    include("Database.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $id = $_POST['user_id'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "SELECT first_name, last_name, email FROM users WHERE user_id = :user_id";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "User Info retrieved successfully",
                    "first_name" => $user['first_name'],
                    "last_name" => $user['last_name'],
                    "email" => $user['email']
                ]);
            } else {
                echo json_encode([
                    "status" => "error", 
                    "message" => "User not found"
                ]);
            }

        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "User does not exist"]);
        }
    }
?>