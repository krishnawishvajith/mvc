<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/stylekalana.css">
    <title><?php echo SITENAME; ?></title>
</head>

<body>
    <!-- Advertisement Banner Carousel (Homepage Only) -->
    <?php
    $activeAds = $data['active_ads'] ?? [];
    if (!empty($activeAds)):
    ?>
        <section class="ad-banner-section" style="background: #000000; padding: 15px 0; position: relative; width: 100%;">
            <div class="ad-banner-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; position: relative;">
                <span class="ad-label" style="position: absolute; top: 5px; left: 25px; background: rgba(0,0,0,0.7); color: #aaa; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; z-index: 20;">AD</span>
                <div class="ad-carousel" style="position: relative; width: 100%; height: 100px; overflow: hidden; border-radius: 12px; background: linear-gradient(135deg, #6B4FF0, #9B59B6);">
                    <div class="ad-carousel-track" style="position: relative; width: 100%; height: 100%;">
                        <?php foreach ($activeAds as $index => $ad): ?>
                            <div class="ad-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: <?php echo $index === 0 ? '1' : '0'; ?>; transition: opacity 0.5s ease-in-out;">
                                <?php if ($ad->website): ?>
                                    <a href="<?php echo htmlspecialchars($ad->website); ?>" target="_blank" rel="noopener" style="display: flex; width: 100%; height: 100%; text-decoration: none; position: relative;">
                                    <?php else: ?>
                                        <div style="display: flex; width: 100%; height: 100%; position: relative;">
                                        <?php endif; ?>

                                        <?php if ($ad->file_path): ?>
                                            <!-- Background Image -->
                                            <img src="<?php echo URLROOT; ?>/images/advertisements/<?php echo htmlspecialchars($ad->file_path); ?>" alt="<?php echo htmlspecialchars($ad->company_name); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px; position: absolute; top: 0; left: 0;">
                                            <!-- Dark Overlay for Text Readability -->
                                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.1) 100%); border-radius: 12px;"></div>
                                        <?php endif; ?>

                                        <!-- Text Content Overlay -->
                                        <div style="position: relative; z-index: 5; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; padding: 15px 30px;">
                                            <span style="font-size: 22px; font-weight: 700; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.5); margin-bottom: 4px;"><?php echo htmlspecialchars($ad->company_name); ?></span>
                                            <?php if ($ad->message): ?>
                                                <span style="font-size: 13px; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5); max-width: 500px; line-height: 1.4;"><?php echo htmlspecialchars($ad->message); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($ad->website): ?>
                                    </a>
                                <?php else: ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                </div>

                <?php if (count($activeAds) > 1): ?>
                    <!-- Carousel Navigation -->
                    <button class="ad-carousel-btn ad-prev" onclick="prevAdSlide()" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.6); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 16px; z-index: 15; display: flex; align-items: center; justify-content: center;">❮</button>
                    <button class="ad-carousel-btn ad-next" onclick="nextAdSlide()" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.6); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 16px; z-index: 15; display: flex; align-items: center; justify-content: center;">❯</button>

                    <!-- Carousel Dots -->
                    <div class="ad-carousel-dots" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 15;">
                        <?php foreach ($activeAds as $index => $ad): ?>
                            <span class="ad-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToAdSlide(<?php echo $index; ?>)" style="width: 10px; height: 10px; border-radius: 50%; background: <?php echo $index === 0 ? 'white' : 'rgba(255,255,255,0.4)'; ?>; cursor: pointer;"></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Main Header Navigation -->
    <?php
    // Admin-editable navigation items (fallback to defaults if table missing)
    $navItems = [
        ['label' => 'Home', 'url' => URLROOT],
        ['label' => 'Stadiums', 'url' => URLROOT . '/stadiums'],
        ['label' => 'Coaches', 'url' => URLROOT . '/coach'],
        ['label' => 'Sports', 'url' => URLROOT . '/sports'],
        ['label' => 'Rental Services', 'url' => URLROOT . '/rental'],
    ];

    try {
        $db = new Database();
        $db->query("SELECT label, url FROM site_navigation WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        $rows = $db->resultSet();
        if (!empty($rows)) {
            $navItems = [];
            foreach ($rows as $r) {
                $u = trim((string)$r->url);
                // Build absolute URL using URLROOT for relative paths
                if (preg_match('#^https?://#i', $u)) {
                    $href = $u;
                } else {
                    if ($u === '') $u = '/';
                    if ($u[0] !== '/') $u = '/' . $u;
                    $href = URLROOT . $u;
                }
                $navItems[] = ['label' => (string)$r->label, 'url' => $href];
            }
        }
    } catch (Exception $e) {
        // fallback to defaults silently
    }
    ?>

    <header class="main-header">
        <nav class="navbar">
            <div class="nav-container">
                <!-- Logo -->
                <a href="<?php echo URLROOT; ?>" class="logo">BookMyGround</a>

                <!-- Navigation Menu -->
                <ul class="nav-menu">
                    <?php foreach ($navItems as $item): ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($item['url']); ?>" class="nav-link">
                                <?php echo htmlspecialchars($item['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <!-- Pricing dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" onclick="return false;" aria-haspopup="true" aria-expanded="false">Pricing ▾</a>
                        <ul class="dropdown-menu" aria-label="submenu">
                            <li><a href="<?php echo URLROOT; ?>/pricing" class="dropdown-link">Stadium Owners</a></li>
                            <li><a href="<?php echo URLROOT; ?>/rental_packages" class="dropdown-link">Sports Gear Rental Services</a></li>
                        </ul>
                    </li>

                    <!-- Pages dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" onclick="return false;" aria-haspopup="true" aria-expanded="false">Pages ▾</a>
                        <ul class="dropdown-menu" aria-label="submenu">
                            <li><a href="<?php echo URLROOT; ?>/posts" class="dropdown-link">Blog</a></li>
                            <li><a href="<?php echo URLROOT; ?>/faq" class="dropdown-link">FAQ</a></li>
                            <li><a href="<?php echo URLROOT; ?>/contact" class="dropdown-link">Contact</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Action Buttons -->
                <div class="nav-actions">
                    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                        <?php
                        $profileUrl = URLROOT . '/customer';
                        $profileInitial = strtoupper(substr(trim($_SESSION['user_first_name'] ?? $_SESSION['user_name'] ?? 'U'), 0, 1));
                        switch ($_SESSION['user_role'] ?? '') {
                            case 'stadium_owner':
                                $profileUrl = URLROOT . '/stadium_owner';
                                break;
                            case 'coach':
                                $profileUrl = URLROOT . '/coachdash';
                                break;
                            case 'rental_owner':
                                $profileUrl = URLROOT . '/rentalowner';
                                break;
                        }
                        ?>
                        <div class="profile-dropdown">
                            <button class="btn-profile" title="My Account">
                                <span class="profile-initial"><?php echo htmlspecialchars($profileInitial); ?></span>
                            </button>
                            <div class="profile-menu">
                                <a href="<?php echo $profileUrl; ?>" class="profile-menu-item">👤 Profile</a>
                                <a href="<?php echo URLROOT; ?>/login/logout" class="profile-menu-item logout-item">🚪 Logout</a>
                            </div>
                        </div>
                    <?php elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <div class="profile-dropdown">
                            <button class="btn-profile" title="Admin Dashboard">
                                <span class="profile-initial">A</span>
                            </button>
                            <div class="profile-menu">
                                <a href="<?php echo URLROOT; ?>/admin" class="profile-menu-item">Dashboard</a>
                                <a href="<?php echo URLROOT; ?>/login/logout" class="profile-menu-item logout-item">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/register" class="btn-register">Register</a>
                        <a href="<?php echo URLROOT; ?>/login" class="btn-login">Login</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    </header>

    <script>
        // Small header JS: hamburger toggle + dropdown behavior (works for desktop hover and mobile click)
        document.addEventListener('DOMContentLoaded', function() {
            // Hamburger toggle (mobile)
            var hamburger = document.getElementById('hamburger');
            var navContainer = document.querySelector('.nav-container');
            var navMenu = document.querySelector('.nav-menu');
            if (hamburger && navMenu) {
                hamburger.addEventListener('click', function() {
                    hamburger.classList.toggle('active');
                    navMenu.classList.toggle('open'); // toggles mobile menu
                });
            }

            // Dropdown behavior: open on click for mobile, keep hover for desktop via CSS
            var dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    // On small screens, prevent navigation and toggle submenu
                    var width = window.innerWidth || document.documentElement.clientWidth;
                    if (width <= 900) {
                        e.preventDefault();
                        var parent = toggle.parentElement;
                        parent.classList.toggle('open');
                        var expanded = parent.classList.contains('open');
                        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    }
                    // On large screens, it's a normal link to /pages (leave default)
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                var target = e.target;
                document.querySelectorAll('.dropdown.open').forEach(function(openDropdown) {
                    if (!openDropdown.contains(target)) {
                        openDropdown.classList.remove('open');
                        var t = openDropdown.querySelector('.dropdown-toggle');
                        if (t) t.setAttribute('aria-expanded', 'false');
                    }
                });
            });

            // Profile dropdown behavior
            var profileDropdowns = document.querySelectorAll('.profile-dropdown');
            profileDropdowns.forEach(function(dropdown) {
                var btn = dropdown.querySelector('.btn-profile');
                var menu = dropdown.querySelector('.profile-menu');

                if (btn && menu) {
                    // Toggle menu on button click (mobile)
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        menu.classList.toggle('visible');
                    });

                    // Show menu on hover (desktop)
                    dropdown.addEventListener('mouseenter', function() {
                        if (window.innerWidth > 900) {
                            menu.classList.add('visible');
                        }
                    });

                    // Hide menu on mouse leave (desktop)
                    dropdown.addEventListener('mouseleave', function() {
                        if (window.innerWidth > 900) {
                            menu.classList.remove('visible');
                        }
                    });
                }
            });

            // Close profile menu when clicking outside
            document.addEventListener('click', function(e) {
                profileDropdowns.forEach(function(dropdown) {
                    if (!dropdown.contains(e.target)) {
                        var menu = dropdown.querySelector('.profile-menu');
                        if (menu) menu.classList.remove('visible');
                    }
                });
            });
        });
    </script>