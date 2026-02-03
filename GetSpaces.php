<?php
include("Database.php");

// Ensure the response is always treated as JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

    if (!$user_id) {
        echo json_encode(["status" => "error", "message" => "User ID is required"]);
        exit;
    }

    try {
        // We select space details by joining 'spaces' (s) with 'members' (m)
        // This finds every space where the user is listed as a member
        $sql = "SELECT s.space_id, s.space_name, s.due_date 
                FROM spaces s
                INNER JOIN members m ON s.space_id = m.space_id
                WHERE m.member_user_id = :user_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        $spaces = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Always return success so the Android app doesn't trigger an error 
        // Toast just because a user hasn't joined any spaces yet.
        echo json_encode([
            "status" => "success", 
            "spaces" => $spaces // This will be an empty array [] if no spaces are found
        ]);

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error occurred"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>