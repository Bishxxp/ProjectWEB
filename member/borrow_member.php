<?php
session_start();
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

require '../db.php'; // เชื่อมต่อกับฐานข้อมูล

// ดึงประวัติการยืมของสมาชิกคนปัจจุบัน
$user_id = $_SESSION['user_id'];
$sql = "SELECT e.title, l.loan_date, l.return_date, l.status 
        FROM loans l
        JOIN equipment e ON l.equipment_id = e.id
        WHERE l.user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$borrow_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow History</title>
</head>
<body>
    <h1>Your Borrowing History</h1>

    <table border="1">
        <tr>
            <th>Equipment Title</th>
            <th>Loan Date</th>
            <th>Return Date</th>
            <th>Status</th>
        </tr>
        <?php foreach ($borrow_history as $history): ?>
        <tr>
            <td><?php echo $history['title']; ?></td>
            <td><?php echo $history['loan_date']; ?></td>
            <td><?php echo $history['return_date'] ? $history['return_date'] : 'Not returned'; ?></td>
            <td><?php echo $history['status']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php?from=dashboard_member">Cancel</a>
</body>
</html>
