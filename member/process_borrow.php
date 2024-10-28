<?php
session_start();
require '../db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name']; // เก็บชื่อผู้ใช้
    $equipment_id = $_POST['equipment_id'];
    $loan_date = $_POST['loan_date'];
    $return_date = $_POST['return_date'];

    // คำนวณระยะเวลาการยืม
    $duration_days = (strtotime($return_date) - strtotime($loan_date)) / (60 * 60 * 24); // คำนวณจำนวนวัน

    // Insert ลงในตาราง loans
    $stmt = $db->prepare("INSERT INTO loans (user_id, equipment_id, loan_date, return_date, status) 
                          VALUES (:user_id, :equipment_id, :loan_date, :return_date, 'Unavailable')");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':equipment_id', $equipment_id);
    $stmt->bindParam(':loan_date', $loan_date);
    $stmt->bindParam(':return_date', $return_date);
    
    if ($stmt->execute()) {
        // ดึง loan_id ที่เพิ่ง insert ลงไปในตาราง loans
        $loan_id = $db->lastInsertId();

        // Insert ลงในตาราง loan_duration เพื่อบันทึกระยะเวลาการยืม
        $durationStmt = $db->prepare("INSERT INTO loan_duration (loan_id, duration_days) VALUES (:loan_id, :duration_days)");
        $durationStmt->bindParam(':loan_id', $loan_id);
        $durationStmt->bindParam(':duration_days', $duration_days);
        $durationStmt->execute();

        // Update สถานะอุปกรณ์เป็น unavailable (status_id = 2)
        $updateStmt = $db->prepare("UPDATE equipment SET status_id = 2 WHERE id = :equipment_id");
        $updateStmt->bindParam(':equipment_id', $equipment_id);
        $updateStmt->execute();

        // หลังจากอัปเดตสถานะอุปกรณ์เรียบร้อย ให้ redirect ไปยังหน้าที่ต้องการ
        header("Location: borrow_equipment.php"); // ถ้ายืมสำเร็จ ไปยังหน้าสำเร็จ
    } else {
        echo "Error borrowing equipment.";
    }
}
?>
