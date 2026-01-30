<?php
    include "Database.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

           if ($stmt->execute()) {
                $last_id = $conn->lastInsertId();

                echo json_encode([
                    "status" => "success", 
                    "message" => "User registered successfully",
                    "user_id" => $last_id
                ]);
            }
        } catch (PDOException $e) {
           echo json_encode(["status" => "error", "message" => "User already exists"]);
        }
    }
?>