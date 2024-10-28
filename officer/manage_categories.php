<?php
session_start();
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันจัดการหมวดหมู่
function addCategory($name) {
    global $db;
    $sql = "INSERT INTO categories (name) VALUES (:name)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();
}

function editCategory($id, $name) {
    global $db;
    $sql = "UPDATE categories SET name = :name WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();
}

function deleteCategory($id) {
    global $db;
    $sql = "DELETE FROM categories WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function getAllCategories() {
    global $db;
    $sql = "SELECT * FROM categories";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// จัดการคำขอจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        addCategory($name);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        editCategory($id, $name);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        deleteCategory($id);
    }
}

// ดึงข้อมูลหมวดหมู่ทั้งหมด
$categories = getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
</head>
<body>
    <h1>Manage Categories</h1>

    <!-- ฟอร์มเพิ่มหมวดหมู่ -->
    <form method="POST" action="">
        <label for="name">Category Name:</label>
        <input type="text" name="name" required>
        <input type="submit" name="add" value="Add Category">
    </form>

    <!-- ตารางแสดงข้อมูลหมวดหมู่ -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($categories as $category): ?>
        <tr>
            <td><?php echo $category['id']; ?></td>
            <td><?php echo $category['name']; ?></td>
            <td>
                <!-- ปุ่มลบหมวดหมู่ -->
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this category?');">
                </form>
                <!-- ลิงก์แก้ไข -->
                <a href="edit_category.php?id=<?php echo $category['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
