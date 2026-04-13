<?php
class Stadium_owner extends Controller
{
    private $stadiumOwnerModel;
    private $messageModel;

    public function __construct()
    {
        try {
            $this->stadiumOwnerModel = $this->model('M_Stadium_owner');
            $this->messageModel = $this->model('M_Messages');

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (!Auth::isLoggedIn()) {
                error_log('Stadium owner not logged in, redirecting...');
                header('Location: ' . URLROOT . '/login');
                exit;
            }

            if (!Auth::hasRole('stadium_owner')) {
                error_log('User does not have stadium_owner role, redirecting...');
                header('Location: ' . URLROOT . '/login');
                exit;
            }
        } catch (Exception $e) {
            error_log('Stadium Owner Controller Constructor Error: ' . $e->getMessage());
            die('Error in Stadium Owner controller: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $userId = Auth::getUserId();

            if (!$userId) {
                die('User ID not found in session');
            }

            // Get stadium owner stats
            $stats = $this->stadiumOwnerModel->getOwnerStats($userId);

            $data = [
                'title' => 'Stadium Owner Dashboard',
                'user_name' => Auth::getUserName() ?: 'Owner',
                'user_first_name' => Auth::getUserFirstName() ?: 'Owner',
                'stats' => $stats,
                'package_info' => $this->stadiumOwnerModel->getPackageInfo($userId),
                'stadium_summary' => $this->stadiumOwnerModel->getStadiumSummary($userId),
                'recent_bookings' => $this->stadiumOwnerModel->getRecentBookings($userId, 5),
                'upcoming_schedules' => $this->stadiumOwnerModel->getUpcomingSchedules($userId),
                'revenue_overview' => $this->stadiumOwnerModel->getRevenueOverview($userId)
            ];

            $this->view('stadium_owner/dashboard', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Index Error: ' . $e->getMessage());
            die('Error in Stadium Owner index: ' . $e->getMessage());
        }
    }

    public function properties()
    {
        try {
            $userId = Auth::getUserId();

            $data = [
                'title' => 'My Properties',
                'properties' => $this->stadiumOwnerModel->getAllProperties($userId),
                'package_limits' => $this->stadiumOwnerModel->getPackageLimits($userId)
            ];

            $this->view('stadium_owner/v_properties', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Properties Error: ' . $e->getMessage());
            die('Error in Stadium Owner properties: ' . $e->getMessage());
        }
    }

    public function add_property()
    {
        try {
            $userId = Auth::getUserId();

            // Check package limits first
            $limits = $this->stadiumOwnerModel->getPackageLimits($userId);

            // Check if user can add more stadiums
            if (!$limits['can_add_more']) {
                $data = [
                    'title' => 'Add New Stadium',
                    'error' => 'You have reached your package limit of ' . $limits['stadium_limit'] . ' stadiums. Please upgrade your package to add more.',
                    'limits' => $limits
                ];
                $this->view('stadium_owner/v_add_property', $data);
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Handle property addition
                $propertyData = $_POST;

                // Handle image uploads
                $uploadedImages = [];
                $uploadErrors = [];
                $uploadDir = APPROOT . '/../public/uploads/stadiums/';

                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        $uploadErrors[] = 'Unable to create stadium image upload directory. Please check server permissions.';
                    }
                }

                if (empty($uploadErrors) && !is_writable($uploadDir)) {
                    $uploadErrors[] = 'Stadium image upload directory is not writable. Please update permissions for public/uploads/stadiums.';
                }

                if (isset($_FILES['stadium_images']) && !empty($_FILES['stadium_images']['name'][0]) && empty($uploadErrors)) {
                    $imageCount = count($_FILES['stadium_images']['name']);
                    $maxImages = $limits['photos_per_stadium'];

                    // Check if exceeds package limit
                    if ($imageCount > $maxImages) {
                        $data['error'] = "You can only upload up to {$maxImages} images with your current package.";
                        $data['title'] = 'Add New Stadium';
                        $data['limits'] = $limits;
                        $this->view('stadium_owner/v_add_property', $data);
                        return;
                    }

                    // Process each uploaded image
                    for ($i = 0; $i < $imageCount; $i++) {
                        $errorCode = $_FILES['stadium_images']['error'][$i];
                        if ($errorCode !== UPLOAD_ERR_OK) {
                            $uploadErrors[] = "Image " . ($i + 1) . ": Upload failed with error code {$errorCode}.";
                            continue;
                        }

                        $fileName = $_FILES['stadium_images']['name'][$i];
                        $fileTmpName = $_FILES['stadium_images']['tmp_name'][$i];
                        $fileSize = $_FILES['stadium_images']['size'][$i];
                        $fileType = $_FILES['stadium_images']['type'][$i];

                        // Validate image
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        $maxFileSize = 5 * 1024 * 1024; // 5MB

                        if (!in_array($fileType, $allowedTypes)) {
                            $uploadErrors[] = "Image " . ($i + 1) . ": Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.";
                            continue;
                        }

                        if ($fileSize > $maxFileSize) {
                            $uploadErrors[] = "Image " . ($i + 1) . ": File size too large. Maximum 5MB allowed.";
                            continue;
                        }

                        // Generate unique filename
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $newFileName = 'stadium_' . $userId . '_' . time() . '_' . $i . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $newFileName;

                        // Move uploaded file
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            $uploadedImages[] = $newFileName;
                        } else {
                            $phpError = error_get_last();
                            $uploadErrors[] = "Image " . ($i + 1) . ": Failed to upload." . (!empty($phpError['message']) ? ' ' . $phpError['message'] : '');
                        }
                    }
                }

                // Check for upload errors
                if (!empty($uploadErrors)) {
                    $data['error'] = implode('<br>', $uploadErrors);
                    $data['title'] = 'Add New Stadium';
                    $data['limits'] = $limits;
                    $data['form_data'] = $_POST;
                    $this->view('stadium_owner/v_add_property', $data);
                    return;
                }

                // Check if at least one image was uploaded
                if (empty($uploadedImages)) {
                    $data['error'] = 'Please upload at least one image for your stadium.';
                    $data['title'] = 'Add New Stadium';
                    $data['limits'] = $limits;
                    $data['form_data'] = $_POST;
                    $this->view('stadium_owner/v_add_property', $data);
                    return;
                }

                // Add uploaded images to property data
                $propertyData['uploaded_images'] = $uploadedImages;
                $propertyData['primary_image'] = $uploadedImages[0]; // First image as primary

                $result = $this->stadiumOwnerModel->addProperty($userId, $propertyData);

                if ($result) {
                    header('Location: ' . URLROOT . '/stadium_owner/properties?success=added');
                    exit;
                } else {
                    // Delete uploaded images if database insert fails
                    foreach ($uploadedImages as $image) {
                        $filePath = APPROOT . '/../public/uploads/stadiums/' . $image;
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    $data['error'] = 'Failed to add stadium. Please try again.';
                }
            }

            $data = [
                'title' => 'Add New Stadium',
                'limits' => $limits
            ];

            $this->view('stadium_owner/v_add_property', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Add Property Error: ' . $e->getMessage());
            die('Error in Stadium Owner add property: ' . $e->getMessage());
        }
    }

    public function edit_property($id = null)
    {
        try {
            $userId = Auth::getUserId();

            if (!$id) {
                header('Location: ' . URLROOT . '/stadium_owner/properties');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Handle property update
                $propertyData = $_POST;
                $data['id'] = $id;

                if ($this->stadiumOwnerModel->updateProperty($userId, $id, $propertyData)) {
                    $data['success'] = 'Property updated successfully!';
                } else {
                    $data['error'] = 'Failed to update property';
                }
            }

            $data = [
                'title' => 'Edit Property',
                'property' => $this->stadiumOwnerModel->getProperty($userId, $id)
            ];

            $this->view('stadium_owner/v_edit_property', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Edit Property Error: ' . $e->getMessage());
            die('Error in Stadium Owner edit property: ' . $e->getMessage());
        }
    }

    public function bookings()
    {
        try {
            $userId = Auth::getUserId();

            $data = [
                'title' => 'Booking History',
                'all_bookings' => $this->stadiumOwnerModel->getAllBookings($userId),
                'booking_stats' => $this->stadiumOwnerModel->getBookingStats($userId)
            ];

            $this->view('stadium_owner/v_bookings', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Bookings Error: ' . $e->getMessage());
            die('Error in Stadium Owner bookings: ' . $e->getMessage());
        }
    }

    public function messages()
    {
        try {
            $userId = Auth::getUserId();

            $data = [
                'title' => 'Messages',
                'conversations' => $this->messageModel->getConversationList($userId, 50),
                'unread_count' => $this->stadiumOwnerModel->getUnreadMessageCount($userId)
            ];

            $this->view('stadium_owner/v_messages', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Messages Error: ' . $e->getMessage());
            die('Error in Stadium Owner messages: ' . $e->getMessage());
        }
    }

    public function send_reply()
    {
        try {
            $userId = Auth::getUserId();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $messageData = [
                    'message_id' => $_POST['message_id'] ?? '',
                    'other_user_id' => $_POST['other_user_id'] ?? '',
                    'stadium_id' => $_POST['stadium_id'] ?? '',
                    'reply_content' => $_POST['reply_content'] ?? ''
                ];

                if ($this->stadiumOwnerModel->sendReply($userId, $messageData)) {
                    echo json_encode(['success' => true, 'message' => 'Reply sent successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to send reply']);
                }
            }
        } catch (Exception $e) {
            error_log('Stadium Owner send_reply error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error sending reply']);
        }
    }

    public function revenue()
    {
        try {
            $userId = Auth::getUserId();

            $data = [
                'title' => 'Revenue & Analytics',
                'revenue_data' => $this->stadiumOwnerModel->getRevenueData($userId),
                'analytics' => $this->stadiumOwnerModel->getAnalytics($userId)
            ];

            $this->view('stadium_owner/v_revenue', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Revenue Error: ' . $e->getMessage());
            die('Error in Stadium Owner revenue: ' . $e->getMessage());
        }
    }

    public function profile()
    {
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

                if ($this->stadiumOwnerModel->updateProfile($userId, $profileData)) {
                    $data['success'] = 'Profile updated successfully!';
                } else {
                    $data['error'] = 'Failed to update profile';
                }
            }

            $data = [
                'title' => 'My Profile',
                'profile_data' => $this->stadiumOwnerModel->getProfileData($userId)
            ];

            $this->view('stadium_owner/v_profile', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Profile Error: ' . $e->getMessage());
            die('Error in Stadium Owner profile: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            // Clear all session data
            session_unset();
            session_destroy();

            // Start a new session
            session_start();

            header('Location: ' . URLROOT . '/login');
            exit;
        } catch (Exception $e) {
            error_log('Stadium Owner Logout Error: ' . $e->getMessage());
            die('Error in Stadium Owner logout: ' . $e->getMessage());
        }
    }

    public function delete_property($id = null)
    {
        try {
            $userId = Auth::getUserId();

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'No property ID provided']);
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                return;
            }

            $result = $this->stadiumOwnerModel->deleteProperty($userId, $id);

            echo json_encode($result);
        } catch (Exception $e) {
            error_log('Stadium Owner Delete Property Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error deleting property']);
        }
    }

