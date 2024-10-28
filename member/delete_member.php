<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'member') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: manage_member.php?from=manage_member");
        exit;
    } else {
        echo "Error deleting user!";
    }
}
?>
