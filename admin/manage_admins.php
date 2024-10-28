<?php
session_start();
include '../db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch Admins
$sql = "SELECT * FROM users WHERE role IN ('admin')";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Manage Admins/Officer</h2>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['username'] ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> |
                <a href="delete_user.php?id=<?= $user['id'] ?>">Delete</a> |
                <a href="reset_password.php?id=<?= $user['id'] ?>">Reset Password</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- ฟอร์มเพิ่ม Admin -->
<form method="POST" action="add_user.php">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <label for="role">Role:</label>
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="officer">Officer</option>
    </select><br>

    <button type="submit">Add User</button>
    <a href="dashboard.php">Back to Dashboard</a>
</form>
