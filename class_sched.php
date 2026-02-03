<?php
include("Database.php");

$user_id = $_GET['user_id'];

$sql = "SELECT sched_id, name, schedule_date
            FROM schedules
            WHERE sched_owner_id = :user_id
            AND schedule_date >= CURDATE()
            ORDER BY schedule_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>