<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: login.php"); // ถ้าไม่ใช่สมาชิก ให้กลับไปที่หน้า login
    exit();
}
?>

<h1>Member Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['username']; ?> (Member)</p>
<!-- เนื้อหาอื่นๆ สำหรับสมาชิก เช่น ประวัติการยืม-คืน -->
<a href="../logout.php">Logout</a>
