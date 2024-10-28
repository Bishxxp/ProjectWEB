<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อฐานข้อมูล

// ฟังก์ชันสำหรับยืมอุปกรณ์
function borrowEquipment($user_id, $equipment_id) {
    global $db;
    
    // ตรวจสอบว่าอุปกรณ์ยังว่าง (available) อยู่หรือไม่
    $sql = "SELECT * FROM equipment WHERE id = :equipment_id AND status_id = 1"; // 1: available
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':equipment_id', $equipment_id);
    $stmt->execute();
    $equipment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($equipment) {
        // เพิ่มข้อมูลการยืมในตาราง loans
        $sql = "INSERT INTO loans (user_id, equipment_id, status) VALUES (:user_id, :equipment_id, 'on loan')";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':equipment_id', $equipment_id);
        $stmt->execute();

        // เปลี่ยนสถานะอุปกรณ์เป็น 'unavailable'
        $sql = "UPDATE equipment SET status_id = 2 WHERE id = :equipment_id"; // 2: unavailable
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':equipment_id', $equipment_id);
        return $stmt->execute();
    } else {
        return false; // อุปกรณ์ไม่ว่างให้ยืม
    }
}

// ตรวจสอบการยืมอุปกรณ์
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // user_id ของสมาชิกที่ล็อกอิน
    $equipment_id = $_POST['equipment_id'];

    if (borrowEquipment($user_id, $equipment_id)) {
        echo "ยืมอุปกรณ์สำเร็จ!";
    } else {
        echo "อุปกรณ์นี้ไม่สามารถยืมได้!";
    }
}

// ดึงข้อมูลอุปกรณ์ทั้งหมดที่ยังว่างอยู่
$sql = "SELECT * FROM equipment WHERE status_id = 1"; // 1: available
$stmt = $db->query($sql);
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow Equipment</title>
</head>
<body>
    <h1>Borrow Equipment</h1>
    <form method="POST" action="">
        <label for="equipment_id">Select Equipment:</label>
        <select name="equipment_id" required>
            <?php foreach ($equipments as $equipment): ?>
                <option value="<?php echo $equipment['id']; ?>"><?php echo $equipment['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Borrow</button>
    </form>
</body>
</html>
