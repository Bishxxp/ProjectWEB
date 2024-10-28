<?php
session_start();
include '../db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'member') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = :username, role = :role WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: manage_member.php?from=manage_member");
        exit;
    } else {
        echo "Error updating user!";
    }
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
        <label for="role">Role:</label>
        <select name="role">
            <option value="member" <?php echo $user['role'] == 'member' ? 'selected' : ''; ?>>Member</option>
        </select>
        <button type="submit">Update</button>
    </form>
    <a href="manage_member.php?from=manage_member">Cancel</a>
</body>
</html>
