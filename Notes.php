<?php
    include("Database.php");

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
        
        $user_id = $_GET['user_id'];

        try {
            $sql = "SELECT note_id, note FROM notes WHERE user_id = :user_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($notes) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "Notes retrieved successfully",
                    "data" => $notes
                ]);
            } else {
                echo json_encode([
                    "status" => "success", 
                    "message" => "No notes found.",
                    "data" => []
                ]);
            }

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error", 
                "message" => "Database error: " . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Missing user_id parameter"
        ]);
    }
?>