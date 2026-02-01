<?php
include("Database.php");

$user_id = $_GET['user_id'];

$sql = "SELECT sched_id, description, schedule_date
        FROM schedules
        WHERE user_id = :user_id
        ORDER BY schedule_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $data
])