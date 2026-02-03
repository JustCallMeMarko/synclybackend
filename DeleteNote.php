<?php
header("Content-Type: application/json");

try {
    $conn = new PDO(
        "mysql:host=localhost;dbname=syncly;charset=utf8",
        "root",
        ""
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $note_id = $_POST['note_id'] ?? null;

    if ($note_id === null) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing note_id"
        ]);
        exit;
    }

    $stmt = $conn->prepare(
        "DELETE FROM notes WHERE note_id = :note_id"
    );
    $stmt->bindValue(":note_id", $note_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Note not found"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
