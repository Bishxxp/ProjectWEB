<?php
session_start();
require '../db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $equipment_id = $_POST['equipment_id'];
    $loan_date = $_POST['loan_date'];
    $return_date = $_POST['return_date'];

    // คำนวณจำนวนวัน (duration_days) ระหว่าง loan_date และ return_date
    $loan_date_obj = new DateTime($loan_date);
    $return_date_obj = new DateTime($return_date);
    $duration_days = $loan_date_obj->diff($return_date_obj)->days;

    try {
        // ตรวจสอบว่าผู้ใช้มีอยู่ในระบบหรือไม่
        $user_stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $user_stmt->execute([$user_name]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['id'];

            // เพิ่มข้อมูลการยืมลงในตาราง loans
            $loan_stmt = $db->prepare("INSERT INTO loans 
                                       (user_id, equipment_id, loan_date, return_date, status) 
                                       VALUES (?, ?, ?, ?, 'Unavailable')");
            $loan_stmt->execute([$user_id, $equipment_id, $loan_date, $return_date]);

            // ดึง loan_id ของการยืมล่าสุด
            $loan_id = $db->lastInsertId();

            // เก็บข้อมูลระยะเวลาในตาราง loan_duration
            $duration_stmt = $db->prepare("INSERT INTO loan_duration (loan_id, duration_days) 
                                           VALUES (?, ?)");
            $duration_stmt->execute([$loan_id, $duration_days]);

            // อัปเดตสถานะอุปกรณ์เป็น 'unavailable'
            $update_equipment_stmt = $db->prepare("UPDATE equipment 
                                                   SET status_id = (SELECT id FROM statuses WHERE name = 'unavailable') 
                                                   WHERE id = ?");
            $update_equipment_stmt->execute([$equipment_id]);

            echo "Equipment borrowed successfully! Duration: $duration_days days.";
        } else {
            echo "User not found!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <a href = 'return_equipment.php'>Back</a>
</body>
</html>