    /* ============================================
       ADVERTISEMENT METHODS
    ============================================ */

    public function advertisements()
    {
        try {
            $userId = Auth::getUserId();

            $data = [
                'title' => 'Advertisements',
                'user_first_name' => Auth::getUserFirstName() ?: 'Owner',
                'advertisements' => $this->stadiumOwnerModel->getAdvertisements($userId),
                'ad_packages' => $this->stadiumOwnerModel->getAdvertisementPackages()
            ];

            $this->view('stadium_owner/v_advertisements', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Advertisements Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Error loading advertisements.';
            header('Location: ' . URLROOT . '/stadium_owner');
            exit;
        }
    }

    public function submitAdvertisement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/stadium_owner/advertisements');
            exit;
        }

        $userId = Auth::getUserId();

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
            $_SESSION['error'] = 'Business/Company name is required.';
            header('Location: ' . URLROOT . '/stadium_owner/advertisements');
            exit;
        }

        // Handle file upload
        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['ad_image'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024;

            if (!in_array($file['type'], $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Use JPG, PNG, GIF or WEBP.';
                header('Location: ' . URLROOT . '/stadium_owner/advertisements');
                exit;
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['error'] = 'File too large. Max 5MB allowed.';
                header('Location: ' . URLROOT . '/stadium_owner/advertisements');
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

        if ($this->stadiumOwnerModel->createAdvertisement($userId, $data)) {
            $_SESSION['success'] = 'Advertisement submitted! We will review and contact you soon.';
        } else {
            $_SESSION['error'] = 'Failed to submit advertisement.';
        }

        header('Location: ' . URLROOT . '/stadium_owner/advertisements');
        exit;
    }

    public function deleteAdvertisement($id)
    {
        $userId = Auth::getUserId();

        if ($this->stadiumOwnerModel->deleteAdvertisement($id, $userId)) {
            $_SESSION['success'] = 'Advertisement removed.';
        } else {
            $_SESSION['error'] = 'Failed to remove advertisement.';
        }

        header('Location: ' . URLROOT . '/stadium_owner/advertisements');
        exit;
    }

    public function editAdvertisement($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/stadium_owner/advertisements');
            exit;
        }

        $userId = Auth::getUserId();

        $existingAd = $this->stadiumOwnerModel->getAdvertisementById($id, $userId);
        if (!$existingAd) {
            $_SESSION['error'] = 'Advertisement not found.';
            header('Location: ' . URLROOT . '/stadium_owner/advertisements');
            exit;
        }

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'Business/Company name is required.';
            header('Location: ' . URLROOT . '/stadium_owner/advertisements');
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

        if ($this->stadiumOwnerModel->updateAdvertisement($id, $userId, $data)) {
            $_SESSION['success'] = '✏️ Advertisement updated! It will be reviewed again for approval.';
        } else {
            $_SESSION['error'] = 'Failed to update advertisement.';
        }

        header('Location: ' . URLROOT . '/stadium_owner/advertisements');
        exit;
    }
}
