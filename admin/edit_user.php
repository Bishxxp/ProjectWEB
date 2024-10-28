<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // ดึงข้อมูลผู้ใช้ตาม ID
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found!";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = :username, role = :role WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        if ($role == 'admin') {
            header("Location: manage_admins.php");
        } elseif ($role == 'librarian') {
            header("Location: manage_librarians.php");
        } else {
            header("Location: manage_members.php");
        }
        exit;
    } else {
        echo "Error updating user!";
    }
}
?>

<h2>Edit User</h2>
<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" name="username" value="<?= $user['username'] ?>" required><br>

    <label for="role">Role:</label>
    <select name="role" required>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="librarian" <?= $user['role'] == 'librarian' ? 'selected' : '' ?>>Librarian</option>
        <option value="member" <?= $user['role'] == 'member' ? 'selected' : '' ?>>Member</option>
    </select><br>

    <button type="submit">Update User</button>
</form>
