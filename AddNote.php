<?php
header("Content-Type: application/json");
include("Database.php");

try {

    if (!isset($_POST['user_id']) || !isset($_POST['note'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing parameters"
        ]);
        exit;
    }

    $user_id = $_POST['user_id'];
    $note = $_POST['note'];

    $stmt = $conn->prepare(
        "INSERT INTO notes (user_id, note) VALUES (:user_id, :note)"
    );
    $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->bindValue(":note", $note, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode([
        "status"  => "success",
        "note_id" => $conn->lastInsertId()
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage()
    ]);
}
?>
