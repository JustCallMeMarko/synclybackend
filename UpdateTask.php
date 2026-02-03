<?php
include("Database.php");

$task_id = $_POST['task_id'];
$status = $_POST['status'];

$sql = "UPDATE tasks
        SET status = :status
        WHERE task_id = :task_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
$stmt->bindParam(':status', $status, PDO::PARAM_INT);
$stmt->execute();

echo json_encode([
    "status" => "success"
]);
?>