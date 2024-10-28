<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // เริ่มต้น session

// ตรวจสอบการเข้าถึงเฉพาะผู้ที่มีบทบาท officer
if ($_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit;
}

// เชื่อมต่อฐานข้อมูล
include '../db.php';

// รับค่าค้นหาจากฟอร์ม
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query: ดึงรายการอุปกรณ์พร้อมสถานะและ loan duration
$sql = "SELECT 
            e.id AS equipment_id, 
            e.title, 
            c.name AS category, 
            s.name AS status, 
            l.loan_date, 
            l.return_date, 
            DATEDIFF(l.return_date, l.loan_date) AS loan_duration
        FROM equipment e
        LEFT JOIN categories c ON e.category_id = c.id
        LEFT JOIN statuses s ON e.status_id = s.id
        LEFT JOIN loans l ON e.id = l.equipment_id
        WHERE e.title LIKE :search OR e.id LIKE :search
        ORDER BY e.id ASC";
$stmt = $db->prepare($sql);
$search_param = '%' . $search . '%';
$stmt->bindParam(':search', $search_param);
$stmt->execute();
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Loan Status</title>
</head>
<body>
    <h1>Manage Loan Status</h1>

    <h2>Search Equipment</h2>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by ID or Title" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>

    <h2>Equipment List</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Loan Date</th>
            <th>Return Date</th>
            <th>Loan Duration (Days)</th>
        </tr>
        <?php if (!empty($equipments)): ?>
            <?php foreach ($equipments as $equipment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($equipment['equipment_id']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['title']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['category']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['status']); ?></td>
                    <td><?php echo htmlspecialchars($equipment['loan_date'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($equipment['return_date'] ?? 'N/A'); ?></td>
                    <td>
                        <?php 
                            echo $equipment['loan_duration'] !== null 
                                ? htmlspecialchars($equipment['loan_duration']) . " days" 
                                : 'N/A'; 
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No equipment found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
