<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'member') {
    header("Location: ../login.php");
    exit;
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$sql = "SELECT * FROM users WHERE role IN ('member')";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Member</title>
</head>
<body>
    <h1>Manage Member</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
                <a href="edit_member.php?id=<?php echo $user['id']; ?>">Edit</a>
                <a href="delete_member.php?id=<?php echo $user['id']; ?>">Delete</a>
                <a href="reset_member.php?id=<?php echo $user['id']; ?>">Reset Password</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
