<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php"); // ถ้าไม่ใช่สมาชิก ให้กลับไปที่หน้า login
    exit();
}
include '../db.php';
?>

<h1>Member Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['username']; ?> (Member)</p>
<ul>
    <li><a href="manage_member.php">Edit Profile</a></li>
    <li><a href="borrow_equipment.php">Borrow Equipment</a></li>
    <li><a href="borrow_member.php">Borrow History</a></li>
    <li><a href="return_equipment.php">Return Equipment</a></li>
</ul>
<a href="../logout.php">Logout</a>
