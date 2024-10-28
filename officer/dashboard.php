<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php"); // ถ้าไม่ใช่เจ้าหน้าที่ ให้กลับไปที่หน้า login
    exit();
}
include '../db.php';
?>
<!-- รายการฟังก์ชันต่าง ๆ ที่เจ้าหน้าที่สามารถทำได้ -->
<h1>Officer Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['username']; ?> (officer)</p>
<ul>
    <li><a href="manage_profile.php">Edit Profile</a></li>
    <li><a href="manage_equipment.php">Manage Equipment Data</a></li>
    <li><a href="manage_categories.php">Manage Equipment Categories</a></li>
    <li><a href="update_status.php">Update Equipment Status</a></li>
</ul>

<a href="../logout.php">Logout</a>
