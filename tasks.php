<?php
include("Database.php");

$user_id = $_GET['user_id'];

$sql = "SELECT task_id, description, due_date, status
        FROM tasks
        WHERE to_id = :user_id
        ORDER BY due_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $data
])