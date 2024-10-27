<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // เริ่มต้น session

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // ถ้าไม่มีการล็อกอิน ให้กลับไปที่หน้าล็อกอิน
    exit;
}

// เชื่อมต่อกับฐานข้อมูล
include '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ดึงข้อมูลจากฐานข้อมูลหรือตัวแปร session
$user_id = $_SESSION['user_id'];
$username = ''; // เริ่มต้นชื่อผู้ใช้
$role = $_SESSION['role']; // ดึงบทบาทของผู้ใช้

// ตรวจสอบข้อมูลผู้ใช้
$stmt = $db->prepare("SELECT username FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $username = $user['username']; // กำหนดชื่อผู้ใช้
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to Admin Dashboard</h1>
    <p>Hello, <?php echo htmlspecialchars($username); ?>!</p>
    <p>Your role: <?php echo htmlspecialchars($role); ?></p>

    <h2>Management</h2>
    <ul>
        <li><a href="manage_librarians.php">Manage Librarians</a></li>
        <li><a href="manage_books.php">Manage Books</a></li>
        <li><a href="manage_members.php">Manage Members</a></li>
    </ul>

    <a href="../logout.php">Logout</a> <!-- ลิงก์สำหรับออกจากระบบ -->
</body>
</html>
