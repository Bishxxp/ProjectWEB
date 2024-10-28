<?php
// ตั้งค่าเกี่ยวกับฐานข้อมูล
$host = 'localhost'; // ชื่อโฮสต์
$dbname = 'equipmentDB'; // ชื่อฐานข้อมูล
$username = 'root'; // ชื่อผู้ใช้
$password = '12345678'; // รหัสผ่านของผู้ใช้

try {
    // สร้างการเชื่อมต่อกับฐานข้อมูล
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // ตั้งค่าการแสดงข้อผิดพลาด
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ตรวจสอบการเชื่อมต่อ
    if ($db instanceof PDO) {
        // ทำงานกับ $db ได้ตามปกติ
    } else {
        die('Database connection failed.');
    }
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
?>
