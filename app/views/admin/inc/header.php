<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> - <?php echo SITENAME; ?> Admin</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>BookMyGround.lk</h2>
                <span class="admin-badge">Admin Panel</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin" class="nav-link">
                            <span class="nav-icon">📊</span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/users" class="nav-link">
                            <span class="nav-icon">👥</span>
                            <span class="nav-text">User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/bookings" class="nav-link">
                            <span class="nav-icon">📅</span>
                            <span class="nav-text">Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/messages" class="nav-link">
                            <span class="nav-icon">💬</span>
                            <span class="nav-text">Messages</span>
                            <span class="badge">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/packages" class="nav-link">
                            <span class="nav-icon">📦</span>
                            <span class="nav-text">Stadium Owner Packages</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/rental_packages" class="nav-link">
                            <span class="nav-icon">🏪</span>
                            <span class="nav-text">Rental Service Owner Packages</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/rentalShops" class="nav-link">
                            <span class="nav-icon">⚽</span>
                            <span class="nav-text">Rental Shops</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/stadiumListings" class="nav-link">
                            <span class="nav-icon">🏟️</span>
                            <span class="nav-text">Stadium Listings</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/payouts" class="nav-link">
                            <span class="nav-icon">💰</span>
                            <span class="nav-text">Payouts</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/refunds" class="nav-link">
                            <span class="nav-icon">🔔</span>
                            <span class="nav-text">Refund Requests</span>
                            <span class="badge">5</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/advertisements" class="nav-link">
                            <span class="nav-icon">📢</span>
                            <span class="nav-text">Advertisements</span>
                            <span class="badge">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/reviews" class="nav-link">
                            <span class="nav-icon">⭐</span>
                            <span class="nav-text">Stadium Reviews</span>
                            <span class="badge">12</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/newsletter" class="nav-link">
                            <span class="nav-icon">📧</span>
                            <span class="nav-text">Email Newsletter</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/content" class="nav-link">
                            <span class="nav-icon">⚙️</span>
                            <span class="nav-text">Home Page Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/faq" class="nav-link">
                            <span class="nav-icon">❓</span>
                            <span class="nav-text">FAQ</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/blog" class="nav-link">
                            <span class="nav-icon">📝</span>
                            <span class="nav-text">Blog</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/admin/contact" class="nav-link">
                            <span class="nav-icon">📞</span>
                            <span class="nav-text">Contact</span>
                        </a>
                    </li>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-profile">
                    <div class="profile-info">
                        <h4>Administrator</h4>
                        <p>admin@bookmyground.lk</p>
                    </div>
                </div>
                <a href="<?php echo URLROOT; ?>/admin/logout" class="logout-btn">Logout</a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-container">
            <!-- Top Navigation -->
            <header class="top-header">
                <div class="header-left">
                    <button class="sidebar-toggle">☰</button>
                    <div class="breadcrumb">
                        <a href="<?php echo URLROOT; ?>">Website</a> / Admin / <?php echo $data['title']; ?>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="header-actions">
                        <a href="<?php echo URLROOT; ?>" class="btn-view-site" target="_blank">View Site</a>
                        <div class="notifications">
                            <span class="notification-icon">🔔</span>
                            <span class="notification-count">8</span>
                        </div>
                    </div>
                </div>
            </header>