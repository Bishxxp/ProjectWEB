<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
    $check_sql = "SELECT * FROM users WHERE username = :username";
    $check_stmt = $db->prepare($check_sql);
    $check_stmt->bindParam(':username', $username);
    $check_stmt->execute();
    if ($check_stmt->rowCount() > 0) {
        echo "Username already exists!";
    } else {
        // เพิ่มข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล
        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        if ($stmt->execute()) {
            // แก้ไขตรงนี้เพื่อเปลี่ยนเส้นทางตาม role ที่เพิ่ม
            if ($role == 'admin') {
                header("Location: manage_admins.php"); // ไปหน้า Admin
            } elseif ($role == 'officer') {
                header("Location: manage_admins.php"); // ไปหน้า Librarian
            } elseif ($role == 'member') {
                header("Location: manage_members.php"); // ไปหน้า Member
            }
            exit;
        } else {
            echo "Error adding user!";
        }
    }
}
?>

<h2>Add New User</h2>
<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <label for="role">Role:</label>
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="officer">Officer</option>
        <option value="member">Member</option>
    </select><br>

    <button type="submit">Add User</button>
</form>
