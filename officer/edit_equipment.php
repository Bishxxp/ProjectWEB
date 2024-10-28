<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันการแก้ไขอุปกรณ์
function editEquipment($id, $name, $category) {
    global $db;
    $sql = "UPDATE equipment SET title = :name, category_id = :category WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category', $category);
    return $stmt->execute();
}

// ดึงข้อมูลอุปกรณ์ที่ต้องการแก้ไข
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM equipment WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $equipment = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    
    // เรียกใช้ฟังก์ชันเพื่อแก้ไขข้อมูลอุปกรณ์
    if (editEquipment($id, $name, $category)) {
        header("Location: manage_equipment.php");
        exit(); // ออกจากสคริปต์หลังจาก redirect
    } else {
        echo "<p>Error updating equipment.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment</title>
</head>
<body>
    <h1>Edit Equipment</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $equipment['id']; ?>">
        <label for="name">Equipment Name:</label>
        <input type="text" name="name" value="<?php echo $equipment['title']; ?>" required>
        <label for="category">Category ID:</label>
        <input type="number" name="category" value="<?php echo $equipment['category_id']; ?>" required> <!-- ใช้ category_id -->
        <input type="submit" value="Update Equipment">
    </form>
</body>
</html>
