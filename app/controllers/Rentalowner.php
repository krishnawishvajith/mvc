<?php
class Rentalowner extends Controller {
    private $rentalOwnerModel;
    private $messageModel;

    public function __construct()
    {
        try {
            $this->rentalOwnerModel = $this->model('M_RentalOwner');
            $this->messageModel = $this->model('M_Messages');
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!Auth::isLoggedIn()) {
                error_log('Rental owner not logged in, redirecting...');
                header('Location: ' . URLROOT . '/login');
                exit;
            }
            
            if (!Auth::hasRole('rental_owner')) {
                error_log('User does not have rental_owner role, redirecting...');
                header('Location: ' . URLROOT . '/login');
                exit;
            }
            
        } catch (Exception $e) {
            error_log('Rental Owner Controller Constructor Error: ' . $e->getMessage());
            die('Error in Rental Owner controller: ' . $e->getMessage());
        }
    }

    public function index() {
        try {
            $userId = Auth::getUserId();
            
            if (!$userId) {
                die('User ID not found in session');
            }
            
            // Get rental owner stats
            $stats = $this->rentalOwnerModel->getOwnerStats($userId);
            
            $data = [
                'title' => 'Rental Owner Dashboard',
                'user_name' => Auth::getUserName() ?: 'Owner',
                'user_first_name' => Auth::getUserFirstName() ?: 'Owner',
                'stats' => $stats,
                'recent_rentals' => $this->rentalOwnerModel->getRecentRentals($userId, 5),
                'upcoming_schedules' => $this->rentalOwnerModel->getUpcomingSchedules($userId),
                'revenue_overview' => $this->rentalOwnerModel->getRevenueOverview($userId),
                'shop_summary' => $this->rentalOwnerModel->getShopSummary($userId),
                'package_info' => $this->rentalOwnerModel->getPackageInfo($userId)
            ];

            $this->view('rentalowner/v_dashboard', $data);
            
        } catch (Exception $e) {
            error_log('Rental Owner Index Error: ' . $e->getMessage());
            die('Error in Rental Owner index: ' . $e->getMessage());
        }
    }

    public function shopManagement() {
        try {
            $userId = Auth::getUserId();
            
            // Get package limits
            $limits = $this->rentalOwnerModel->getPackageLimits($userId);
            
            $data = [
                'title' => 'Shop Management',
                'shops' => $this->rentalOwnerModel->getAllShops($userId),
                'limits' => $limits,
                'success_message' => isset($_GET['success']) ? $this->getSuccessMessage($_GET['success']) : ''
            ];

            $this->view('rentalowner/shop_managment', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Shop Management Error: ' . $e->getMessage());
            die('Error in Rental Owner shop management: ' . $e->getMessage());
        }
    }

    private function getSuccessMessage($type) {
        $messages = [
            'added' => '✅ Shop added successfully!',
            'pending' => '⏳ Your shop has been submitted successfully! It will be reviewed and added within 2 business days.',
            'updated' => '✅ Shop updated successfully!',
            'deleted' => '✅ Shop deleted successfully!',
            'approved' => '✅ Your shop has been approved and is now live!'
        ];
        return $messages[$type] ?? '';
    }
    
    private function sendShopSubmissionEmail($shopData) {
        try {
            $userEmail = Auth::getUserEmail();
            $userName = Auth::getUserName();
            
            if (!$userEmail) {
                return false;
            }
            
            $subject = 'Shop Submission Received - BookMyGround.lk';
            
            $message = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #03B200 0%, #028a00 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                    .shop-details { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #03B200; }
                    .detail-row { margin: 10px 0; }
                    .label { font-weight: bold; color: #03B200; }
                    .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #777; }
                    .badge { background: #03B200; color: white; padding: 8px 16px; border-radius: 20px; display: inline-block; margin: 10px 0; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🏪 Shop Submission Received!</h1>
                    </div>
                    <div class='content'>
                        <p>Dear {$userName},</p>
                        
                        <p>Thank you for submitting your sports equipment rental shop to BookMyGround.lk!</p>
                        
                        <div class='badge'>⏳ Under Review</div>
                        
                        <div class='shop-details'>
                            <h3>📋 Submitted Shop Details:</h3>
                            <div class='detail-row'><span class='label'>Shop Name:</span> {$shopData['shop_name']}</div>
                            <div class='detail-row'><span class='label'>Category:</span> {$shopData['category']}</div>
                            <div class='detail-row'><span class='label'>District:</span> {$shopData['district']}</div>
                            <div class='detail-row'><span class='label'>Contact:</span> {$shopData['contact_email']}</div>
                        </div>
                        
                        <h3>⏰ What Happens Next?</h3>
                        <ul>
                            <li>✅ Our admin team will review your shop listing</li>
                            <li>✅ This process typically takes <strong>1-2 business days</strong></li>
                            <li>✅ You'll receive an email once your shop is approved</li>
                            <li>✅ Your shop will then appear on our website for customers to book</li>
                        </ul>
                        
                        <p><strong>Need to make changes?</strong><br>
                        You can edit your shop details from your dashboard at any time before approval.</p>
                        
                        <div class='footer'>
                            <p>Thank you for choosing BookMyGround.lk!</p>
                            <p style='font-size: 12px;'>This is an automated message. Please do not reply to this email.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail = new Mail();
            return $mail->send($userEmail, $subject, $message, MAIL_FROM, 'BookMyGround.lk');
            
        } catch (Exception $e) {
            error_log('Failed to send shop submission email: ' . $e->getMessage());
            return false;
        }
    }

    public function addShop() {
        try {
            $userId = Auth::getUserId();
            
            // Check package limits
            $limits = $this->rentalOwnerModel->getPackageLimits($userId);
            
            if (!$limits['can_add_more']) {
                $data = [
                    'title' => 'Add New Shop',
                    'error' => 'You have reached your package limit of ' . $limits['shops_limit'] . ' shops. Please upgrade your package to add more.',
                    'limits' => $limits
                ];
                $this->view('rentalowner/add_shop', $data);
                return;
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Handle shop addition
                $shopData = [
                    'shop_name' => trim($_POST['shop_name'] ?? ''),
                    'address' => trim($_POST['address'] ?? ''),
                    'district' => trim($_POST['district'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'category' => trim($_POST['category'] ?? ''),
                    'contact_email' => trim($_POST['contact_email'] ?? ''),
                    'contact_phone' => trim($_POST['contact_phone'] ?? ''),
                    'operating_hours' => trim($_POST['operating_hours'] ?? ''),
                    'daily_rate' => floatval($_POST['daily_rate'] ?? 0),
                    'equipment_count' => intval($_POST['equipment_count'] ?? 0),
                    'equipment_types' => isset($_POST['equipment_types']) ? (array)$_POST['equipment_types'] : [],
                    'amenities' => isset($_POST['amenities']) ? (array)$_POST['amenities'] : [],
                    'status' => 'pending' // Set to pending for admin approval
                ];
                
                // Handle image uploads (up to 5 images based on package)
                $uploadedImages = [];
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $imageCount = count($_FILES['images']['name']);
                    $maxImages = $limits['images_per_shop'];
                    
                    for ($i = 0; $i < min($imageCount, $maxImages); $i++) {
                        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                            $tmpName = $_FILES['images']['tmp_name'][$i];
                            $fileName = $_FILES['images']['name'][$i];
                            $fileSize = $_FILES['images']['size'][$i];
                            $fileType = $_FILES['images']['type'][$i];
                            
                            // Validate file type
                            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                            if (in_array($fileType, $allowedTypes) && $fileSize < 5000000) { // 5MB max
                                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                $newFileName = uniqid('shop_') . '_' . time() . '.' . $extension;
                                $uploadPath = APPROOT . '/../public/uploads/rental_shops/' . $newFileName;
                                
                                if (move_uploaded_file($tmpName, $uploadPath)) {
                                    $uploadedImages[] = 'uploads/rental_shops/' . $newFileName;
                                }
                            }
                        }
                    }
                }
                
                $shopId = $this->rentalOwnerModel->addShop($userId, $shopData, $uploadedImages);
                
                if ($shopId) {
                    // Send submission confirmation email
                    $this->sendShopSubmissionEmail($shopData);
                    
                    header('Location: ' . URLROOT . '/rentalowner/shopManagement?success=pending');
                    exit;
                } else {
                    $data['error'] = 'Failed to add shop. Please try again.';
                }
            }
            
            $data = [
                'title' => 'Add New Shop',
                'limits' => $limits
            ];

            $this->view('rentalowner/add_shop', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Add Shop Error: ' . $e->getMessage());
            die('Error in Rental Owner add shop: ' . $e->getMessage());
        }
    }

    public function editShop($id = null) {
        try {
            $userId = Auth::getUserId();
            
            if (!$id) {
                header('Location: ' . URLROOT . '/rentalowner/shopManagement');
                exit;
            }
            
            // Get package limits for image validation
            $limits = $this->rentalOwnerModel->getPackageLimits($userId);
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Handle shop update with all fields
                $shopData = [
                    'shop_name' => trim($_POST['shop_name'] ?? ''),
                    'address' => trim($_POST['address'] ?? ''),
                    'district' => trim($_POST['district'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'category' => trim($_POST['category'] ?? ''),
                    'contact_email' => trim($_POST['contact_email'] ?? ''),
                    'contact_phone' => trim($_POST['contact_phone'] ?? ''),
                    'operating_hours' => trim($_POST['operating_hours'] ?? ''),
                    'daily_rate' => floatval($_POST['daily_rate'] ?? 0),
                    'equipment_count' => intval($_POST['equipment_count'] ?? 0),
                    'equipment_types' => isset($_POST['equipment_types']) ? (array)$_POST['equipment_types'] : [],
                    'amenities' => isset($_POST['amenities']) ? (array)$_POST['amenities'] : [],
                    'status' => $_POST['status'] ?? 'active'
                ];
                
                // Handle new image uploads (optional - add to existing images)
                $newImages = [];
                if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                    $imageCount = count($_FILES['images']['name']);
                    $maxImages = $limits['images_per_shop'];
                    
                    // Get current image count for this shop
                    $currentImages = $this->rentalOwnerModel->getShopImageCount($id);
                    $availableSlots = $maxImages - $currentImages;
                    
                    for ($i = 0; $i < min($imageCount, $availableSlots); $i++) {
                        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                            $tmpName = $_FILES['images']['tmp_name'][$i];
                            $fileName = $_FILES['images']['name'][$i];
                            $fileSize = $_FILES['images']['size'][$i];
                            $fileType = $_FILES['images']['type'][$i];
                            
                            // Validate file type
                            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                            if (in_array($fileType, $allowedTypes) && $fileSize < 5000000) {
                                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                $newFileName = 'shop_' . $id . '_' . uniqid() . '.' . $extension;
                                $uploadPath = PUBLICPATH . '/uploads/rental_shops/' . $newFileName;
                                
                                if (move_uploaded_file($tmpName, $uploadPath)) {
                                    $newImages[] = 'uploads/rental_shops/' . $newFileName;
                                }
                            }
                        }
                    }
                }
                
                if ($this->rentalOwnerModel->updateShop($userId, $id, $shopData, $newImages)) {
                    header('Location: ' . URLROOT . '/rentalowner/shopManagement?success=updated');
                    exit;
                } else {
                    $data['error'] = 'Failed to update shop';
                }
            }
            
            $data = [
                'title' => 'Edit Shop',
                'shop' => $this->rentalOwnerModel->getShop($userId, $id),
                'limits' => $limits
            ];

            $this->view('rentalowner/edit_shop', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Edit Shop Error: ' . $e->getMessage());
            die('Error in Rental Owner edit shop: ' . $e->getMessage());
        }
    }

    public function messages() {
        try {
            $userId = Auth::getUserId();
            
            $data = [
                'title' => 'Messages',
                'conversations' => $this->messageModel->getConversationList($userId, 100),
                'unread_count' => $this->messageModel->getUnreadCount($userId)
            ];

            $this->view('rentalowner/v_messages', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Messages Error: ' . $e->getMessage());
            die('Error in Rental Owner messages: ' . $e->getMessage());
        }
    }

    public function advertisment() {
        try {
            $userId = Auth::getUserId();
            $model = $this->model('M_RentalOwner');
            
            $data = [
                'title' => 'My Advertisements',
                'advertisements' => $model->getAdvertisements($userId),
                'ad_packages' => $model->getAdvertisementPackages()
            ];

            $this->view('rentalowner/v_advertisment', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Advertisement Error: ' . $e->getMessage());
            die('Error in Rental Owner advertisement: ' . $e->getMessage());
        }
    }

    public function submitAdvertisement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/rentalowner/advertisment');
            exit;
        }

        $userId = Auth::getUserId();
        $model = $this->model('M_RentalOwner');

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'contact_name' => trim((Auth::getUserFirstName() ?? '') . ' ' . (Auth::getUserLastName() ?? '')),
            'email' => Auth::getUserEmail() ?? '',
            'phone' => '',
            'package' => $_POST['package'] ?? 'basic',
            'website' => trim($_POST['website'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'Business/Service name is required.';
            header('Location: ' . URLROOT . '/rentalowner/advertisment');
            exit;
        }

        // Handle file upload
        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['ad_image'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024;

            if (!in_array($file['type'], $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Use JPG, PNG, GIF or WEBP.';
                header('Location: ' . URLROOT . '/rentalowner/advertisment');
                exit;
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['error'] = 'File too large. Max 5MB allowed.';
                header('Location: ' . URLROOT . '/rentalowner/advertisment');
                exit;
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'ad_' . $userId . '_' . time() . '.' . $extension;
            $uploadDir = APPROOT . '/../public/images/advertisements/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $data['file_path'] = $filename;
            }
        }

        if ($model->createAdvertisement($userId, $data)) {
            $_SESSION['success'] = 'Advertisement submitted! We will review and contact you soon.';
        } else {
            $_SESSION['error'] = 'Failed to submit advertisement.';
        }

        header('Location: ' . URLROOT . '/rentalowner/advertisment');
        exit;
    }

    public function deleteAdvertisement($id) {
        $userId = Auth::getUserId();
        $model = $this->model('M_RentalOwner');

        if ($model->deleteAdvertisement($id, $userId)) {
            $_SESSION['success'] = 'Advertisement removed.';
        } else {
            $_SESSION['error'] = 'Failed to remove advertisement.';
        }

        header('Location: ' . URLROOT . '/rentalowner/advertisment');
        exit;
    }

    public function editAdvertisement($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/rentalowner/advertisment');
            exit;
        }

        $userId = Auth::getUserId();
        $model = $this->model('M_RentalOwner');
        
        $existingAd = $model->getAdvertisementById($id, $userId);
        if (!$existingAd) {
            $_SESSION['error'] = 'Advertisement not found.';
            header('Location: ' . URLROOT . '/rentalowner/advertisment');
            exit;
        }

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'Business/Service name is required.';
            header('Location: ' . URLROOT . '/rentalowner/advertisment');
            exit;
        }

        // Handle new file upload
        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['ad_image'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024;

            if (in_array($file['type'], $allowed) && $file['size'] <= $maxSize) {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'ad_' . $userId . '_' . time() . '.' . $extension;
                $uploadDir = APPROOT . '/../public/images/advertisements/';

                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $data['file_path'] = $filename;
                }
            }
        }

        if ($model->updateAdvertisement($id, $userId, $data)) {
            $_SESSION['success'] = 'Advertisement updated! It will be reviewed again for approval.';
        } else {
            $_SESSION['error'] = 'Failed to update advertisement.';
        }

        header('Location: ' . URLROOT . '/rentalowner/advertisment');
        exit;
    }

    public function blog() {
        try {
            $data = [
                'title' => 'Blog Management',
                'posts' => [
                    ['id' => 1, 'title' => 'Beginner\'s Guide: What to Rent for Your First Cricket Match', 'author' => 'Krishna Wishvajith', 'category' => 'Cricket', 'status' => 'Published', 'published' => '2025-08-18', 'views' => 1250],
                    ['id' => 2, 'title' => 'How to Choose the Right Football for Different Grounds', 'author' => 'Krishna Wishvajith', 'category' => 'Football', 'status' => 'Draft', 'published' => '', 'views' => 0],
                ]
            ];

            $this->view('rentalowner/v_blog', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Blog Error: ' . $e->getMessage());
            die('Error in Rental Owner blog: ' . $e->getMessage());
        }
    }

    public function profile() {
        try {
            $userId = Auth::getUserId();
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Handle profile update
                $profileData = [
                    'owner_name' => $_POST['owner_name'] ?? '',
                    'business_name' => $_POST['business_name'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'address' => $_POST['address'] ?? ''
                ];
                
                if ($this->rentalOwnerModel->updateProfile($userId, $profileData)) {
                    $data['success'] = 'Profile updated successfully!';
                } else {
                    $data['error'] = 'Failed to update profile';
                }
            }
            
            $data = [
                'title' => 'My Profile',
                'profile_data' => $this->rentalOwnerModel->getProfileData($userId)
            ];

            $this->view('rentalowner/v_profile', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Profile Error: ' . $e->getMessage());
            die('Error in Rental Owner profile: ' . $e->getMessage());
        }
    }

    public function logout() {
        try {
            // Clear all session data
            session_unset();
            session_destroy();
            
            // Start a new session
            session_start();
            
            header('Location: ' . URLROOT . '/login');
            exit;
        } catch (Exception $e) {
            error_log('Rental Owner Logout Error: ' . $e->getMessage());
            die('Error in Rental Owner logout: ' . $e->getMessage());
        }
    }
    
    public function deleteShop($id = null) {
        try {
            // Only accept POST requests
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }
            
            $userId = Auth::getUserId();
            
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Shop ID required']);
                exit;
            }
            
            // Verify ownership and delete
            if ($this->rentalOwnerModel->deleteShop($userId, $id)) {
                echo json_encode(['success' => true, 'message' => 'Shop deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete shop or you do not own this shop']);
            }
            
        } catch (Exception $e) {
            error_log('Rental Owner Delete Shop Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
}
?>