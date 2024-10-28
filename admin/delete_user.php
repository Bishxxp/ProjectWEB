<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ลบผู้ใช้ตาม ID
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        if ($_SESSION['role'] == 'admin') {
            header("Location: manage_admins.php");
        } elseif ($_SESSION['role'] == 'librarian') {
            header("Location: manage_librarians.php");
        } else {
            header("Location: manage_members.php");
        }
        exit;
    } else {
        echo "Error deleting user!";
    }
}
