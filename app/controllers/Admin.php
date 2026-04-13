<?php
class Admin extends Controller
{
    private $adminModel;
    private $faqModel;
    private $messageModel;

    public function __construct()
    {
        $this->adminModel = $this->model('M_Admin');
        $this->faqModel = $this->model('M_Faq');
        $this->messageModel = $this->model('M_Messages');
    }

    public function index()
    {
        session_start();

        // Check if admin is logged in
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            // Redirect to main login page
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Get real bookings from database
        $recentBookings = $this->adminModel->getAllBookings(5, 0);
        $totalBookings = $this->adminModel->getTotalBookingsCount();
        $totalRevenue = $this->adminModel->getTotalRevenue();

        // Dashboard data
        $data = [
            'title' => 'Admin Dashboard',
            'total_users' => $this->adminModel->getTotalUsers(),
            'total_bookings' => $totalBookings,
            'monthly_revenue' => $totalRevenue,
            'pending_payouts' => 65000,
            'pending_refunds' => 5,
            'active_stadiums' => 45,
            'recent_bookings' => $recentBookings,
            'pending_payouts_list' => [
                ['owner' => 'University Of Colombo', 'stadium' => 'University Of Colombo Basket Ball Court', 'amount' => 4000, 'commission' => 1000],
                ['owner' => 'Dehiwala Indoor Lanka', 'stadium' => 'Dehiwala Indoor Lanka Court 1', 'amount' => 6000, 'commission' => 1500]
            ]
        ];

        $this->view('admin/v_dashboard', $data);
    }

    // Remove the separate login method - admins now use main login
    public function login()
    {
        // Redirect to main login page
        header('Location: ' . URLROOT . '/login');
        exit;
    }

    public function logout()
    {
        session_start();

        // Clear admin session
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_role']);

        session_destroy();

        header('Location: ' . URLROOT . '/login');
        exit;
    }

    public function users()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Get all users from database
        $users = $this->adminModel->getAllUsers();

        $data = [
            'title' => 'User Management',
            'users' => $users
        ];

