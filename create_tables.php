<?php

// إنشاء قاعدة البيانات والجداول مباشرة
try {
    // محاولة الاتصال بقاعدة البيانات
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // إنشاء قاعدة البيانات إذا لم تكن موجودة
    $pdo->exec("CREATE DATABASE IF NOT EXISTS sahm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE sahm");
    
    echo "Database created/selected successfully!\n";
    
    // إنشاء جدول surveys
    $surveysSql = "CREATE TABLE IF NOT EXISTS surveys (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        survey_number VARCHAR(255) UNIQUE,
        work_order_id BIGINT UNSIGNED,
        created_by BIGINT UNSIGNED NULL,
        assigned_to BIGINT UNSIGNED NULL,
        status VARCHAR(255) DEFAULT 'pending',
        survey_type VARCHAR(255) DEFAULT 'general',
        description TEXT NULL,
        survey_date DATE NULL,
        survey_data JSON NULL,
        city VARCHAR(255) NULL,
        district VARCHAR(255) NULL,
        address TEXT NULL,
        latitude DECIMAL(10,8) NULL,
        longitude DECIMAL(11,8) NULL,
        requires_action BOOLEAN DEFAULT FALSE,
        recommendations TEXT NULL,
        findings JSON NULL,
        start_coordinates VARCHAR(255) NULL,
        end_coordinates VARCHAR(255) NULL,
        has_obstacles BOOLEAN DEFAULT FALSE,
        obstacles_notes TEXT NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )";
    
    $pdo->exec($surveysSql);
    echo "Surveys table created successfully!\n";
    
    // إنشاء جدول work_order_files
    $filesSql = "CREATE TABLE IF NOT EXISTS work_order_files (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        work_order_id BIGINT UNSIGNED,
        survey_id BIGINT UNSIGNED NULL,
        filename VARCHAR(255),
        original_filename VARCHAR(255),
        file_path VARCHAR(255),
        file_type VARCHAR(255) NULL,
        file_size INT NULL,
        file_category VARCHAR(255) NULL,
        attachment_type VARCHAR(255) NULL,
        file_name VARCHAR(255),
        mime_type VARCHAR(255) NULL,
        description TEXT NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($filesSql);
    echo "Work_order_files table created successfully!\n";
    
    // إنشاء جدول work_orders إذا لم يكن موجوداً
    $workOrdersSql = "CREATE TABLE IF NOT EXISTS work_orders (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        work_order_number VARCHAR(255) UNIQUE,
        title VARCHAR(255),
        description TEXT NULL,
        status VARCHAR(255) DEFAULT 'pending',
        created_by BIGINT UNSIGNED NULL,
        assigned_to BIGINT UNSIGNED NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($workOrdersSql);
    echo "Work_orders table created successfully!\n";
    
    // إنشاء جدول users إذا لم يكن موجوداً
    $usersSql = "CREATE TABLE IF NOT EXISTS users (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        password VARCHAR(255),
        is_admin BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($usersSql);
    echo "Users table created successfully!\n";
    
    // إدراج مستخدم تجريبي
    $checkUser = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'admin@sahm.com'");
    if ($checkUser->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO users (name, email, password, is_admin) VALUES ('Admin', 'admin@sahm.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', TRUE)");
        echo "Admin user created successfully!\n";
    }
    
    // إدراج أمر عمل تجريبي
    $checkWorkOrder = $pdo->query("SELECT COUNT(*) FROM work_orders WHERE id = 1");
    if ($checkWorkOrder->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO work_orders (id, work_order_number, title, description, created_by) VALUES (1, 'WO-001', 'أمر عمل تجريبي', 'وصف أمر العمل التجريبي', 1)");
        echo "Test work order created successfully!\n";
    }
    
    echo "All tables created successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 