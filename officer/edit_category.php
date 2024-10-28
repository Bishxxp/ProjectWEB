<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันแก้ไขหมวดหมู่
function editCategory($id, $name) {
    global $db;
    $sql = "UPDATE categories SET name = :name WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();
}

// ดึงข้อมูลหมวดหมู่ที่ต้องการแก้ไข
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    
    // เรียกใช้ฟังก์ชันเพื่อแก้ไขข้อมูลหมวดหมู่
    if (editCategory($id, $name)) {
        header("Location: manage_categories.php");
        exit();
    } else {
        echo "<p>Error updating category.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
</head>
<body>
    <h1>Edit Category</h1>
    <form method="POST" action="">
        <label for="name">Category Name:</label>
        <input type="text" name="name" value="<?php echo $category['name']; ?>" required>
        <input type="submit" value="Update Category">
    </form>
</body>
</html>
