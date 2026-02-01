<?php
include("Database.php");

$user_id = $_GET['user_id'];

$sql = "SELECT task_id, name, due_date, status
        FROM tasks
        WHERE owner_id = :user_id AND status = 0
        ORDER BY due_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>