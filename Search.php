<?php
    include "Database.php";

    $search = isset($_POST['search']) ? $_POST['search'] : "";

    $sql = "SELECT user_id, username, email, img_profile
            FROM users
            WHERE username LIKE :search OR email LIKE :search";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':search' => "%$search%"
    ]);

    echo json_encode($stmt->fetchAll());
?>