<?php

class Customer extends Controller
{
    private $customerModel;
    private $messageModel;


    public function __construct()
    {
        $this->customerModel = $this->model('M_Customer');
        $this->messageModel = $this->model('M_Messages');

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!Auth::isLoggedIn() || !Auth::hasRole('customer')) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }
    }


    /* ============================================
       MAIN DASHBOARD
    ============================================ */

    public function index()
    {
        $userId = Auth::getUserId();

        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'title'              => 'Customer Dashboard',
            'stats'              => $this->customerModel->getCustomerStats($userId),
            'recent_bookings'    => $this->customerModel->getRecentBookings($userId, 5),
            'conversations'      => $this->messageModel->getConversationList($userId, 50),
            'unread_count'       => $this->messageModel->getUnreadCount($userId),
            'favorite_stadiums'  => $this->customerModel->getFavoriteStadiums($userId),
            'payment_history'    => $this->customerModel->getPaymentHistory($userId),
            'payment_summary'    => $this->customerModel->getPaymentSummary($userId),
            'emergency_contacts' => $this->customerModel->getEmergencyContacts($userId),
            'profile_data'       => $this->customerModel->getProfileData($userId),
            'advertisements'     => $this->customerModel->getCustomerAdvertisements($userId),
            'ad_packages'        => $this->customerModel->getAdvertisementPackages()
        ];

        $this->view('customer/customer', $data);
    }

    public function getConversation()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $otherUserId = isset($_POST['other_user_id']) ? (int)$_POST['other_user_id'] : 0;
        $stadiumId = isset($_POST['stadium_id']) && $_POST['stadium_id'] !== '' ? (int)$_POST['stadium_id'] : null;

        if (!$otherUserId) {
            echo json_encode(['success' => false, 'message' => 'Conversation partner is required']);
            exit;
        }

        $this->messageModel->markConversationAsRead($userId, $otherUserId, $stadiumId);
        $messages = $this->messageModel->getConversation($userId, $otherUserId, $stadiumId);

        echo json_encode([
            'success' => true,
            'messages' => $messages,
            'user_id' => $userId
        ]);
        exit;
    }


    /* ============================================
       PROFILE METHODS
    ============================================ */

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/customer');
            exit;
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $this->handleProfilePictureUpload($userId);
        }

        $age_group = str_replace('-', '_', $_POST['age_group'] ?? '');

        $payload = [
            'first_name'  => $_POST['first_name'] ?? '',
            'last_name'   => $_POST['last_name'] ?? '',
            'phone'       => $_POST['phone'] ?? '',
            'district'    => $_POST['district'] ?? '',
            'sports'      => $_POST['preferred_sports'] ?? '',
            'age_group'   => $age_group,
            'skill_level' => $_POST['skill_level'] ?? '',
        ];

        if ($this->customerModel->updateProfile($userId, $payload)) {
            $_SESSION['success'] = 'Profile updated!';
        } else {
            $_SESSION['error'] = 'Failed to update profile.';
        }

        header('Location: ' . URLROOT . '/customer#profile');
        exit;
    }


    private function handleProfilePictureUpload($userId)
    {
        $file     = $_FILES['profile_picture'];
        $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize  = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowed)) {
            $_SESSION['error'] = 'Invalid file type. Use JPG, PNG, GIF or WEBP.';
            return false;
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'File too large. Max 5MB allowed.';
            return false;
        }

        $oldPicture = $this->customerModel->getProfilePicture($userId);
        if ($oldPicture) {
            $oldPath = APPROOT . '/../public/images/profiles/' . $oldPicture;
            if (file_exists($oldPath) && $oldPicture !== 'default-avatar.png') {
                unlink($oldPath);
            }
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename  = 'user_' . $userId . '_' . time() . '.' . $extension;
        $uploadDir = APPROOT . '/../public/images/profiles/';
        $uploadPath = $uploadDir . $filename;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $this->customerModel->updateProfilePicture($userId, $filename);
            return true;
        }

        return false;
    }


    public function deleteProfilePicture()
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->deleteProfilePicture($userId)) {
            $_SESSION['success'] = 'Profile picture removed.';
        } else {
            $_SESSION['error'] = 'Failed to remove picture.';
        }

        header('Location: ' . URLROOT . '/customer#profile');
        exit;
    }


    /* ============================================
       EMERGENCY CONTACT METHODS
    ============================================ */

    public function addEmergencyContact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/customer#emergency-contacts');
            exit;
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'contact_name' => $_POST['contact_name'] ?? '',
            'relationship' => $_POST['relationship'] ?? '',
            'phone'        => $_POST['phone'] ?? '',
            'email'        => $_POST['email'] ?? null,
        ];

        if ($this->customerModel->createEmergencyContact($userId, $data)) {
            $_SESSION['success'] = 'Contact added.';
        } else {
            $_SESSION['error'] = 'Failed to add contact.';
        }

        header('Location: ' . URLROOT . '/customer#emergency-contacts');
        exit;
    }


    public function deleteEmergencyContact($id)
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->deleteEmergencyContact($id, $userId)) {
            $_SESSION['success'] = 'Contact deleted.';
        } else {
            $_SESSION['error'] = 'Could not delete contact.';
        }

        header('Location: ' . URLROOT . '/customer#emergency-contacts');
        exit;
    }


    /* ============================================
       PAYMENT METHODS
    ============================================ */

    public function deletePayment($id)
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->deletePayment($id, $userId)) {
            $_SESSION['success'] = 'Payment deleted.';
        } else {
            $_SESSION['error'] = 'Could not delete payment.';
        }

        header('Location: ' . URLROOT . '/customer#payments');
        exit;
    }


    public function clearAllPayments()
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->clearAllPayments($userId)) {
            $_SESSION['success'] = 'Payment history cleared.';
        } else {
            $_SESSION['error'] = 'Could not clear history.';
        }

        header('Location: ' . URLROOT . '/customer#payments');
        exit;
    }


    /* ============================================
       FAVORITE STADIUM METHODS
    ============================================ */

    public function addFavorite($stadium_id)
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->addFavoriteStadium($userId, $stadium_id)) {
            $_SESSION['success'] = 'Added to favorites.';
        } else {
            $_SESSION['error'] = 'Already in favorites or failed.';
        }

        header('Location: ' . URLROOT . '/customer#stadiums');
        exit;
    }


    public function removeFavorite($favorite_id)
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->removeFavoriteStadium($favorite_id, $userId)) {
            $_SESSION['success'] = 'Removed from favorites.';
        } else {
            $_SESSION['error'] = 'Could not remove.';
        }

        header('Location: ' . URLROOT . '/customer#stadiums');
        exit;
    }


    public function renameFavorite()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/customer#stadiums');
            exit;
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $favorite_id = $_POST['favorite_id'] ?? null;
        $nickname    = trim($_POST['nickname'] ?? '');

        if (!$favorite_id || empty($nickname)) {
            $_SESSION['error'] = 'Please provide a name.';
            header('Location: ' . URLROOT . '/customer#stadiums');
            exit;
        }

        if ($this->customerModel->renameFavoriteStadium($favorite_id, $userId, $nickname)) {
            $_SESSION['success'] = 'Renamed successfully.';
        } else {
            $_SESSION['error'] = 'Could not rename.';
        }

        header('Location: ' . URLROOT . '/customer#stadiums');
        exit;
    }


    /* ============================================
       BOOKING METHODS
    ============================================ */

    public function cancelBooking($id)
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->cancelBooking($id, $userId)) {
            $_SESSION['success'] = 'Booking cancelled.';
        } else {
            $_SESSION['error'] = 'Cannot cancel this booking.';
        }

        header('Location: ' . URLROOT . '/customer#bookings');
        exit;
    }

    public function submitRefundRequest($booking_id)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Get booking to verify ownership and get refund amount
        $booking = $this->customerModel->getBookingById($booking_id, $userId);
        if (!$booking) {
            echo json_encode(['success' => false, 'message' => 'Booking not found']);
            exit;
        }

        // Check if booking is completed or already cancelled
        if ($booking->status === 'completed' || $booking->status === 'cancelled') {
            echo json_encode(['success' => false, 'message' => 'Cannot request refund for this booking status']);
            exit;
        }

        // Check if cancellation is allowed (6 hours before start time)
        $bookingDateTime = new DateTime($booking->booking_date . ' ' . $booking->start_time);
        $currentDateTime = new DateTime();
        
        if ($bookingDateTime <= $currentDateTime) {
            echo json_encode(['success' => false, 'message' => 'Booking time has already passed']);
            exit;
        }

        $interval = $currentDateTime->diff($bookingDateTime);
        $hoursRemaining = ($interval->days * 24) + $interval->h + ($interval->i / 60);

        if ($hoursRemaining < 6) {
            echo json_encode([
                'success' => false, 
                'message' => 'Refund requests are only allowed 6 hours before the booking time. Only ' . round($hoursRemaining, 1) . ' hours remaining.'
            ]);
            exit;
        }

        // Validate refund data
        $account_name = $_POST['account_name'] ?? '';
        $account_number = $_POST['account_number'] ?? '';
        $bank_name = $_POST['bank_name'] ?? '';
        $branch_name = $_POST['branch_name'] ?? '';
        $reason = $_POST['reason'] ?? '';

        if (empty($account_name) || empty($account_number) || empty($bank_name)) {
            echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
            exit;
        }

        // Check if refund request already exists for this booking
        $existing = $this->customerModel->getRefundRequestByBookingId($booking_id);
        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'Refund request already exists for this booking']);
            exit;
        }

        // Prepare refund data
        $refund_data = [
            'account_name' => $account_name,
            'account_number' => $account_number,
            'bank_name' => $bank_name,
            'branch_name' => $branch_name,
            'refund_amount' => $booking->total_price,
            'original_amount' => $booking->total_price,
            'reason' => $reason
        ];

        // Submit refund request
        $result = $this->customerModel->submitRefundRequest($booking_id, $userId, $refund_data);

        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Refund request submitted successfully! You will receive your refund within 24-48 hours.',
                'refund_id' => $result['refund_id']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        exit;
    }

    public function getRefundStatus($booking_id)
    {
        header('Content-Type: application/json');

        $userId = Auth::getUserId();
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $refund = $this->customerModel->getRefundStatusByBooking($booking_id, $userId);

        if ($refund) {
            echo json_encode([
                'success' => true,
                'has_refund' => true,
                'refund' => [
                    'id' => $refund->id,
                    'status' => $refund->status,
                    'amount' => $refund->refund_amount,
                    'bank' => $refund->bank_name,
                    'account_number' => substr($refund->account_number, -4),
                    'created_at' => date('M d, Y H:i', strtotime($refund->created_at)),
                    'updated_at' => $refund->updated_at ? date('M d, Y H:i', strtotime($refund->updated_at)) : null,
                    'admin_notes' => $refund->admin_notes,
                    'payment_slip' => $refund->payment_slip
                ]
            ]);
        } else {
            echo json_encode(['success' => true, 'has_refund' => false]);
        }
        exit;
    }


    /* ============================================
       ADVERTISEMENT METHODS
    ============================================ */

    public function submitAdvertisement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/customer#advertisements');
            exit;
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $profileData = $this->customerModel->getProfileData($userId);

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'contact_name' => trim(($profileData['first_name'] ?? '') . ' ' . ($profileData['last_name'] ?? '')),
            'email'        => $profileData['email'] ?? '',
            'phone'        => $profileData['phone'] ?? '',
            'package'      => $_POST['package'] ?? 'basic',
            'website'      => trim($_POST['website'] ?? ''),
            'message'      => trim($_POST['message'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'Business/Company name is required.';
            header('Location: ' . URLROOT . '/customer#advertisements');
            exit;
        }

        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
            $file     = $_FILES['ad_image'];
            $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize  = 5 * 1024 * 1024;

            if (!in_array($file['type'], $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Use JPG, PNG, GIF or WEBP.';
                header('Location: ' . URLROOT . '/customer#advertisements');
                exit;
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['error'] = 'File too large. Max 5MB allowed.';
                header('Location: ' . URLROOT . '/customer#advertisements');
                exit;
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename  = 'ad_' . $userId . '_' . time() . '.' . $extension;
            $uploadDir = APPROOT . '/../public/images/advertisements/';
            $uploadPath = $uploadDir . $filename;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data['file_path'] = $filename;
            }
        }

        if ($this->customerModel->createAdvertisement($userId, $data)) {
            $_SESSION['success'] = 'Advertisement submitted! We will review and contact you soon.';
        } else {
            $_SESSION['error'] = 'Failed to submit advertisement.';
        }

        header('Location: ' . URLROOT . '/customer#advertisements');
        exit;
    }


    public function deleteAdvertisement($id)
    {
        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->customerModel->deleteAdvertisement($id, $userId)) {
            $_SESSION['success'] = 'Advertisement removed.';
        } else {
            $_SESSION['error'] = 'Failed to remove advertisement.';
        }

        header('Location: ' . URLROOT . '/customer#advertisements');
        exit;
    }


    public function editAdvertisement($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/customer#advertisements');
            exit;
        }

        $userId = Auth::getUserId();
        if (!$userId) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        // Verify ownership
        $existingAd = $this->customerModel->getAdvertisementById($id, $userId);
        if (!$existingAd) {
            $_SESSION['error'] = 'Advertisement not found.';
            header('Location: ' . URLROOT . '/customer#advertisements');
            exit;
        }

        $profileData = $this->customerModel->getProfileData($userId);

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'contact_name' => trim(($profileData['first_name'] ?? '') . ' ' . ($profileData['last_name'] ?? '')),
            'email'        => $profileData['email'] ?? '',
            'phone'        => $profileData['phone'] ?? '',
            'package'      => $existingAd->package, // Keep original package - cannot change
            'website'      => trim($_POST['website'] ?? ''),
            'message'      => trim($_POST['message'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'Business/Company name is required.';
            header('Location: ' . URLROOT . '/customer#advertisements');
            exit;
        }

        // Handle new file upload
        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
            $file     = $_FILES['ad_image'];
            $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize  = 5 * 1024 * 1024;

            if (!in_array($file['type'], $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Use JPG, PNG, GIF or WEBP.';
                header('Location: ' . URLROOT . '/customer#advertisements');
                exit;
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['error'] = 'File too large. Max 5MB allowed.';
                header('Location: ' . URLROOT . '/customer#advertisements');
                exit;
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename  = 'ad_' . $userId . '_' . time() . '.' . $extension;
            $uploadDir = APPROOT . '/../public/images/advertisements/';
            $uploadPath = $uploadDir . $filename;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data['file_path'] = $filename;
            }
        }

        if ($this->customerModel->updateAdvertisement($id, $userId, $data)) {
            $_SESSION['success'] = '✏️ Advertisement updated! It will be reviewed again for approval.';
        } else {
            $_SESSION['error'] = 'Failed to update advertisement.';
        }

        header('Location: ' . URLROOT . '/customer#advertisements');
        exit;
    }


    /* ============================================
       SESSION METHODS
    ============================================ */

    public function logout()
    {
        session_unset();
        session_destroy();
        session_start();
        header('Location: ' . URLROOT . '/login');
        exit;
    }
}
