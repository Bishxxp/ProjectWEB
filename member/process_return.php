<?php
session_start();
require '../db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loan_id = $_POST['loan_id'];
    $equipment_id = $_POST['equipment_id'];

    try {
        // เริ่มต้น transaction เพื่อให้การเปลี่ยนแปลงทั้งหมดสำเร็จหรือล้มเหลวพร้อมกัน
        $db->beginTransaction();

        // ลบข้อมูลจาก loan_duration ที่เกี่ยวข้องกับ loan นี้
        $delete_duration_stmt = $db->prepare("DELETE FROM loan_duration WHERE loan_id = ?");
        $delete_duration_stmt->execute([$loan_id]);

        // ลบข้อมูลจาก loans
        $delete_loan_stmt = $db->prepare("DELETE FROM loans WHERE id = ?");
        $delete_loan_stmt->execute([$loan_id]);

        // อัปเดตสถานะอุปกรณ์ในตาราง equipment เป็น 'available'
        $update_equipment_stmt = $db->prepare("UPDATE equipment 
                                               SET status_id = (SELECT id FROM statuses WHERE name = 'available') 
                                               WHERE id = ?");
        $update_equipment_stmt->execute([$equipment_id]);

        // ยืนยันการเปลี่ยนแปลงทั้งหมด
        $db->commit();

        echo "Equipment returned and loan record deleted successfully!";
    } catch (PDOException $e) {
        // หากเกิดข้อผิดพลาด ยกเลิกการทำงานทั้งหมด
        $db->rollBack();
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