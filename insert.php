<?php

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $parentId = !empty($_POST['parentId']) ? intval($_POST['parentId']) : null;

    $stmt = $conn->prepare("INSERT INTO Members (Name, ParentId) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $parentId);

    if ($stmt->execute()) {
        echo "Member added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
