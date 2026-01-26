<?php
    include "Database.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            $stmt->execute();


            echo json_encode(["status" => "success", "message" => "User registered successfully"]);
        } catch (PDOException $e) {
           echo json_encode(["status" => "error", "message" => "User already exists"]);
        }
    }
?>