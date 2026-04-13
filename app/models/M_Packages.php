<?php
// Create this file: app/models/M_Packages.php

class M_Packages {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Get all packages
    public function getAllPackages() {
        // For now, return static data
        // Later you can implement database functionality
        return [
            'basic' => [
                'id' => 1,
                'name' => 'Basic',
                'monthly_fee' => 0,
                'commission_rate' => 8,
                'stadium_limit' => 3,
                'photos_limit' => 3,
                'videos_limit' => 3,
                'featured_limit' => 0,
                'support_level' => 'email',
                'status' => 'active',
                'users_count' => 25
            ],
            'standard' => [
                'id' => 2,
                'name' => 'Standard', 
                'monthly_fee' => 0,
                'commission_rate' => 12,
                'stadium_limit' => 6,
                'photos_limit' => 5,
                'videos_limit' => 5,
                'featured_limit' => 3,
                'support_level' => 'phone',
                'status' => 'active',
                'users_count' => 15
            ],
            'gold' => [
                'id' => 3,
                'name' => 'Gold',
                'monthly_fee' => 0,
                'commission_rate' => 20,
                'stadium_limit' => 999,
                'photos_limit' => 10,
                'videos_limit' => 5,
                'featured_limit' => 5,
                'support_level' => 'priority',
                'status' => 'active',
                'users_count' => 5
            ]
        ];
    }

    // Update package settings
    public function updatePackage($packageId, $packageData) {
        // TODO: Implement database update functionality
        // For now, return true as demo
        return true;
    }

    // Get package statistics
    public function getPackageStatistics() {
        return [
            'total_packages' => 3,
            'active_packages' => 3,
            'total_users' => 45,
            'monthly_revenue' => 125000,
            'avg_commission_rate' => 13.3
        ];
    }

    // Future database table structure for packages:
    /*
    CREATE TABLE packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        monthly_fee DECIMAL(10,2) DEFAULT 0,
        commission_rate DECIMAL(5,2) NOT NULL,
        stadium_limit INT NOT NULL,
        photos_limit INT NOT NULL,
        videos_limit INT NOT NULL,
        featured_limit INT NOT NULL,
        support_level ENUM('email', 'phone', 'priority', 'dedicated') NOT NULL,
        description TEXT,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE package_features (
        id INT AUTO_INCREMENT PRIMARY KEY,
        package_id INT NOT NULL,
        feature_name VARCHAR(100) NOT NULL,
        feature_value BOOLEAN DEFAULT TRUE,
        FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
    );

    CREATE TABLE user_packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        package_id INT NOT NULL,
        started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NULL,
        status ENUM('active', 'expired', 'cancelled') DEFAULT 'active',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
    );
    */
}
?>