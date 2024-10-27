// admin/manage_librarians.php
<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// เพิ่ม Librarian
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_librarian'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, 'librarian')";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
}

// แสดง Librarian ทั้งหมด
$stmt = $db->query("SELECT * FROM users WHERE role = 'librarian'");
$librarians = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- ฟอร์มเพิ่ม librarian -->
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="add_librarian" value="Add Librarian">
</form>

<!-- แสดงรายชื่อ Librarian -->
<ul>
    <?php foreach ($librarians as $librarian): ?>
        <li><?php echo htmlspecialchars($librarian['username']); ?></li>
    <?php endforeach; ?>
</ul>
