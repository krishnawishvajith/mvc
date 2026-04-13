<?php error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> - <?php echo SITENAME; ?> RentalOwner</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/rentdashbord.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>BookMyGround.lk</h2>
                <span class="admin-badge">Rental Owner Panel</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="<?php echo URLROOT; ?>/rentalowner" class="nav-link">
                            <span class="nav-icon">📊</span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/rentalowner/shopManagement" class="nav-link">
                            <span class="nav-icon">🏬</span>
                            <span class="nav-text">Manage Shops</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/rentalowner/messages" class="nav-link">
                            <span class="nav-icon">💬</span>
                            <span class="nav-text">Messages</span>
                            <span class="badge">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/rentalowner/advertisment" class="nav-link">
                            <span class="nav-icon">📢</span>
                            <span class="nav-text">Advertisements</span>
                            <span class="badge">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/rentalowner/blog" class="nav-link">
                            <span class="nav-icon">📝</span>
                            <span class="nav-text">Blog</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/rentalowner/profile" class="nav-link">
                            <span class="nav-icon">👤</span>
                            <span class="nav-text">Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-profile">
                    <div class="profile-info">
                        <h4><?php echo Auth::getUserFirstName() . ' ' . Auth::getUserLastName(); ?></h4>
                        <p><?php echo Auth::getUserEmail(); ?></p>
                    </div>
                </div>
                <a href="<?php echo URLROOT; ?>/rentalowner/logout" class="logout-btn">Logout</a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-container">
            <!-- Top Navigation -->
            <header class="top-header">
                <div class="header-left">
                    <button class="sidebar-toggle">☰</button>
                    <div class="breadcrumb">
                        <a href="<?php echo URLROOT; ?>">Website</a> / RentalOwner / <?php echo $data['title']; ?>
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