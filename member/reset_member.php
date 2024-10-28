<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = $_POST['new_password'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // อัปเดตรหัสผ่านในฐานข้อมูล
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->execute()) {
            header("Location: manage_member.php?from=manage_member");
            exit;
        } else {
            echo "Error resetting password!";
        }
    }
}
?>

<h2>Reset Password</h2>
<form method="POST" action="">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required><br>
    <button type="submit">Reset Password</button>
</form>