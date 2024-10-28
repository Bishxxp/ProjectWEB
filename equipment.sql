-- สร้างฐานข้อมูล (ถ้ายังไม่มี)
CREATE DATABASE IF NOT EXISTS equipmentDB;
USE equipmentDB;

-- 1. ตารางบุคคลากร/สมาชิก: เก็บข้อมูลของผู้ดูแลระบบและสมาชิก
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','officer', 'member') NOT NULL
);

-- 2. ตารางหมวดหมู่: จัดเก็บข้อมูลหมวดหมู่ของอุปกรณ์
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- 4. ตารางสถานะ: เก็บข้อมูลสถานะของการยืมอุปกรณ์
CREATE TABLE IF NOT EXISTS statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- 5. ตารางอุปกรณ์: บันทึกรายละเอียดของอุปกรณ์แต่ละชิ้น
CREATE TABLE IF NOT EXISTS equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category_id INT,
    status_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (status_id) REFERENCES statuses(id)
);

-- 6. ตารางเก็บข้อมูลการยืม/คืน: บันทึกประวัติการยืมและคืนอุปกรณ์
CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    equipment_id INT,
    loan_date DATE,
    return_date DATE,
    status ENUM('Available', 'Unavailable') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(id)
);

-- 7. ตารางกำหนดจำนวนวันให้ยืม: เก็บข้อมูลเกี่ยวกับจำนวนวันที่อนุญาตให้ยืมอุปกรณ์
CREATE TABLE IF NOT EXISTS loan_duration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loan_id INT,
    duration_days INT NOT NULL,
    FOREIGN KEY (loan_id) REFERENCES loans(id)
);
