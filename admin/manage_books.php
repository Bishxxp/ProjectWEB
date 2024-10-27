<?php
// manage_books.php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ตรวจสอบการเพิ่มหนังสือ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category_id = $_POST['category_id'];
    $shelf_id = $_POST['shelf_id'];
    $status_id = $_POST['status_id'];

    $sql = "INSERT INTO books (title, author, category_id, shelf_id, status_id) VALUES (:title, :author, :category_id, :shelf_id, :status_id)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':shelf_id', $shelf_id);
    $stmt->bindParam(':status_id', $status_id);
    $stmt->execute();
}

// ดึงข้อมูลหนังสือ
$stmt = $db->query("SELECT * FROM books");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลหมวดหมู่
$categories = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
// ดึงข้อมูลชั้นวาง
$shelves = $db->query("SELECT * FROM shelves")->fetchAll(PDO::FETCH_ASSOC);
// ดึงข้อมูลสถานะ
$statuses = $db->query("SELECT * FROM statuses")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
</head>
<body>
    <h2>Manage Books</h2>

    <h3>Add Book</h3>
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" required>
        <br>
        <label for="author">Author:</label>
        <input type="text" name="author" required>
        <br>
        <label for="category_id">Category:</label>
        <select name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="shelf_id">Shelf:</label>
        <select name="shelf_id" required>
            <?php foreach ($shelves as $shelf): ?>
                <option value="<?php echo $shelf['id']; ?>"><?php echo htmlspecialchars($shelf['location']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="status_id">Status:</label>
        <select name="status_id" required>
            <?php foreach ($statuses as $status): ?>
                <option value="<?php echo $status['id']; ?>"><?php echo htmlspecialchars($status['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" name="add_book" value="Add Book">
    </form>

    <h3>Books List</h3>
    <ul>
        <?php foreach ($books as $book): ?>
            <li><?php echo htmlspecialchars($book['title']) . ' by ' . htmlspecialchars($book['author']); ?></li>
        <?php endforeach; ?>
    </ul>

    <a href="../logout.php">Logout</a>
</body>
</html>
