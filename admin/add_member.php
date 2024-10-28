<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header("Location: manage_members.php?from=manage_member");
        exit;
    } else {
        echo "Error adding member!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Member</title>
</head>
<body>
    <h1>Add Member</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <label for="role">Role:</label>
        <select name="role">
            <option value="member">Member</option>
        </select>
        <button type="submit">Add Member</button>
    </form>
    <a href="manage_members.php?from=manage_member">Cancel</a>
</body>
</html>