        $this->view('admin/v_users', $data);
    }

    public function add_user()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'title' => 'Add New User',
            'error' => '',
            'success' => '',
            'form_data' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->processAddUser($data);
        }

        $this->view('admin/v_add_user', $data);
    }

    // In app/controllers/Admin.php, update the processAddUser method's validation section:

    private function processAddUser($data)
    {
        // Get and validate form data
        $formData = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ];

        $data['form_data'] = $formData;

        // Validation
        $errors = [];

        if (empty($formData['first_name'])) {
            $errors[] = 'First name is required';
        }

        if (empty($formData['last_name'])) {
            $errors[] = 'Last name is required';
        }

        if (empty($formData['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }

        if (empty($formData['phone'])) {
            $errors[] = 'Phone number is required';
        } elseif (!preg_match('/^0[0-9]{9}$/', $formData['phone'])) {
            $errors[] = 'Phone number must start with 0 and contain exactly 10 digits';
        }

        if (empty($formData['role'])) {
            $errors[] = 'Please select a role';
        } elseif (!in_array($formData['role'], ['customer', 'stadium_owner', 'coach', 'rental_owner'])) {
            $errors[] = 'Invalid role selected';
        }

        if (empty($formData['password'])) {
            $errors[] = 'Password is required';
        } elseif (strlen($formData['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }

        if ($formData['password'] !== $formData['confirm_password']) {
            $errors[] = 'Passwords do not match';
        }

        // Check if email already exists
        if (empty($errors) && $this->adminModel->emailExists($formData['email'])) {
            $errors[] = 'Email address already exists';
        }

        if (!empty($errors)) {
            $data['error'] = implode('<br>', $errors);
            return $data;
        }

        // Create user
        $userId = $this->adminModel->createUser($formData);

        if ($userId) {
            // Create role-specific profile if needed
            $this->createRoleProfile($userId, $formData);

            $data['success'] = 'User created successfully!';
            $data['form_data'] = []; // Clear form data on success
        } else {
            $data['error'] = 'Failed to create user. Please try again.';
        }

        return $data;
    }

    // In app/controllers/Admin.php, update the createRoleProfile method:

    private function createRoleProfile($userId, $formData)
    {
        // Create basic profiles for different roles
        // This can be expanded later with more specific fields
        switch ($formData['role']) {
            case 'customer':
                $this->adminModel->createCustomerProfile($userId, [
                    'district' => 'Not specified',
                    'sports' => 'Not specified',
                    'age_group' => 'under_18',
                    'skill_level' => 'beginner'
                ]);
                break;
            case 'stadium_owner':
                $this->adminModel->createStadiumOwnerProfile($userId, [
                    'owner_name' => $formData['first_name'] . ' ' . $formData['last_name'],
                    'business_name' => $formData['first_name'] . ' ' . $formData['last_name'], // Changed from "Not specified"
                    'district' => 'Not specified',
                    'venue_type' => 'stadium',
                    'business_registration' => 'Not specified'
                ]);
                break;
            case 'coach':
                $this->adminModel->createCoachProfile($userId, [
                    'specialization' => 'Not specified',
                    'experience' => '1_3',
                    'certification' => 'basic',
                    'coaching_type' => 'individual',
                    'district' => 'Not specified',
                    'availability' => 'part_time'
                ]);
                break;
            case 'rental_owner':
                $this->adminModel->createRentalOwnerProfile($userId, [
                    'owner_name' => $formData['first_name'] . ' ' . $formData['last_name'],
                    'business_name' => $formData['first_name'] . ' ' . $formData['last_name'], // Changed from "Not specified"
                    'district' => 'Not specified',
                    'business_type' => 'independent',
                    'equipment_categories' => 'Not specified',
                    'delivery_service' => 'no'
                ]);
                break;
        }
    }

    public function edit_user($id = null)
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . URLROOT . '/admin/users');
            exit;
        }

        $data = [
            'title' => 'Edit User',
            'error' => '',
            'success' => '',
            'user' => $this->adminModel->getUserById($id),
            'form_data' => []
        ];

        if (!$data['user']) {
            $_SESSION['admin_error'] = 'User not found.';
            header('Location: ' . URLROOT . '/admin/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->processEditUser($data, $id);
        }

        $this->view('admin/v_edit_user', $data);
    }

    private function processEditUser($data, $userId)
    {
        $formData = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'status' => $_POST['status'] ?? '',
            'reset_password' => isset($_POST['reset_password']),
            'new_password' => $_POST['new_password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ];

        $data['form_data'] = $formData;

        // Validation
        $errors = [];

        if (empty($formData['first_name'])) {
            $errors[] = 'First name is required';
        }

        if (empty($formData['last_name'])) {
            $errors[] = 'Last name is required';
        }

        if (empty($formData['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }

        if (empty($formData['phone'])) {
            $errors[] = 'Phone number is required';
        }

        if (empty($formData['status'])) {
            $errors[] = 'Status is required';
        }

        // Password validation only if reset_password is checked
        if ($formData['reset_password']) {
            if (empty($formData['new_password'])) {
                $errors[] = 'New password is required when resetting password';
            } elseif (strlen($formData['new_password']) < 6) {
                $errors[] = 'Password must be at least 6 characters long';
            }

            if ($formData['new_password'] !== $formData['confirm_password']) {
                $errors[] = 'Passwords do not match';
            }
        }

        // Check if email exists for other users
        if (empty($errors) && $this->adminModel->emailExistsForOtherUser($formData['email'], $userId)) {
            $errors[] = 'Email address already exists for another user';
        }

        if (!empty($errors)) {
            $data['error'] = implode('<br>', $errors);
            return $data;
        }

        // Update user
        $updateSuccess = $this->adminModel->updateUser($userId, $formData);

        // Update password if requested
        if ($updateSuccess && $formData['reset_password']) {
            $updateSuccess = $this->adminModel->updateUserPassword($userId, $formData['new_password']);
        }

        if ($updateSuccess) {
            $_SESSION['admin_message'] = 'User updated successfully!';
            header('Location: ' . URLROOT . '/admin/users');
            exit;
        } else {
            $data['error'] = 'Failed to update user. Please try again.';
        }

        return $data;
    }

    public function delete_user($id = null)
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . URLROOT . '/admin/users');
            exit;
        }

        if ($this->adminModel->deleteUser($id)) {
            $_SESSION['admin_message'] = 'User deleted successfully!';
        } else {
            $_SESSION['admin_error'] = 'Failed to delete user.';
        }

        header('Location: ' . URLROOT . '/admin/users');
        exit;
    }

    public function toggle_user_status($id = null)
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . URLROOT . '/admin/users');
            exit;
        }

        if ($this->adminModel->toggleUserStatus($id)) {
            $_SESSION['admin_message'] = 'User status updated successfully!';
        } else {
            $_SESSION['admin_error'] = 'Failed to update user status.';
        }

        header('Location: ' . URLROOT . '/admin/users');
        exit;
    }

    public function bookings()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Load admin model to fetch real bookings
        $adminModel = $this->model('M_Admin');

        // Get all bookings from database (real data)
        $allBookings = $adminModel->getAllBookings();
        $bookingStatuses = $adminModel->getBookingCountByStatus();
        $totalBookings = $adminModel->getTotalBookingsCount();
        $totalRevenue = $adminModel->getTotalRevenue();

        // Count by status
        $statusCounts = [
            'completed' => 0,
            'pending' => 0,
            'cancelled' => 0,
            'confirmed' => 0
        ];

        foreach ($bookingStatuses as $status) {
            if (isset($statusCounts[strtolower($status->status)])) {
                $statusCounts[strtolower($status->status)] = $status->count;
            }
        }

        $data = [
            'title' => 'Booking Management',
            'bookings' => $allBookings,
            'status_counts' => $statusCounts,
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue
        ];

        $this->view('admin/v_bookings', $data);
    }

    public function refunds()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $adminModel = $this->model('M_Admin');

        // Get all refund requests
        $allRefunds = $adminModel->getAllRefundRequests();
        $refundCounts = $adminModel->getRefundRequestCounts();
        $totalRefundAmount = $adminModel->getTotalRefundAmount();
        $pendingRefundAmount = $adminModel->getPendingRefundsAmount();

        $data = [
            'title' => 'Refund Requests Management',
            'refunds' => $allRefunds,
            'refund_counts' => $refundCounts,
            'total_refund_amount' => $totalRefundAmount,
            'pending_refund_amount' => $pendingRefundAmount
        ];

        $this->view('admin/v_refunds', $data);
    }

    public function getRefundDetails()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['success' => false, 'message' => 'Not authorized']);
            exit;
        }

        $refund_id = $_GET['id'] ?? 0;
        if (!$refund_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $adminModel = $this->model('M_Admin');
        $refund = $adminModel->getRefundRequestById($refund_id);

        if ($refund) {
            echo json_encode(['success' => true, 'data' => $refund]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Refund request not found']);
        }
        exit;
    }

    public function updateRefundStatus()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            echo json_encode(['success' => false, 'message' => 'Not authorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $refund_id = $_POST['refund_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $admin_id = $_SESSION['admin_id'] ?? 0;
        $payment_slip = null;

        if (!$refund_id || !$status || !in_array($status, ['pending', 'refunded', 'rejected', 'cancelled'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        // Handle file upload if status is 'refunded'
        if ($status === 'refunded' && isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['payment_slip'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'slip_' . $refund_id . '_' . time() . '.' . $ext;
            $uploadPath = PUBROOT . '/images/refunds/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $payment_slip = $filename;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload payment slip']);
                exit;
            }
        }

        $adminModel = $this->model('M_Admin');

        if ($adminModel->updateRefundRequestStatus($refund_id, $status, $admin_id, $notes, $payment_slip)) {
            echo json_encode([
                'success' => true,
                'message' => 'Refund status updated successfully'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update refund status']);
        }
        exit;
    }


    public function content()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $homeModel = $this->model('M_Home');

        // Save (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prefix = $_POST['hero_title_prefix'] ?? '';
            $highlight = $_POST['hero_title_highlight'] ?? '';
            $suffix = $_POST['hero_title_suffix'] ?? '';
            $description = $_POST['hero_description'] ?? '';

            $okHero = $homeModel->updateHeroContent([
                'hero_title_prefix' => $prefix,
                'hero_title_highlight' => $highlight,
                'hero_title_suffix' => $suffix,
                'hero_description' => $description
            ]);

            $okSettings = $homeModel->updateSiteSettings([
                'footer_company_name' => $_POST['footer_company_name'] ?? '',
                'footer_tagline' => $_POST['footer_tagline'] ?? '',
                'footer_address' => $_POST['footer_address'] ?? '',
                'footer_phone' => $_POST['footer_phone'] ?? '',
                'footer_email' => $_POST['footer_email'] ?? '',
                'social_facebook' => $_POST['social_facebook'] ?? '',
                'social_instagram' => $_POST['social_instagram'] ?? '',
                'social_linkedin' => $_POST['social_linkedin'] ?? '',
                'social_twitter' => $_POST['social_twitter'] ?? '',
                'social_youtube' => $_POST['social_youtube'] ?? ''
            ]);

            // Navigation menu items
            $labels = $_POST['nav_label'] ?? [];
            $urls = $_POST['nav_url'] ?? [];
            $actives = $_POST['nav_active'] ?? [];
            $navItems = [];

            if (is_array($labels) && is_array($urls)) {
                $count = max(count($labels), count($urls));
                for ($i = 0; $i < $count; $i++) {
                    $navItems[] = [
                        'label' => $labels[$i] ?? '',
                        'url' => $urls[$i] ?? '',
                        'is_active' => isset($actives[$i]) ? 1 : 0
                    ];
                }
            }

            $okNav = $homeModel->updateNavigationItems($navItems);

            if ($okHero && $okSettings && $okNav) {
                $_SESSION['admin_message'] = 'Home page hero content updated successfully.';
            } else {
                $_SESSION['admin_error'] = 'Failed to save home page content. Please try again.';
            }

            header('Location: ' . URLROOT . '/admin/content');
            exit;
        }

        // Load (GET)
        $hero = $homeModel->getHeroContent();
        $settings = $homeModel->getSiteSettings();
        $navItems = $homeModel->getNavigationItems();
        $data = [
            'title' => 'Content Management',
            'hero_title_prefix' => $hero['hero_title_prefix'],
            'hero_title_highlight' => $hero['hero_title_highlight'],
            'hero_title_suffix' => $hero['hero_title_suffix'],
            'hero_description' => $hero['hero_description'],
            'footer_company_name' => $settings['footer_company_name'],
            'footer_tagline' => $settings['footer_tagline'],
            'footer_address' => $settings['footer_address'],
            'footer_phone' => $settings['footer_phone'],
            'footer_email' => $settings['footer_email'],
            'social_facebook' => $settings['social_facebook'],
            'social_instagram' => $settings['social_instagram'],
            'social_linkedin' => $settings['social_linkedin'],
            'social_twitter' => $settings['social_twitter'],
            'social_youtube' => $settings['social_youtube'],
            'nav_items' => $navItems,
            'hero_bg_image' => 'home/basketball-player.jpg'
        ];

        $this->view('admin/v_content', $data);
    }

    public function payouts()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'title' => 'Payout Management',
            'pending_payouts' => [
                ['owner' => 'University Of Colombo', 'stadium' => 'University Of Colombo Football Court', 'total_bookings' => 12, 'gross_amount' => 60000, 'commission' => 12000, 'net_payout' => 48000],
                ['owner' => 'Dehiwala Indoor Lanka', 'stadium' => 'Dehiwala Indoor Lanka Footsal Court', 'total_bookings' => 8, 'gross_amount' => 45000, 'commission' => 9000, 'net_payout' => 36000],
                ['owner' => 'Tennis Academy Pannipitiya', 'stadium' => 'Tennis Academy Tennis Court 1', 'total_bookings' => 15, 'gross_amount' => 37500, 'commission' => 7500, 'net_payout' => 30000]
            ],
            'completed_payouts' => [
                ['owner' => 'Basketball Hub', 'amount' => 25000, 'date' => '2025-08-12', 'status' => 'Completed'],
                ['owner' => 'Multi-Purpose Arena', 'amount' => 42000, 'date' => '2025-08-05', 'status' => 'Completed']
            ]
        ];

        $this->view('admin/v_payouts', $data);
    }

    public function advertisements()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Load advertisement model
        $advertisementModel = $this->model('M_Advertisement');

        // Get all advertisements from database
        $allAds = $advertisementModel->getAllAdvertisements();

        // Separate pending and published/active ads
        $pending_ads = [];
        $published_ads = [];

        // Package prices for display
        $packagePrices = [
            'basic' => 5000,
            'standard' => 10000,
            'premium' => 20000
        ];

        foreach ($allAds as $ad) {
            if ($ad->is_active == 0) continue; // Skip soft-deleted ads

            if (in_array($ad->status, ['pending', 'approved'])) {
                $pending_ads[] = [
                    'id' => $ad->id,
                    'company' => $ad->company_name,
                    'contact' => $ad->contact_name,
                    'email' => $ad->email,
                    'phone' => $ad->phone,
                    'package' => ucfirst($ad->package),
                    'amount' => $packagePrices[$ad->package] ?? 5000,
                    'status' => $ad->status == 'pending' ? 'Pending Review' : 'Approved',
                    'submitted' => date('Y-m-d', strtotime($ad->submitted_at)),
                    'message' => $ad->message,
                    'website' => $ad->website,
                    'file_path' => $ad->file_path
                ];
            } elseif ($ad->status == 'active') {
                $published_ads[] = [
                    'id' => $ad->id,
                    'company' => $ad->company_name,
                    'type' => $ad->file_path ? 'Image' : 'Text',
                    'published' => $ad->approved_at ? date('Y-m-d', strtotime($ad->approved_at)) : '-',
                    'expires' => $ad->expires_at ? date('Y-m-d', strtotime($ad->expires_at)) : '-',
                    'status' => 'Active',
                    'file_path' => $ad->file_path
                ];
            }
        }

        $data = [
            'title' => 'Advertisement Management',
            'pending_ads' => $pending_ads,
            'published_ads' => $published_ads
        ];

        $this->view('admin/v_advertisements', $data);
    }

    // Approve advertisement request
    public function approveAdvertisement($id)
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $advertisementModel = $this->model('M_Advertisement');

            // Update status to 'active' and set dates
            if ($advertisementModel->approveAd($id)) {
                $_SESSION['success_msg'] = '✅ Advertisement approved and published!';
            } else {
                $_SESSION['error_msg'] = '❌ Failed to approve advertisement.';
            }
        }

        header('Location: ' . URLROOT . '/admin/advertisements');
        exit;
    }

    // Reject advertisement request
    public function rejectAdvertisement($id)
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $advertisementModel = $this->model('M_Advertisement');
            $advertisementModel->updateStatus($id, 'rejected');
            $_SESSION['success_msg'] = '❌ Advertisement request rejected.';
        }

        header('Location: ' . URLROOT . '/admin/advertisements');
        exit;
    }

    public function faq()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'title' => 'FAQ Management',
            'faqs' => $this->faqModel->getAllFaqsAdmin(),
            'categories' => $this->faqModel->getCategories()
        ];

        $this->view('admin/v_faq', $data);
    }

    public function createFaq()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $categorySlug = trim($_POST['category'] ?? 'general');
            $status = strtolower(trim($_POST['status'] ?? 'draft')) === 'published' ? 'published' : 'draft';
            $categoryId = $this->faqModel->getCategoryIdBySlug($categorySlug);

            if ($question === '' || $answer === '') {
                $_SESSION['admin_error'] = 'Question and answer are required for each FAQ.';
            } else {
                if ($this->faqModel->createFaq([
                    'category_id' => $categoryId,
                    'question' => $question,
                    'answer' => $answer,
                    'status' => $status
                ])) {
                    $_SESSION['admin_message'] = 'FAQ added successfully.';
                } else {
                    $_SESSION['admin_error'] = 'Failed to add the FAQ. Please try again.';
                }
            }
        }

        header('Location: ' . URLROOT . '/admin/faq');
        exit;
    }

    public function updateFaq()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            $categorySlug = trim($_POST['category'] ?? 'general');
            $status = strtolower(trim($_POST['status'] ?? 'draft')) === 'published' ? 'published' : 'draft';
            $categoryId = $this->faqModel->getCategoryIdBySlug($categorySlug);

            if ($id <= 0 || $question === '' || $answer === '') {
                $_SESSION['admin_error'] = 'Please provide valid FAQ details before updating.';
            } else {
                if ($this->faqModel->updateFaq($id, [
                    'category_id' => $categoryId,
                    'question' => $question,
                    'answer' => $answer,
                    'status' => $status
                ])) {
                    $_SESSION['admin_message'] = 'FAQ updated successfully.';
                } else {
                    $_SESSION['admin_error'] = 'Failed to update the FAQ. Please try again.';
                }
            }
        }

        header('Location: ' . URLROOT . '/admin/faq');
        exit;
    }

    public function toggleFaqStatus()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $action = trim($_POST['status'] ?? '');
            $status = $action === 'publish' ? 'published' : 'draft';

            if ($id > 0 && $this->faqModel->setFaqStatus($id, $status)) {
                $_SESSION['admin_message'] = $status === 'published' ? 'FAQ published successfully.' : 'FAQ set to draft.';
            } else {
                $_SESSION['admin_error'] = 'Failed to update FAQ status.';
            }
        }

        header('Location: ' . URLROOT . '/admin/faq');
        exit;
    }

    public function deleteFaq()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0 && $this->faqModel->deleteFaq($id)) {
                $_SESSION['admin_message'] = 'FAQ deleted successfully.';
            } else {
                $_SESSION['admin_error'] = 'Failed to delete FAQ.';
            }
        }

        header('Location: ' . URLROOT . '/admin/faq');
        exit;
    }





    /*-------------------------blog managment --------------------------------------------*/


    public function blog()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'title' => 'Blog Management',
            'posts' => [
                ['id' => 1, 'title' => 'Top 10 Cricket Grounds in Colombo', 'author' => 'Admin', 'category' => 'Cricket', 'status' => 'Published', 'published' => '2025-08-18', 'views' => 1250],
                ['id' => 2, 'title' => 'Football Training Tips for Beginners', 'author' => 'Krishna Wishvajith', 'category' => 'Football', 'status' => 'Draft', 'published' => '', 'views' => 0],
                ['id' => 3, 'title' => 'Benefits of Playing Tennis', 'author' => 'Dr. Dinesh', 'category' => 'Tennis', 'status' => 'Published', 'published' => '2025-08-15', 'views' => 980]
            ]
        ];

        $this->view('admin/v_blog', $data);
    }









    public function contact()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $contactModel = $this->model('M_Contact');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $contactModel->updateContactPageSettings([
                'page_title' => $_POST['page_title'] ?? '',
                'page_subtitle' => $_POST['page_subtitle'] ?? '',
                'main_phone' => $_POST['main_phone'] ?? '',
                'support_phone' => $_POST['support_phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'support_email' => $_POST['support_email'] ?? '',
                'address' => $_POST['address'] ?? '',
                'working_hours' => $_POST['working_hours'] ?? '',
                'emergency_contact' => $_POST['emergency_contact'] ?? ''
            ]);

            if ($ok) {
                $_SESSION['admin_message'] = 'Contact page settings updated successfully.';
            } else {
                $_SESSION['admin_error'] = 'Failed to save contact page settings.';
            }

            header('Location: ' . URLROOT . '/admin/contact');
            exit;
        }

        $settings = $contactModel->getContactPageSettings();
        $stats = $contactModel->getContactStats();
        $messages = $contactModel->getAllContactMessages(20);

        $data = [
            'title' => 'Contact Page Management',
            'settings' => $settings,
            'stats' => $stats,
            'messages' => $messages
        ];

        $this->view('admin/v_contact', $data);
    }

    public function listings()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'title' => 'Stadium Listings Management',
            'active_listings' => [
                ['id' => 1, 'name' => 'Colombo Cricket Ground', 'owner' => 'Rajesh Kumar', 'type' => 'Cricket', 'category' => 'Outdoor', 'price' => 5000, 'location' => 'Colombo 03', 'status' => 'Active', 'featured' => true, 'created' => '2025-01-15', 'views' => 245, 'bookings' => 12],
                ['id' => 2, 'name' => 'Football Arena Pro', 'owner' => 'David Fernando', 'type' => 'Football', 'category' => 'Outdoor', 'price' => 7500, 'location' => 'Colombo 05', 'status' => 'Active', 'featured' => true, 'created' => '2025-01-10', 'views' => 189, 'bookings' => 8],
                ['id' => 3, 'name' => 'Tennis Academy Courts', 'owner' => 'Michelle Perera', 'type' => 'Tennis', 'category' => 'Outdoor', 'price' => 2500, 'location' => 'Colombo 06', 'status' => 'Active', 'featured' => false, 'created' => '2025-01-08', 'views' => 156, 'bookings' => 5],
            ],
            'pending_listings' => [
                ['id' => 4, 'name' => 'New Basketball Court', 'owner' => 'Kevin Rodrigo', 'type' => 'Basketball', 'category' => 'Indoor', 'price' => 4000, 'location' => 'Colombo 04', 'status' => 'Pending', 'submitted' => '2025-01-20', 'reason' => 'New listing awaiting approval'],
                ['id' => 5, 'name' => 'Swimming Pool Complex', 'owner' => 'Sarah Johnson', 'type' => 'Swimming', 'category' => 'Outdoor', 'price' => 6000, 'location' => 'Mount Lavinia', 'status' => 'Pending', 'submitted' => '2025-01-19', 'reason' => 'Missing documentation'],
            ],
            'expired_listings' => [
                ['id' => 6, 'name' => 'Old Badminton Hall', 'owner' => 'Former Owner', 'type' => 'Badminton', 'category' => 'Indoor', 'price' => 3000, 'location' => 'Colombo 02', 'status' => 'Expired', 'expired' => '2025-01-01', 'last_booking' => '2024-12-15'],
            ],
            'statistics' => [
                'total_listings' => 25,
                'active_listings' => 18,
                'pending_approval' => 4,
                'expired_listings' => 3,
                'featured_listings' => 6,
                'this_month_revenue' => 125000
            ]
        ];

        $this->view('admin/v_listings', $data);
    }

    public function edit_listing($id = null)
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . URLROOT . '/admin/listings');
            exit;
        }

        // Sample data - replace with actual database query
        $data = [
            'title' => 'Edit Stadium Listing',
            'listing' => [
                'id' => $id,
                'name' => 'Colombo Cricket Ground',
                'owner' => 'Rajesh Kumar',
                'owner_email' => 'rajesh@email.com',
                'type' => 'Cricket',
                'category' => 'Outdoor',
                'price' => 5000,
                'location' => 'Colombo 03',
                'address' => '123 Cricket Street, Colombo 03',
                'description' => 'Professional cricket ground with modern facilities',
                'features' => ['Lighting', 'Parking', 'WiFi', 'Changing Rooms'],
                'status' => 'Active',
                'featured' => true,
                'images' => ['cricket-ground-1.jpg', 'cricket-ground-2.jpg'],
                'created' => '2025-01-15',
                'views' => 245,
                'bookings' => 12
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle form submission
            $data['success'] = 'Listing updated successfully!';
        }

        $this->view('admin/v_edit_listing', $data);
    }

    public function packages()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Handle POST request (save package changes)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $hasPackageFields = false;
            foreach ($_POST as $key => $value) {
                if (preg_match('/^package_\d+_name$/', $key)) {
                    $hasPackageFields = true;
                    break;
                }
            }

            if ($hasPackageFields || isset($_POST['package_ids'])) {
                $this->savePackageChanges();
                return;
            }

            // Handle AJAX request for purchase status update
            if (isset($_POST['action']) && $_POST['action'] === 'update_purchase_status') {
                $this->updatePurchaseStatus();
                return;
            }
        }

        // Try to fetch packages from database (with fallback if table doesn't exist)
        $dbPackages = [];
        try {
            $dbPackages = $this->adminModel->getAllPackages();

            // If no packages exist in database, create default packages
            if (empty($dbPackages)) {
                $this->initializeDefaultPackages();
                $dbPackages = $this->adminModel->getAllPackages();
            }
        } catch (Exception $e) {
            error_log('Admin packages: Database error (table may not exist): ' . $e->getMessage());
            // Try to create default packages if table exists but is empty
            try {
                $this->initializeDefaultPackages();
                $dbPackages = $this->adminModel->getAllPackages();
            } catch (Exception $initError) {
                error_log('Admin packages: Could not initialize default packages: ' . $initError->getMessage());
            }
        }

        // Try to fetch package purchases
        $packagePurchases = [];
        $purchaseCounts = ['pending' => 0, 'active' => 0, 'suspended' => 0, 'expired' => 0, 'total' => 0];
        try {
            $packagePurchases = $this->adminModel->getAllPackagePurchases();
            $purchaseCounts = $this->adminModel->getPackagePurchaseCounts();
        } catch (Exception $e) {
            error_log('Admin packages: Could not fetch purchases (table may not exist): ' . $e->getMessage());
        }

        $data = [
            'title' => 'Stadium Owner Package Management',
            'packages' => []
        ];

        if (is_array($dbPackages) && count($dbPackages) > 0) {
            foreach ($dbPackages as $pkg) {
                $data['packages'][] = [
                    'id' => $pkg->id,
                    'name' => $pkg->name,
                    'monthly_fee' => 0,
                    'setup_fee' => $pkg->setup_fee,
                    'commission_rate' => $pkg->commission_rate,
                    'stadium_limit' => $pkg->stadium_limit === 'unlimited' ? 'unlimited' : $pkg->stadium_limit,
                    'photos_limit' => $pkg->photos_per_property,
                    'videos_limit' => $pkg->videos_per_property,
                    'featured_limit' => $pkg->featured_listings,
                    'support_level' => $this->getSupportLevel($pkg->support),
                    'features' => [
                        'booking_management' => true,
                        'payment_processing' => true,
                        'advanced_analytics' => (bool)$pkg->advanced_analytics,
                        'marketing_tools' => (bool)$pkg->marketing_tools,
                        'api_access' => (bool)$pkg->api_access,
                        'priority_support' => (bool)$pkg->priority_support,
                        'dedicated_manager' => (bool)($pkg->dedicated_manager ?? false)
                    ],
                    'description' => $pkg->description,
                    'status' => 'active',
                    'users_count' => 0,
                    'icon' => $pkg->icon ?? '⚡',
                    'color' => $pkg->color ?? 'standard',
                    'is_popular' => (bool)$pkg->is_popular
                ];
            }
        } else {
            $data['packages'] = [
                [
                    'id' => 1,
                    'name' => 'Basic',
                    'monthly_fee' => 0,
                    'setup_fee' => 1380,
                    'commission_rate' => 8,
                    'stadium_limit' => 3,
                    'photos_limit' => 3,
                    'videos_limit' => 3,
                    'featured_limit' => 0,
                    'support_level' => 'email',
                    'features' => [
                        'booking_management' => true,
                        'payment_processing' => true,
                        'advanced_analytics' => false,
                        'marketing_tools' => false,
                        'api_access' => false,
                        'priority_support' => false,
                        'dedicated_manager' => false
                    ],
                    'description' => 'Perfect for getting started with stadium rentals',
                    'status' => 'active',
                    'users_count' => 25,
                    'icon' => '🌟',
                    'color' => 'basic',
                    'is_popular' => false
                ],
                [
                    'id' => 2,
                    'name' => 'Standard',
                    'monthly_fee' => 0,
                    'setup_fee' => 1380,
                    'commission_rate' => 12,
                    'stadium_limit' => 6,
                    'photos_limit' => 5,
                    'videos_limit' => 5,
                    'featured_limit' => 3,
                    'support_level' => 'phone',
                    'features' => [
                        'booking_management' => true,
                        'payment_processing' => true,
                        'advanced_analytics' => true,
                        'marketing_tools' => true,
                        'api_access' => false,
                        'priority_support' => true,
                        'dedicated_manager' => false
                    ],
                    'description' => 'Ideal for growing stadium businesses',
                    'status' => 'active',
                    'users_count' => 15,
                    'icon' => '⚡',
                    'color' => 'standard',
                    'is_popular' => true
                ],
                [
                    'id' => 3,
                    'name' => 'Gold',
                    'monthly_fee' => 0,
                    'setup_fee' => 1380,
                    'commission_rate' => 20,
                    'stadium_limit' => 'unlimited',
                    'photos_limit' => 10,
                    'videos_limit' => 5,
                    'featured_limit' => 5,
                    'support_level' => 'priority',
                    'features' => [
                        'booking_management' => true,
                        'payment_processing' => true,
                        'advanced_analytics' => true,
                        'marketing_tools' => true,
                        'api_access' => true,
                        'priority_support' => true,
                        'dedicated_manager' => true
                    ],
                    'description' => 'For established stadium owners who want maximum exposure',
                    'status' => 'active',
                    'users_count' => 5,
                    'icon' => '👑',
                    'color' => 'gold',
                    'is_popular' => false
                ]
            ];
        }

        // Add package purchases to data
        $data['package_purchases'] = $packagePurchases;
        $data['purchase_counts'] = $purchaseCounts;

        $this->view('admin/v_packages', $data);
    }

    private function updatePurchaseStatus()
    {
        header('Content-Type: application/json');

        try {
            $purchaseId = (int)$_POST['purchase_id'];
            $newStatus = trim($_POST['status']);
            $adminNotes = trim($_POST['admin_notes'] ?? '');

            // Validate status
            $validStatuses = ['pending', 'active', 'suspended', 'expired', 'failed'];
            if (!in_array($newStatus, $validStatuses)) {
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                exit;
            }

            // Update status
            $result = $this->adminModel->updatePackagePurchaseStatus($purchaseId, $newStatus, $adminNotes);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Package purchase status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }

        exit;
    }

    // ========== RENTAL SERVICE OWNER PACKAGES ==========

    public function rental_packages()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Handle POST request (save package changes)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['package_1_name'])) {
            $this->saveRentalPackageChanges();
            return;
        }

        // Handle AJAX request for purchase status update
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_rental_purchase_status') {
            $this->updateRentalPurchaseStatus();
            return;
        }

        // Try to fetch rental packages from database (with fallback)
        $dbPackages = [];
        try {
            $dbPackages = $this->adminModel->getAllRentalPackages();
        } catch (Exception $e) {
            error_log('Admin rental packages: Database error (table may not exist): ' . $e->getMessage());
        }

        // Try to fetch rental package purchases
        $packagePurchases = [];
        $purchaseCounts = ['pending' => 0, 'active' => 0, 'suspended' => 0, 'expired' => 0, 'failed' => 0, 'total' => 0];
        try {
            $packagePurchases = $this->adminModel->getAllRentalPackagePurchases();
            $purchaseCounts = $this->adminModel->getRentalPackagePurchaseCounts();
        } catch (Exception $e) {
            error_log('Admin rental packages: Could not fetch purchases: ' . $e->getMessage());
        }

        $data = [
            'title' => 'Rental Service Owner Package Management',
            'packages' => []
        ];

        // If packages found in database, use them
        if (is_array($dbPackages) && count($dbPackages) > 0) {
            foreach ($dbPackages as $pkg) {
                $key = strtolower($pkg->name);
                $data['packages'][$key] = [
                    'id' => $pkg->id,
                    'name' => $pkg->name,
                    'description' => $pkg->description,
                    'price' => $pkg->price,
                    'duration_text' => $pkg->duration_text,
                    'shop_listings' => $pkg->shop_listings,
                    'images_per_listing' => $pkg->images_per_listing,
                    'phone_contact' => (bool)$pkg->phone_contact,
                    'email_contact' => (bool)$pkg->email_contact,
                    'amenities_display' => (bool)$pkg->amenities_display,
                    'priority_placement' => (bool)$pkg->priority_placement,
                    'email_phone_support' => (bool)$pkg->email_phone_support,
                    'icon' => $pkg->icon ?? '⚡',
                    'color' => $pkg->color ?? 'standard',
                    'is_popular' => (bool)$pkg->is_popular
                ];
            }
        } else {
            // Fallback hardcoded data
            $data['packages'] = [
                'standard' => [
                    'id' => 1,
                    'name' => 'Standard',
                    'description' => 'Perfect for sports equipment rental businesses',
                    'price' => 12300,
                    'duration_text' => 'Listings Valid For 3 Months',
                    'shop_listings' => 5,
                    'images_per_listing' => 5,
                    'phone_contact' => true,
                    'email_contact' => true,
                    'amenities_display' => true,
                    'priority_placement' => true,
                    'email_phone_support' => true,
                    'icon' => '⚡',
                    'color' => 'standard',
                    'is_popular' => true
                ]
            ];
        }

        // Add purchases data
        $data['package_purchases'] = $packagePurchases;
        $data['purchase_counts'] = $purchaseCounts;

        $this->view('admin/v_rental_packages', $data);
    }

    private function saveRentalPackageChanges()
    {
        // Currently only handle package ID 1 (standard)
        $packagesData = [];
        foreach (['1'] as $id) {
            $packagesData[$id] = [
                'id' => $id,
                'name' => $_POST["package_{$id}_name"] ?? 'Standard',
                'description' => $_POST["package_{$id}_description"] ?? '',
                'price' => (float)($_POST["package_{$id}_price"] ?? 0),
                'duration_text' => $_POST["package_{$id}_duration"] ?? 'One-time',
                'shop_listings' => (int)($_POST["package_{$id}_shops"] ?? 0),
                'images_per_listing' => (int)($_POST["package_{$id}_images"] ?? 0),
                'phone_contact' => isset($_POST["package_{$id}_phone"]) ? 1 : 0,
                'email_contact' => isset($_POST["package_{$id}_email"]) ? 1 : 0,
                'amenities_display' => isset($_POST["package_{$id}_amenities"]) ? 1 : 0,
                'priority_placement' => isset($_POST["package_{$id}_priority"]) ? 1 : 0,
                'email_phone_support' => isset($_POST["package_{$id}_support"]) ? 1 : 0,
                'icon' => $_POST["package_{$id}_icon"] ?? '⚡',
                'color' => 'standard',
                'is_popular' => isset($_POST["package_{$id}_popular"]) ? 1 : 0
            ];

            $this->adminModel->updateRentalPackage($packagesData[$id]);
        }

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Rental packages updated successfully']);
        exit;
    }

    private function updateRentalPurchaseStatus()
    {
        header('Content-Type: application/json');

        try {
            $purchaseId = (int)$_POST['purchase_id'];
            $newStatus = trim($_POST['status']);
            $adminNotes = trim($_POST['admin_notes'] ?? '');

            // Validate status
            $validStatuses = ['pending', 'active', 'suspended', 'expired', 'failed'];
            if (!in_array($newStatus, $validStatuses)) {
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                exit;
            }

            // Update status
            $result = $this->adminModel->updateRentalPackagePurchaseStatus($purchaseId, $newStatus, $adminNotes);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Rental package purchase status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }

        exit;
    }

    private function initializeDefaultPackages()
    {
        $defaultPackages = [
            [
                'id' => 1,
                'name' => 'Basic',
                'description' => 'Perfect for getting started with stadium rentals',
                'setup_fee' => 1380.00,
                'commission_rate' => 8,
                'stadium_limit' => '3',
                'photos_per_property' => 3,
                'videos_per_property' => 3,
                'featured_listings' => 0,
                'support' => 'Email Support',
                'marketing_tools' => 0,
                'advanced_analytics' => 0,
                'priority_support' => 0,
                'dedicated_manager' => 0,
                'api_access' => 0,
                'icon' => '🌟',
                'color' => 'basic',
                'is_popular' => 0
            ],
            [
                'id' => 2,
                'name' => 'Standard',
                'description' => 'Ideal for growing stadium businesses',
                'setup_fee' => 1380.00,
                'commission_rate' => 12,
                'stadium_limit' => '6',
                'photos_per_property' => 5,
                'videos_per_property' => 5,
                'featured_listings' => 3,
                'support' => 'Email & Phone Support',
                'marketing_tools' => 1,
                'advanced_analytics' => 1,
                'priority_support' => 1,
                'dedicated_manager' => 0,
                'api_access' => 0,
                'icon' => '⚡',
                'color' => 'standard',
                'is_popular' => 1
            ],
            [
                'id' => 3,
                'name' => 'Gold',
                'description' => 'For established stadium owners who want maximum exposure',
                'setup_fee' => 1380.00,
                'commission_rate' => 20,
                'stadium_limit' => 'unlimited',
                'photos_per_property' => 10,
                'videos_per_property' => 5,
                'featured_listings' => 5,
                'support' => 'Priority Support 24/7',
                'marketing_tools' => 1,
                'advanced_analytics' => 1,
                'priority_support' => 1,
                'dedicated_manager' => 1,
                'api_access' => 1,
                'icon' => '👑',
                'color' => 'gold',
                'is_popular' => 0
            ]
        ];

        foreach ($defaultPackages as $package) {
            try {
                $this->adminModel->createPackage($package);
            } catch (Exception $e) {
                // Package might already exist, try to update instead
                try {
                    $this->adminModel->updatePackage($package);
                } catch (Exception $updateError) {
                    error_log('Failed to create/update default package ' . $package['id'] . ': ' . $updateError->getMessage());
                }
            }
        }
    }

    private function getSupportLevel($support)
    {
        if (strpos(strtolower($support), 'priority') !== false) return 'priority';
        if (strpos(strtolower($support), 'phone') !== false) return 'phone';
        return 'email';
    }

    private function savePackageChanges()
    {
        header('Content-Type: application/json');

        // Build package data arrays from submitted IDs
        $packageIds = [];
        if (!empty($_POST['package_ids'])) {
            $packageIds = array_filter(array_map('trim', explode(',', $_POST['package_ids'])));
        }

        if (empty($packageIds)) {
            foreach ($_POST as $key => $value) {
                if (preg_match('/^package_(\d+)_name$/', $key, $matches)) {
                    $packageIds[] = $matches[1];
                }
            }
        }

        if (empty($packageIds)) {
            echo json_encode(['success' => false, 'message' => 'No package data submitted.']);
            exit;
        }

        $saveResults = ['saved' => [], 'failed' => []];

        foreach ($packageIds as $id) {
            $key = strtolower($_POST["package_{$id}_name"] ?? '');
            $stadium_limit = $_POST["package_{$id}_stadiums"] ?? '0';
            if ($stadium_limit === '999' || strtolower($stadium_limit) === 'unlimited') {
                $stadium_limit = 'unlimited';
            }

            $packagesData[$id] = [
                'id' => (int)$id,
                'name' => $_POST["package_{$id}_name"] ?? '',
                'description' => $_POST["package_{$id}_description"] ?? '',
                'setup_fee' => (float)($_POST["package_{$id}_setup_fee"] ?? 0),
                'commission_rate' => (int)($_POST["package_{$id}_commission"] ?? 0),
                'stadium_limit' => $stadium_limit,
                'photos_per_property' => (int)($_POST["package_{$id}_photos"] ?? 0),
                'videos_per_property' => (int)($_POST["package_{$id}_videos"] ?? 0),
                'featured_listings' => (int)($_POST["package_{$id}_featured"] ?? 0),
                'support' => $_POST["package_{$id}_support"] ?? 'Email Support',
                'marketing_tools' => isset($_POST["package_{$id}_marketing"]) ? 1 : 0,
                'advanced_analytics' => isset($_POST["package_{$id}_analytics"]) ? 1 : 0,
                'priority_support' => isset($_POST["package_{$id}_priority"]) ? 1 : 0,
                'dedicated_manager' => isset($_POST["package_{$id}_manager"]) ? 1 : 0,
                'api_access' => isset($_POST["package_{$id}_api"]) ? 1 : 0,
                'icon' => $_POST["package_{$id}_icon"] ?? '⚡',
                'color' => $key,
                'is_popular' => isset($_POST["package_{$id}_popular"]) ? 1 : 0,
                'display_order' => (int)$id
            ];

            try {
                $existing = $this->adminModel->getPackageById($id);
                if ($existing) {
                    $this->adminModel->updatePackage($packagesData[$id]);
                } else {
                    $this->adminModel->createPackage($packagesData[$id]);
                }
                $saveResults['saved'][] = $id;
            } catch (Exception $e) {
                try {
                    $this->adminModel->createPackage($packagesData[$id]);
                    $saveResults['saved'][] = $id;
                } catch (Exception $createError) {
                    $saveResults['failed'][] = [
                        'id' => $id,
                        'message' => $createError->getMessage()
                    ];
                    error_log('Failed to save package ' . $id . ': ' . $createError->getMessage());
                }
            }
        }

        if (!empty($saveResults['failed'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Some packages could not be saved.',
                'details' => $saveResults['failed']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Packages saved successfully.',
                'saved' => $saveResults['saved']
            ]);
        }

        exit;
    }

    public function reviews()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Sample review data - in production this would come from database
        $data = [
            'title' => 'Stadium Reviews Management',
            'reviews' => [
                [
                    'id' => 1,
                    'stadium_name' => 'Colombo Cricket Ground',
                    'stadium_id' => 1,
                    'customer_name' => 'Krishna Wishvajith',
                    'customer_email' => 'krishna@email.com',
                    'rating' => 5,
                    'review_text' => 'Excellent facilities and well-maintained ground. The lighting system is perfect for evening matches. Highly recommend for cricket tournaments.',
                    'date' => '2025-01-20',
                    'status' => 'Published',
                    'verified_booking' => true,
                    'helpful_votes' => 15,
                    'reported' => false
                ],
                [
                    'id' => 2,
                    'stadium_name' => 'Football Arena Pro',
                    'stadium_id' => 3,
                    'customer_name' => 'Kulakshi Thathsarani',
                    'customer_email' => 'kulakshi@email.com',
                    'rating' => 4,
                    'review_text' => 'Great stadium with good parking facilities. Only minor issue was the changing room could be cleaner. Overall good experience.',
                    'date' => '2025-01-18',
                    'status' => 'Published',
                    'verified_booking' => true,
                    'helpful_votes' => 8,
                    'reported' => false
                ],
                [
                    'id' => 3,
                    'stadium_name' => 'Tennis Academy Courts',
                    'stadium_id' => 4,
                    'customer_name' => 'Dinesh Sulakshana',
                    'customer_email' => 'dinesh@email.com',
                    'rating' => 5,
                    'review_text' => 'Professional quality courts and excellent customer service. The coaching staff is very helpful and knowledgeable.',
                    'date' => '2025-01-17',
                    'status' => 'Published',
                    'verified_booking' => true,
                    'helpful_votes' => 22,
                    'reported' => false
                ],
                [
                    'id' => 4,
                    'stadium_name' => 'Basketball Hub Angoda',
                    'stadium_id' => 5,
                    'customer_name' => 'Kalana Ekanayake',
                    'customer_email' => 'kalana@email.com',
                    'rating' => 4,
                    'review_text' => 'Good value for money. The court quality is excellent and perfect for competitive games.',
                    'date' => '2025-01-15',
                    'status' => 'Published',
                    'verified_booking' => true,
                    'helpful_votes' => 6,
                    'reported' => false
                ],
                [
                    'id' => 5,
                    'stadium_name' => 'Indoor Sports Complex',
                    'stadium_id' => 2,
                    'customer_name' => 'Sarah Johnson',
                    'customer_email' => 'sarah@email.com',
                    'rating' => 2,
                    'review_text' => 'Very disappointing experience. The facility was not clean and staff was unprofessional. Would not recommend.',
                    'date' => '2025-01-14',
                    'status' => 'Flagged',
                    'verified_booking' => true,
                    'helpful_votes' => 3,
                    'reported' => true
                ],
                [
                    'id' => 6,
                    'stadium_name' => 'Swimming Pool Complex',
                    'stadium_id' => 6,
                    'customer_name' => 'Mike Wilson',
                    'customer_email' => 'mike@email.com',
                    'rating' => 5,
                    'review_text' => 'Amazing swimming facility with clean water and excellent maintenance. The Olympic-size pool is perfect for serious training.',
                    'date' => '2025-01-12',
                    'status' => 'Published',
                    'verified_booking' => true,
                    'helpful_votes' => 18,
                    'reported' => false
                ],
                [
                    'id' => 7,
                    'stadium_name' => 'Colombo Badminton Center',
                    'stadium_id' => 7,
                    'customer_name' => 'Priya Raj',
                    'customer_email' => 'priya@email.com',
                    'rating' => 3,
                    'review_text' => 'Average facility. Courts are okay but could use better lighting. Service is decent.',
                    'date' => '2025-01-10',
                    'status' => 'Pending',
                    'verified_booking' => false,
                    'helpful_votes' => 2,
                    'reported' => false
                ],
                [
                    'id' => 8,
                    'stadium_name' => 'Premier Squash Courts',
                    'stadium_id' => 8,
                    'customer_name' => 'John Silva',
                    'customer_email' => 'john@email.com',
                    'rating' => 1,
                    'review_text' => 'Terrible experience! Courts were dirty and equipment was broken. Staff was rude and unprofessional. Waste of money!',
                    'date' => '2025-01-08',
                    'status' => 'Flagged',
                    'verified_booking' => true,
                    'helpful_votes' => 0,
                    'reported' => true
                ]
            ],
            'stats' => [
                'total_reviews' => 156,
                'published_reviews' => 142,
                'pending_reviews' => 8,
                'flagged_reviews' => 6,
                'average_rating' => 4.2,
                'this_month_reviews' => 23
            ]
        ];

        $this->view('admin/v_reviews', $data);
    }

    // ==================== RENTAL SHOP APPROVAL METHODS ====================

    public function rentalShops()
    {
        try {
            if (!Auth::isAdminLoggedIn()) {
                header('Location: ' . URLROOT . '/admin/login');
                exit;
            }

            $filter = $_GET['filter'] ?? 'all';

            // Get shops based on filter
            $shops = [];
            if ($filter === 'all') {
                $shops = $this->adminModel->getAllRentalShops();
            } else {
                $shops = $this->adminModel->getAllRentalShops($filter);
            }

            // Get statistics
            $stats = $this->adminModel->getRentalShopCounts();

            $data = [
                'title' => 'Rental Shop Management',
                'shops' => $shops,
                'stats' => $stats,
                'current_filter' => $filter
            ];

            $this->view('admin/v_rental_shops', $data);
        } catch (Exception $e) {
            error_log('Admin Rental Shops Error: ' . $e->getMessage());
            die('Error loading rental shops: ' . $e->getMessage());
        }
    }

    public function approveShop()
    {
        try {
            if (!Auth::isAdminLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $shopId = $_POST['shop_id'] ?? null;
                $adminId = Auth::getAdminId();

                if (!$shopId) {
                    echo json_encode(['success' => false, 'message' => 'Shop ID required']);
                    exit;
                }

                // Get shop details for email
                $shop = $this->adminModel->getRentalShopById($shopId);

                if (!$shop) {
                    echo json_encode(['success' => false, 'message' => 'Shop not found']);
                    exit;
                }

                // Approve the shop
                if ($this->adminModel->approveRentalShop($shopId, $adminId)) {
                    // Send approval email
                    $this->sendShopApprovalEmail($shop);

                    echo json_encode(['success' => true, 'message' => 'Shop approved successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to approve shop']);
                }
            }
        } catch (Exception $e) {
            error_log('Admin Approve Shop Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function rejectShop()
    {
        try {
            if (!Auth::isAdminLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $shopId = $_POST['shop_id'] ?? null;
                $reason = $_POST['reason'] ?? 'No reason provided';
                $adminId = Auth::getAdminId();

                if (!$shopId) {
                    echo json_encode(['success' => false, 'message' => 'Shop ID required']);
                    exit;
                }

                // Get shop details for email
                $shop = $this->adminModel->getRentalShopById($shopId);

                if (!$shop) {
                    echo json_encode(['success' => false, 'message' => 'Shop not found']);
                    exit;
                }

                // Reject the shop
                if ($this->adminModel->rejectRentalShop($shopId, $adminId, $reason)) {
                    // Send rejection email
                    $this->sendShopRejectionEmail($shop, $reason);

                    echo json_encode(['success' => true, 'message' => 'Shop rejected']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to reject shop']);
                }
            }
        } catch (Exception $e) {
            error_log('Admin Reject Shop Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function rentalShopDetails()
    {
        header('Content-Type: application/json');
        try {
            if (!Auth::isAdminLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $shopId = $_POST['shop_id'] ?? null;
            if (!$shopId) {
                echo json_encode(['success' => false, 'message' => 'Shop ID required']);
                exit;
            }

            $shop = $this->adminModel->getRentalShopById($shopId);
            if (!$shop) {
                echo json_encode(['success' => false, 'message' => 'Shop not found']);
                exit;
            }

            echo json_encode(['success' => true, 'shop' => $shop]);
        } catch (Exception $e) {
            error_log('Admin Rental Shop Details Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred while loading shop details']);
        }
        exit;
    }

    public function contactMessageDetails()
    {
        header('Content-Type: application/json');
        try {
            if (!Auth::isAdminLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $id = $_POST['message_id'] ?? null;
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Message ID required']);
                exit;
            }

            $contactModel = $this->model('M_Contact');
            $msg = $contactModel->getContactMessageById($id);
            if (!$msg) {
                echo json_encode(['success' => false, 'message' => 'Message not found']);
                exit;
            }

            echo json_encode(['success' => true, 'message' => $msg]);
        } catch (Exception $e) {
            error_log('Admin Contact Message Details Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred while loading message details']);
        }
        exit;
    }

    private function sendShopApprovalEmail($shop)
    {
        try {
            $ownerEmail = $shop->owner_email ?? null;
            $ownerName = ($shop->first_name ?? '') . ' ' . ($shop->last_name ?? '');

            if (!$ownerEmail) {
                return false;
            }

            $subject = '✅ Your Shop Has Been Approved - BookMyGround.lk';

            $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #03B200 0%, #028a00 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .shop-details { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #03B200; }
                .cta-button { display: inline-block; background: #03B200; color: white !important; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #777; }
                .badge { background: #03B200; color: white; padding: 8px 16px; border-radius: 20px; display: inline-block; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Congratulations!</h1>
                    <h2>Your Shop is Now Live!</h2>
                </div>
                <div class='content'>
                    <p>Dear {$ownerName},</p>
                    
                    <p>Great news! Your sports equipment rental shop has been <strong>approved</strong> and is now live on BookMyGround.lk!</p>
                    
                    <div class='badge'>✅ APPROVED</div>
                    
                    <div class='shop-details'>
                        <h3>📋 Shop Details:</h3>
                        <p><strong>Shop Name:</strong> {$shop->store_name}</p>
                        <p><strong>Status:</strong> Live and visible to customers</p>
                    </div>
                    
                    <h3>🚀 What's Next?</h3>
                    <ul>
                        <li>✅ Your shop is now visible to all customers on our platform</li>
                        <li>✅ Customers can now browse and book your equipment</li>
                        <li>✅ You can manage your shop from your dashboard</li>
                        <li>✅ You'll receive notifications for new bookings</li>
                    </ul>
                    
                    <center>
                        <a href='" . URLROOT . "/rentalowner/shopManagement' class='cta-button'>Go to Dashboard</a>
                    </center>
                    
                    <p><strong>Need Help?</strong><br>
                    Contact our support team if you have any questions.</p>
                    
                    <div class='footer'>
                        <p>Thank you for partnering with BookMyGround.lk!</p>
                        <p style='font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";

            $mail = new Mail();
            return $mail->send($ownerEmail, $subject, $message, MAIL_FROM, 'BookMyGround.lk');
        } catch (Exception $e) {
            error_log('Failed to send shop approval email: ' . $e->getMessage());
            return false;
        }
    }

    private function sendShopRejectionEmail($shop, $reason)
    {
        try {
            $ownerEmail = $shop->owner_email ?? null;
            $ownerName = ($shop->first_name ?? '') . ' ' . ($shop->last_name ?? '');

            if (!$ownerEmail) {
                return false;
            }

            $subject = 'Shop Submission Update - BookMyGround.lk';

            $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .reason-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #ff6b6b; }
                .cta-button { display: inline-block; background: #03B200; color: white !important; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #777; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Shop Submission Update</h1>
                </div>
                <div class='content'>
                    <p>Dear {$ownerName},</p>
                    
                    <p>Thank you for submitting your shop <strong>{$shop->store_name}</strong> to BookMyGround.lk.</p>
                    
                    <p>After careful review, we regret to inform you that your shop submission could not be approved at this time.</p>
                    
                    <div class='reason-box'>
                        <h3>📝 Reason:</h3>
                        <p>{$reason}</p>
                    </div>
                    
                    <h3>🔄 What Can You Do?</h3>
                    <ul>
                        <li>Review the reason provided above</li>
                        <li>Make necessary changes to your shop details</li>
                        <li>Resubmit your shop from your dashboard</li>
                        <li>Contact support if you need assistance</li>
                    </ul>
                    
                    <center>
                        <a href='" . URLROOT . "/rentalowner/shopManagement' class='cta-button'>Go to Dashboard</a>
                    </center>
                    
                    <div class='footer'>
                        <p>If you have questions, please contact our support team.</p>
                        <p style='font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";

            $mail = new Mail();
            return $mail->send($ownerEmail, $subject, $message, MAIL_FROM, 'BookMyGround.lk');
        } catch (Exception $e) {
            error_log('Failed to send shop rejection email: ' . $e->getMessage());
            return false;
        }
    }

    // ========== STADIUM APPROVAL METHODS ==========

    public function stadiumListings()
    {
        try {
            if (!Auth::isAdminLoggedIn()) {
                header('Location: ' . URLROOT . '/login');
                exit;
            }

            // Get filter parameter
            $filter = $_GET['filter'] ?? 'all';

            // Get stadiums based on filter
            $stadiums = $this->adminModel->getStadiumListings($filter);

            // Get statistics
            $stats = $this->adminModel->getStadiumCounts();

            $data = [
                'title' => 'Stadium Listings Management',
                'stadiums' => $stadiums,
                'stats' => $stats,
                'current_filter' => $filter
            ];

            $this->view('admin/v_stadium_listings', $data);
        } catch (Exception $e) {
            error_log('Admin Stadium Listings Error: ' . $e->getMessage());
            die('Error loading stadium listings: ' . $e->getMessage());
        }
    }

    public function stadiumDetails()
    {
        try {
            if (!Auth::isAdminLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            $stadiumId = $_GET['stadium_id'] ?? null;
            if (!$stadiumId) {
                echo json_encode(['success' => false, 'message' => 'Stadium ID required']);
                exit;
            }

            $stadium = $this->adminModel->getStadiumById($stadiumId);
            if (!$stadium) {
                echo json_encode(['success' => false, 'message' => 'Stadium not found']);
                exit;
            }

            // Build image URLs
            $images = [];
            if (!empty($stadium->images)) {
                foreach ($stadium->images as $image) {
                    $images[] = $this->resolveImageUrl($image->image_path ?? $image->path ?? '');
                }
            }

            echo json_encode([
                'success' => true,
                'stadium' => [
                    'id' => $stadium->id,
                    'name' => $stadium->name,
                    'description' => $stadium->description,
                    'type' => $stadium->type,
                    'category' => $stadium->category,
                    'district' => $stadium->district,
                    'price' => $stadium->price,
                    'approval_status' => $stadium->approval_status,
                    'submitted_at' => $stadium->created_at,
                    'owner_name' => trim(($stadium->first_name ?? '') . ' ' . ($stadium->last_name ?? '')),
                    'owner_email' => $stadium->owner_email ?? '',
                    'owner_phone' => $stadium->owner_phone ?? '',
                    'stadium_limit' => $stadium->stadium_limit ?? null,
                    'features' => $stadium->features ?? [],
                    'images' => $images
                ]
            ]);
        } catch (Exception $e) {
            error_log('Admin Stadium Details Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    private function resolveImageUrl($path)
    {
        $path = trim((string)$path);

        if ($path === '') {
            return URLROOT . '/public/images/default-stadium.jpg';
        }

        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }

        if (strpos($path, '/') === 0) {
            return $path;
        }

        if (strpos($path, 'uploads/') === 0) {
            return URLROOT . '/' . ltrim($path, '/');
        }

        return URLROOT . '/public/uploads/stadiums/' . ltrim($path, '/');
    }

    public function approveStadium()
    {
        try {
            if (!Auth::isAdminLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stadiumId = $_POST['stadium_id'] ?? null;
                $adminId = Auth::getAdminId();

                if (!$stadiumId) {
                    echo json_encode(['success' => false, 'message' => 'Stadium ID required']);
                    exit;
                }

                // Debug: Check admin ID
                if (!$adminId) {
                    error_log('Admin ID not found in session. Session data: ' . print_r($_SESSION, true));
                    // Use NULL if admin_id not found
                    $adminId = null;
                }

                // Get stadium details for email
                $stadium = $this->adminModel->getStadiumById($stadiumId);

                if (!$stadium) {
                    echo json_encode(['success' => false, 'message' => 'Stadium not found']);
                    exit;
                }

                // Approve the stadium
                if ($this->adminModel->approveStadium($stadiumId, $adminId)) {
                    // Send approval email
                    $this->sendStadiumApprovalEmail($stadium);

                    echo json_encode(['success' => true, 'message' => 'Stadium approved successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to approve stadium']);
                }
            }
        } catch (Exception $e) {
            error_log('Admin Approve Stadium Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function rejectStadium()
    {
        try {
            if (!Auth::isAdminLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stadiumId = $_POST['stadium_id'] ?? null;
                $reason = $_POST['reason'] ?? 'No reason provided';
                $adminId = Auth::getAdminId();

                if (!$stadiumId) {
                    echo json_encode(['success' => false, 'message' => 'Stadium ID required']);
                    exit;
                }

                // Use NULL if admin_id not found
                if (!$adminId) {
                    error_log('Admin ID not found in session for rejection. Session data: ' . print_r($_SESSION, true));
                    $adminId = null;
                }

                // Get stadium details for email
                $stadium = $this->adminModel->getStadiumById($stadiumId);

                if (!$stadium) {
                    echo json_encode(['success' => false, 'message' => 'Stadium not found']);
                    exit;
                }

                // Reject the stadium
                if ($this->adminModel->rejectStadium($stadiumId, $adminId, $reason)) {
                    // Send rejection email
                    $this->sendStadiumRejectionEmail($stadium, $reason);

                    echo json_encode(['success' => true, 'message' => 'Stadium rejected']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to reject stadium']);
                }
            }
        } catch (Exception $e) {
            error_log('Admin Reject Stadium Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    private function sendStadiumApprovalEmail($stadium)
    {
        try {
            $ownerEmail = $stadium->owner_email ?? null;
            $ownerName = ($stadium->first_name ?? '') . ' ' . ($stadium->last_name ?? '');

            if (!$ownerEmail) {
                return false;
            }

            $subject = '✅ Your Stadium Has Been Approved - BookMyGround.lk';

            $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #03B200 0%, #028a00 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .stadium-details { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #03B200; }
                .cta-button { display: inline-block; background: #03B200; color: white !important; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #777; }
                .badge { background: #03B200; color: white; padding: 8px 16px; border-radius: 20px; display: inline-block; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Congratulations!</h1>
                    <h2>Your Stadium is Now Live!</h2>
                </div>
                <div class='content'>
                    <p>Dear {$ownerName},</p>
                    
                    <p>Great news! Your stadium listing has been <strong>approved</strong> and is now live on BookMyGround.lk!</p>
                    
                    <div class='badge'>✅ APPROVED</div>
                    
                    <div class='stadium-details'>
                        <h3>🏟️ Stadium Details:</h3>
                        <p><strong>Stadium Name:</strong> {$stadium->name}</p>
                        <p><strong>Type:</strong> {$stadium->type}</p>
                        <p><strong>Location:</strong> {$stadium->location}, {$stadium->district}</p>
                        <p><strong>Status:</strong> Live and visible to customers</p>
                    </div>
                    
                    <h3>🚀 What's Next?</h3>
                    <ul>
                        <li>✅ Your stadium is now visible to all customers on our platform</li>
                        <li>✅ Customers can now browse and book your facility</li>
                        <li>✅ You can manage your stadium from your dashboard</li>
                        <li>✅ You'll receive notifications for new bookings</li>
                    </ul>
                    
                    <center>
                        <a href='" . URLROOT . "/stadium_owner/properties' class='cta-button'>Go to Dashboard</a>
                    </center>
                    
                    <p><strong>Need Help?</strong><br>
                    Contact our support team if you have any questions.</p>
                    
                    <div class='footer'>
                        <p>Thank you for partnering with BookMyGround.lk!</p>
                        <p style='font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";

            $mail = new Mail();
            return $mail->send($ownerEmail, $subject, $message, MAIL_FROM, 'BookMyGround.lk');
        } catch (Exception $e) {
            error_log('Failed to send stadium approval email: ' . $e->getMessage());
            return false;
        }
    }

    private function sendStadiumRejectionEmail($stadium, $reason)
    {
        try {
            $ownerEmail = $stadium->owner_email ?? null;
            $ownerName = ($stadium->first_name ?? '') . ' ' . ($stadium->last_name ?? '');

            if (!$ownerEmail) {
                return false;
            }

            $subject = 'Stadium Submission Update - BookMyGround.lk';

            $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .reason-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #ff6b6b; }
                .cta-button { display: inline-block; background: #03B200; color: white !important; padding: 15px 30px; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #777; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Stadium Submission Update</h1>
                </div>
                <div class='content'>
                    <p>Dear {$ownerName},</p>
                    
                    <p>Thank you for submitting your stadium listing to BookMyGround.lk. After reviewing your submission, we need you to make some adjustments before we can approve it.</p>
                    
                    <div class='reason-box'>
                        <h3>📋 Reason for Review:</h3>
                        <p>{$reason}</p>
                    </div>
                    
                    <h3>🔄 What's Next?</h3>
                    <ul>
                        <li>✅ Review the feedback above carefully</li>
                        <li>✅ Make the necessary changes to your listing</li>
                        <li>✅ Update your stadium details from your dashboard</li>
                        <li>✅ Your updated listing will be reviewed again</li>
                    </ul>
                    
                    <center>
                        <a href='" . URLROOT . "/stadium_owner/properties' class='cta-button'>Edit Stadium Details</a>
                    </center>
                    
                    <p><strong>Need Help?</strong><br>
                    If you have any questions about this feedback, please contact our support team.</p>
                    
                    <div class='footer'>
                        <p>Thank you for your understanding and cooperation!</p>
                        <p style='font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";

            $mail = new Mail();
            return $mail->send($ownerEmail, $subject, $message, MAIL_FROM, 'BookMyGround.lk');
        } catch (Exception $e) {
            error_log('Failed to send stadium rejection email: ' . $e->getMessage());
            return false;
        }
    }

    // ==================== NEWSLETTER ADMIN METHODS ====================

    public function newsletter($section = 'index')
    {
        try {
            $newsletterModel = $this->model('M_Newsletter');

            // Enrolled emails list
            if ($section === 'subscribers' || $section === 'index') {
                $data = [
                    'title' => 'Newsletter Subscribers',
                    'subscribers' => $newsletterModel->getAllSubscribers(),
                    'subscriber_stats' => $newsletterModel->getSubscriberStats()
                ];
                $this->view('admin/v_newsletter_subscribers', $data);
                exit;
            }

            // Export CSV
            if ($section === 'export') {
                $subscribers = $newsletterModel->getAllSubscribers();

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=newsletter_subscribers.csv');

                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID', 'Email', 'Name', 'Status', 'Subscribed Date']);

                foreach ($subscribers as $sub) {
                    fputcsv($out, [
                        $sub->id ?? '',
                        $sub->email ?? '',
                        $sub->name ?? '',
                        $sub->status ?? '',
                        $sub->subscribed_date ?? ''
                    ]);
                }

                fclose($out);
                exit;
            }

            // Default: Newsletter management page
            $data = [
                'title' => 'Newsletter Management',
                'total_subscribers' => $newsletterModel->getTotalSubscribers(),
                'active_subscribers' => $newsletterModel->getActiveSubscribers(),
                'newsletters_sent' => $newsletterModel->getNewslettersSent(),
                'recent_newsletters' => $newsletterModel->getRecentNewsletters(5),
                'subscriber_growth' => $newsletterModel->getSubscriberGrowth(),
                'top_categories' => $newsletterModel->getTopCategories()
            ];

            $this->view('admin/v_newsletter', $data);
            exit;
        } catch (Exception $e) {
            error_log('Admin Newsletter Error: ' . $e->getMessage());
            die('Error loading newsletter admin page: ' . $e->getMessage());
        }
    }

    public function messages()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        try {
            $userId = $_SESSION['admin_id'];

            $data = [
                'title' => 'Messages',
                'conversations' => $this->messageModel->getConversationList($userId, 50),
                'unread_count' => $this->messageModel->getUnreadCount($userId)
            ];

            $this->view('admin/v_messages', $data);
        } catch (Exception $e) {
            error_log('Admin Messages Error: ' . $e->getMessage());
            die('Error in Admin messages: ' . $e->getMessage());
        }
    }

    public function send_reply()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        try {
            $userId = $_SESSION['admin_id'];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $messageData = [
                    'message_id' => $_POST['message_id'] ?? '',
                    'other_user_id' => $_POST['other_user_id'] ?? '',
                    'stadium_id' => $_POST['stadium_id'] ?? '',
                    'reply_content' => $_POST['reply_content'] ?? ''
                ];

                if ($this->messageModel->sendMessage($userId, $messageData['other_user_id'], $messageData['stadium_id'], 'Re: Message', $messageData['reply_content'])) {
                    echo json_encode(['success' => true, 'message' => 'Reply sent successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to send reply']);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error sending reply']);
        }
    }
}
