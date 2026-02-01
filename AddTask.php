<?php
include "Database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $name = $_POST['name'];
    $user_id = $_POST['id'];
    $date = $_POST['date'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {

        $sql = "INSERT INTO tasks (owner_id, name, status, due_date)
            VALUES(:id, :name, 0, :date);";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':date', $date);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Task added successfully"
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Something went wrong"]);
    }
}
?>