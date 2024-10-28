<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
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

        header("Location: manage_admins.php");
        exit;
    }
} else {
    echo "Invalid request!";
}
?>

<h2>Reset Password</h2>
<form method="POST" action="">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required><br>
    <button type="submit">Reset Password</button>
</form>
