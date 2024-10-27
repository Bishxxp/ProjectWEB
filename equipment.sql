-- สร้างฐานข้อมูล (ถ้ายังไม่มี)
CREATE DATABASE IF NOT EXISTS library_system;
USE library_system;

-- ตารางผู้ใช้ (users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'librarian', 'member') NOT NULL
);

-- ตารางหมวดหมู่ (categories)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- ตารางที่เก็บหนังสือ (shelves)
CREATE TABLE IF NOT EXISTS shelves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100) NOT NULL
);

-- ตารางสถานะ (statuses)
CREATE TABLE IF NOT EXISTS statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) NOT NULL UNIQUE
);

-- ตารางหนังสือ (books)
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category_id INT,
    shelf_id INT,
    status_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (shelf_id) REFERENCES shelves(id),
    FOREIGN KEY (status_id) REFERENCES statuses(id)
);

-- ตารางข้อมูลการยืม/คืน (borrow_return_records)
CREATE TABLE IF NOT EXISTS borrow_return_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    borrow_date DATE NOT NULL,
    return_date DATE,
    status ENUM('borrowed', 'returned') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- ตารางกำหนดจำนวนวันให้ยืม (loan_periods)
CREATE TABLE IF NOT EXISTS loan_periods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    days_allowed INT NOT NULL
);
