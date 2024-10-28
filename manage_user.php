<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

// เพิ่มผู้ใช้ใหม่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$stmt = $db->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
</head>
<body>
    <h2>Manage Users</h2>

    <h3>Add User</h3>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <label for="role">Role:</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="officer">officer</option>
            <option value="member">Member</option>
        </select>
        <br>
        <input type="submit" name="add_user" value="Add User">
    </form>

    <h3>Users List</h3>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['username']); ?> - <?php echo htmlspecialchars($user['role']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
