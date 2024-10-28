<?php
session_start();
if ($_SESSION['role'] !== 'librarian') {
    header("Location: login.php"); // ถ้าไม่ใช่บรรณารักษ์ ให้กลับไปที่หน้า login
    exit();
}
?>

<h1>Librarian Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['username']; ?> (Librarian)</p>
<!-- เนื้อหาอื่นๆ สำหรับบรรณารักษ์ เช่น จัดการหนังสือ -->
<a href="../logout.php">Logout</a>
