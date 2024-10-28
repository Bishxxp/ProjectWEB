<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อฐานข้อมูล

// Query: ดึงรายการอุปกรณ์ที่สถานะเป็น 'unavailable'
$sql = "SELECT 
            l.id AS loan_id, 
            e.id AS equipment_id, 
            e.title, 
            c.name AS category, 
            l.loan_date, 
            l.return_date 
        FROM loans l
        JOIN equipment e ON l.equipment_id = e.id
        LEFT JOIN categories c ON e.category_id = c.id
        WHERE l.status = 'Unavailable'";
$stmt = $db->query($sql);
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Equipment</title>
</head>
<body>
    <h1>Return Equipment</h1>

    <!-- ตารางแสดงรายการอุปกรณ์ที่ยังไม่ถูกคืน -->
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Loan Date</th>
            <th>Return Date</th>
            <th>Action</th>
        </tr>
        <?php if (!empty($loans)): ?>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['loan_id']); ?></td>
                    <td><?php echo htmlspecialchars($loan['title']); ?></td>
                    <td><?php echo htmlspecialchars($loan['category']); ?></td>
                    <td><?php echo htmlspecialchars($loan['loan_date']); ?></td>
                    <td><?php echo htmlspecialchars($loan['return_date']); ?></td>
                    <td>
                        <form method="POST" action="process_return.php">
                            <input type="hidden" name="loan_id" value="<?php echo $loan['loan_id']; ?>">
                            <input type="hidden" name="equipment_id" value="<?php echo $loan['equipment_id']; ?>">
                            <input type="submit" value="Return">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No equipment to return.</td>
            </tr>
        <?php endif; ?>
    </table>
    <a href = 'dashboard.php'>Back</a>
</body>
</html>
