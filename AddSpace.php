<?php
include "Database.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ensure we handle the 'id' vs 'user_id' naming from your Android app
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';

    if (empty($name) || empty($user_id)) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    try {
        // Start a transaction to ensure both inserts happen or neither does
        $conn->beginTransaction();

        // 1. Insert into Spaces
        $sqlSpace = "INSERT INTO spaces (owner_id, space_name, due_date) VALUES (:id, :name, :date)";
        $stmtSpace = $conn->prepare($sqlSpace);
        $stmtSpace->bindParam(':id', $user_id);
        $stmtSpace->bindParam(':name', $name);
        $stmtSpace->bindParam(':date', $date);

        if ($stmtSpace->execute()) {
            $newSpaceId = $conn->lastInsertId();

            $sqlMember = "INSERT INTO members (space_id, user_id) VALUES (:space_id, :user_id)";
            $stmtMember = $conn->prepare($sqlMember);
            $stmtMember->bindParam(':space_id', $newSpaceId);
            $stmtMember->bindParam(':user_id', $user_id);
            
            if ($stmtMember->execute()) {
                $conn->commit(); 
                echo json_encode([
                    "status" => "success",
                    "message" => "Space created and member joined!"
                ]);
            } else {
                $conn->rollBack();
                echo json_encode(["status" => "error", "message" => "Failed to add member"]);
            }
        }
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
}
?>