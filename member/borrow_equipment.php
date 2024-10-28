<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อฐานข้อมูล

// Query: ดึงข้อมูลอุปกรณ์ที่มีสถานะ 'available'
$sql = "SELECT 
            e.id, 
            e.title, 
            c.name AS category, 
            s.name AS status 
        FROM equipment e
        LEFT JOIN categories c ON e.category_id = c.id
        LEFT JOIN statuses s ON e.status_id = s.id
        WHERE s.name = 'available'";
$stmt = $db->query($sql);
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Equipment</title>
</head>
<body>
    <h1>Borrow Equipment</h1>

    <!-- ตารางแสดงรายการอุปกรณ์ที่พร้อมให้ยืม -->
    <h2>Available Equipment</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
        </tr>
        <?php if (!empty($equipments)): ?>
            <?php foreach ($equipments as $equipment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($equipment['id']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['title']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['category']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No equipment available at the moment.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- ฟอร์มการยืมอุปกรณ์ -->
    <form method="POST" action="process_borrow.php" style="margin-top: 20px;"> <!-- เปลี่ยน action ไปที่ process_borrow.php -->
        <h2>Borrow Form</h2>
        <label>Your Name:</label>
        <input type="text" name="user_name" required><br>

        <label>Select Equipment:</label>
        <select name="equipment_id" required>
            <?php foreach ($equipments as $equipment): ?>
                <option value="<?php echo $equipment['id']; ?>">
                    <?php echo htmlspecialchars($equipment['title']) . " (" . htmlspecialchars($equipment['category']) . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Loan Date:</label>
        <input type="date" name="loan_date" required><br>

        <label>Return Date:</label>
        <input type="date" name="return_date" required><br>

        <input type="submit" value="Borrow Equipment">
    </form>
    <a href="dashboard.php?from=dashboard_member">Cancel</a>
</body>
</html>
