<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

// ข้อมูลที่ต้องการเพิ่ม
$username = 'admin';
$password = password_hash('12345678', PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
$role = 'admin';

// เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูล
$sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':role', $role);

// รันคำสั่ง SQL
if ($stmt->execute()) {
    echo "เพิ่มผู้ใช้เรียบร้อยแล้ว!";
} else {
    echo "เกิดข้อผิดพลาดในการเพิ่มผู้ใช้.";
}
?>
