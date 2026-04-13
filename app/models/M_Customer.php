<?php

class M_Customer
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    /* ============================================
       BOOKING METHODS
    ============================================ */

    public function getCustomerStats($user_id)
    {
        $this->db->query('SELECT COUNT(*) as count 
                          FROM bookings 
                          WHERE customer_id = :uid 
                          AND status IN ("pending", "confirmed")');
        $this->db->bind(':uid', $user_id);
        $result = $this->db->single();
        $activeBookings = $result && isset($result->count) ? $result->count : 0;

        $this->db->query('SELECT COUNT(DISTINCT stadium_id) as count 
                          FROM bookings 
                          WHERE customer_id = :uid 
                          AND status = "completed"');
        $this->db->bind(':uid', $user_id);
        $result = $this->db->single();
        $stadiumsVisited = $result && isset($result->count) ? $result->count : 0;

        $this->db->query('SELECT COALESCE(SUM(total_price), 0) as total 
                          FROM bookings 
                          WHERE customer_id = :uid 
                          AND payment_status = "paid"');
        $this->db->bind(':uid', $user_id);
        $result = $this->db->single();
        $totalSpent = $result && isset($result->total) ? $result->total : 0;

        return [
            'active_bookings'  => $activeBookings,
            'stadiums_visited' => $stadiumsVisited,
            'total_spent'      => $totalSpent,
            'rating_given'     => 0
        ];
    }


    public function getRecentBookings($user_id, $limit = 5)
    {
        $this->db->query('SELECT b.*, s.name as stadium_name, s.location, s.type, s.image 
                          FROM bookings b 
                          JOIN stadiums s ON b.stadium_id = s.id 
                          WHERE b.customer_id = :uid 
                          ORDER BY b.booking_date DESC, b.created_at DESC 
                          LIMIT :limit');
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }


    public function getAllBookings($user_id)
    {
        $this->db->query('SELECT b.*, s.name as stadium_name, s.location, s.type, s.image 
                          FROM bookings b 
                          JOIN stadiums s ON b.stadium_id = s.id 
                          WHERE b.customer_id = :uid 
                          ORDER BY b.booking_date DESC');
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }


    public function getBookingById($bookingId, $user_id)
    {
        $this->db->query('SELECT b.*, s.name as stadium_name, s.location, s.type, s.image 
                          FROM bookings b 
                          JOIN stadiums s ON b.stadium_id = s.id 
                          WHERE b.id = :bid AND b.customer_id = :uid');
        $this->db->bind(':bid', $bookingId);
        $this->db->bind(':uid', $user_id);
        return $this->db->single();
    }


    public function cancelBooking($bookingId, $user_id)
    {
        $this->db->query('UPDATE bookings 
                          SET status = "cancelled" 
                          WHERE id = :bid 
                          AND customer_id = :uid 
                          AND status IN ("pending", "confirmed")');
        $this->db->bind(':bid', $bookingId);
        $this->db->bind(':uid', $user_id);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }


    /* ============================================
       REFUND REQUEST METHODS
    ============================================ */

    public function submitRefundRequest($booking_id, $customer_id, $refund_data)
    {
        // Start transaction
        try {
            $this->db->beginTransaction();

            // Insert refund request
            $this->db->query('INSERT INTO refund_requests
                              (booking_id, customer_id, account_name, account_number, bank_name, 
                               branch_name, refund_amount, original_amount, reason_for_cancellation, 
                               status, created_at)
                              VALUES (:booking_id, :customer_id, :account_name, :account_number, 
                                      :bank_name, :branch_name, :refund_amount, :original_amount, 
                                      :reason, :status, NOW())');

            $this->db->bind(':booking_id', $booking_id);
            $this->db->bind(':customer_id', $customer_id);
            $this->db->bind(':account_name', $refund_data['account_name']);
            $this->db->bind(':account_number', $refund_data['account_number']);
            $this->db->bind(':bank_name', $refund_data['bank_name']);
            $this->db->bind(':branch_name', $refund_data['branch_name'] ?? '');
            $this->db->bind(':refund_amount', $refund_data['refund_amount']);
            $this->db->bind(':original_amount', $refund_data['original_amount'] ?? $refund_data['refund_amount']);
            $this->db->bind(':reason', $refund_data['reason'] ?? '');
            $this->db->bind(':status', 'pending');

            if (!$this->db->execute()) {
                throw new Exception('Failed to create refund request');
            }

            $refund_id = $this->db->lastInsertId();

            // Update booking status to reflect refund request
            $this->db->query('UPDATE bookings 
                              SET status = "cancelled"
                              WHERE id = :bid AND customer_id = :cid');
            $this->db->bind(':bid', $booking_id);
            $this->db->bind(':cid', $customer_id);

            if (!$this->db->execute()) {
                throw new Exception('Failed to update booking');
            }

            $this->db->commit();
            return ['success' => true, 'refund_id' => $refund_id];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getRefundRequestByBookingId($booking_id)
    {
        $this->db->query('SELECT * FROM refund_requests 
                          WHERE booking_id = :bid');
        $this->db->bind(':bid', $booking_id);
        return $this->db->single();
    }

    public function getCustomerRefundRequests($customer_id)
    {
        $this->db->query('SELECT rr.*, b.stadium_id, s.name as stadium_name, b.booking_date, 
                                 b.total_price, u.first_name, u.last_name
                          FROM refund_requests rr
                          JOIN bookings b ON rr.booking_id = b.id
                          JOIN stadiums s ON b.stadium_id = s.id
                          JOIN users u ON rr.customer_id = u.id
                          WHERE rr.customer_id = :cid
                          ORDER BY rr.created_at DESC');
        $this->db->bind(':cid', $customer_id);
        return $this->db->resultSet();
    }

    public function getRefundStatusByBooking($booking_id, $customer_id)
    {
        $this->db->query('SELECT rr.*, b.total_price, b.booking_date
                          FROM refund_requests rr
                          JOIN bookings b ON rr.booking_id = b.id
                          WHERE rr.booking_id = :bid AND rr.customer_id = :cid');
        $this->db->bind(':bid', $booking_id);
        $this->db->bind(':cid', $customer_id);
        return $this->db->single();
    }


    /* ============================================
       FAVORITE STADIUMS METHODS
    ============================================ */

    public function getFavoriteStadiums($user_id)
    {
        $this->db->query('SELECT fs.id, fs.stadium_id, fs.nickname, fs.created_at,
                                 s.name, s.location, s.type, s.rating, s.image, s.category
                          FROM favorite_stadiums fs
                          JOIN stadiums s ON fs.stadium_id = s.id
                          WHERE fs.user_id = :uid AND fs.is_active = 1
                          ORDER BY fs.created_at DESC');
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }


    public function addFavoriteStadium($user_id, $stadium_id)
    {
        $this->db->query('INSERT INTO favorite_stadiums (user_id, stadium_id) 
                          VALUES (:uid, :sid)');
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':sid', $stadium_id);
        return $this->db->execute();
    }


    public function removeFavoriteStadium($favorite_id, $user_id)
    {
        $this->db->query('UPDATE favorite_stadiums 
                          SET is_active = 0 
                          WHERE id = :id AND user_id = :uid');
        $this->db->bind(':id', $favorite_id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }


    public function renameFavoriteStadium($favorite_id, $user_id, $nickname)
    {
        $this->db->query('UPDATE favorite_stadiums 
                          SET nickname = :nickname 
                          WHERE id = :id AND user_id = :uid');
        $this->db->bind(':nickname', $nickname);
        $this->db->bind(':id', $favorite_id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }


    public function isFavoriteStadium($user_id, $stadium_id)
    {
        $this->db->query('SELECT id FROM favorite_stadiums 
                          WHERE user_id = :uid AND stadium_id = :sid AND is_active = 1');
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':sid', $stadium_id);
        return $this->db->single() ? true : false;
    }


    /* ============================================
       PAYMENT METHODS
    ============================================ */

    public function getPaymentHistory($user_id)
    {
        $this->db->query('SELECT * FROM customer_payments 
                          WHERE user_id = :uid AND is_active = 1 
                          ORDER BY payment_date DESC');
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }


    public function getPaymentSummary($user_id)
    {
        $this->db->query('SELECT COUNT(*) as total_transactions,
                                 COALESCE(SUM(amount), 0) as total_amount,
                                 COALESCE(AVG(amount), 0) as avg_amount,
                                 MAX(payment_date) as last_payment_date
                          FROM customer_payments 
                          WHERE user_id = :uid AND status = "completed" AND is_active = 1');
        $this->db->bind(':uid', $user_id);
        $result = $this->db->single();

        if ($result) {
            return [
                'total_transactions' => (int)$result->total_transactions,
                'total_amount'       => (float)$result->total_amount,
                'avg_amount'         => (float)$result->avg_amount,
                'last_payment_date'  => $result->last_payment_date
            ];
        }

        return [
            'total_transactions' => 0,
            'total_amount'       => 0,
            'avg_amount'         => 0,
            'last_payment_date'  => null
        ];
    }


    public function deletePayment($payment_id, $user_id)
    {
        $this->db->query('UPDATE customer_payments 
                          SET is_active = 0 
                          WHERE id = :id AND user_id = :uid');
        $this->db->bind(':id', $payment_id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }


    public function clearAllPayments($user_id)
    {
        $this->db->query('UPDATE customer_payments 
                          SET is_active = 0 
                          WHERE user_id = :uid AND is_active = 1');
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }


    /* ============================================
       PROFILE METHODS
    ============================================ */

    public function getProfileData($user_id)
    {
        $this->db->query('SELECT u.*, cp.district, cp.sports, cp.age_group, cp.skill_level 
                          FROM users u
                          LEFT JOIN customer_profiles cp ON u.id = cp.user_id
                          WHERE u.id = :id');
        $this->db->bind(':id', $user_id);

        $profile = $this->db->single();

        if ($profile) {
            return [
                'first_name'             => $profile->first_name ?? '',
                'last_name'              => $profile->last_name ?? '',
                'email'                  => $profile->email ?? '',
                'phone'                  => $profile->phone ?? '',
                'profile_picture'        => $profile->profile_picture ?? '',
                'profile_picture_active' => $profile->profile_picture_active ?? 1,
                'location'               => $profile->district ?? '',
                'favorite_sports'        => $profile->sports ?? '',
                'age_group'              => $profile->age_group ?? '',
                'skill_level'            => $profile->skill_level ?? '',
                'member_since'           => isset($profile->created_at) ? date('F Y', strtotime($profile->created_at)) : ''
            ];
        }

        return [
            'first_name'             => '',
            'last_name'              => '',
            'email'                  => '',
            'phone'                  => '',
            'profile_picture'        => '',
            'profile_picture_active' => 1,
            'location'               => '',
            'favorite_sports'        => '',
            'age_group'              => '',
            'skill_level'            => '',
            'member_since'           => ''
        ];
    }


    public function updateProfile($user_id, $profile_data)
    {
        $this->db->query('UPDATE users SET
                          first_name = :first_name,
                          last_name  = :last_name,
                          phone      = :phone,
                          updated_at = NOW()
                          WHERE id = :id');
        $this->db->bind(':first_name', $profile_data['first_name'] ?? '');
        $this->db->bind(':last_name', $profile_data['last_name'] ?? '');
        $this->db->bind(':phone', $profile_data['phone'] ?? '');
        $this->db->bind(':id', $user_id);
        $this->db->execute();

        $this->db->query('SELECT id FROM customer_profiles WHERE user_id = :uid');
        $this->db->bind(':uid', $user_id);
        $exists = $this->db->single();

        if ($exists) {
            $this->db->query('UPDATE customer_profiles SET 
                              district    = :district,
                              sports      = :sports,
                              age_group   = :age_group,
                              skill_level = :skill_level,
                              updated_at  = NOW()
                              WHERE user_id = :uid');
        } else {
            $this->db->query('INSERT INTO customer_profiles 
                              (user_id, district, sports, age_group, skill_level, created_at)
                              VALUES (:uid, :district, :sports, :age_group, :skill_level, NOW())');
        }

        $this->db->bind(':uid', $user_id);
        $this->db->bind(':district', $profile_data['district'] ?? '');
        $this->db->bind(':sports', $profile_data['sports'] ?? '');
        $this->db->bind(':age_group', $profile_data['age_group'] ?? '');
        $this->db->bind(':skill_level', $profile_data['skill_level'] ?? '');

        return $this->db->execute();
    }


    public function updateProfilePicture($user_id, $filename)
    {
        $this->db->query('UPDATE users 
                          SET profile_picture = :pic, profile_picture_active = 1, updated_at = NOW() 
                          WHERE id = :id');
        $this->db->bind(':pic', $filename);
        $this->db->bind(':id', $user_id);
        return $this->db->execute();
    }


    public function getProfilePicture($user_id)
    {
        $this->db->query('SELECT profile_picture FROM users WHERE id = :id');
        $this->db->bind(':id', $user_id);
        $result = $this->db->single();
        return $result ? $result->profile_picture : null;
    }


    public function deleteProfilePicture($user_id)
    {
        $this->db->query('UPDATE users 
                          SET profile_picture_active = 0, updated_at = NOW() 
                          WHERE id = :id');
        $this->db->bind(':id', $user_id);
        return $this->db->execute();
    }


    /* ============================================
       EMERGENCY CONTACTS METHODS
    ============================================ */

    public function createEmergencyContact($user_id, $data)
    {
        $this->db->query('INSERT INTO emergency_contacts 
                          (user_id, contact_name, relationship, phone, email, created_at) 
                          VALUES (:user_id, :name, :relationship, :phone, :email, NOW())');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':name', $data['contact_name'] ?? '');
        $this->db->bind(':relationship', $data['relationship'] ?? '');
        $this->db->bind(':phone', $data['phone'] ?? '');
        $this->db->bind(':email', $data['email'] ?? null);
        return $this->db->execute();
    }


    public function getEmergencyContacts($user_id)
    {
        $this->db->query('SELECT * FROM emergency_contacts 
                          WHERE user_id = :uid AND is_active = 1 
                          ORDER BY created_at DESC');
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }


    public function deleteEmergencyContact($id, $user_id)
    {
        $this->db->query('UPDATE emergency_contacts 
                          SET is_active = 0 
                          WHERE id = :id AND user_id = :uid');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }


    /* ============================================
       ADVERTISEMENT METHODS
    ============================================ */

    public function getCustomerAdvertisements($user_id)
    {
        $this->db->query('SELECT * FROM advertisement_requests 
                          WHERE user_id = :uid AND is_active = 1 
                          ORDER BY submitted_at DESC');
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }


    public function createAdvertisement($user_id, $data)
    {
        $this->db->query('INSERT INTO advertisement_requests 
                          (user_id, company_name, contact_name, email, phone, package, website, message, file_path, status, submitted_at) 
                          VALUES (:user_id, :company_name, :contact_name, :email, :phone, :package, :website, :message, :file_path, :status, :submitted_at)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':contact_name', $data['contact_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':package', $data['package']);
        $this->db->bind(':website', $data['website'] ?? null);
        $this->db->bind(':message', $data['message'] ?? null);
        $this->db->bind(':file_path', $data['file_path'] ?? null);
        $this->db->bind(':status', 'pending');
        $this->db->bind(':submitted_at', date('Y-m-d H:i:s'));
        return $this->db->execute();
    }


    public function deleteAdvertisement($id, $user_id)
    {
        $this->db->query('UPDATE advertisement_requests 
                          SET is_active = 0 
                          WHERE id = :id AND user_id = :uid');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }


    public function getAdvertisementPackages()
    {
        return [
            'basic' => [
                'name'     => 'Basic',
                'price'    => 5000,
                'duration' => '7 days'
            ],
            'professional' => [
                'name'     => 'Professional',
                'price'    => 12000,
                'duration' => '30 days'
            ],
            'premium' => [
                'name'     => 'Premium',
                'price'    => 30000,
                'duration' => '90 days'
            ]
        ];
    }


    public function getAdvertisementById($id, $user_id)
    {
        $this->db->query('SELECT * FROM advertisement_requests 
                          WHERE id = :id AND user_id = :uid AND is_active = 1');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);
        return $this->db->single();
    }


    public function updateAdvertisement($id, $user_id, $data)
    {
        // Update ad and reset status to pending for re-approval
        $sql = 'UPDATE advertisement_requests 
                SET company_name = :company_name, 
                    contact_name = :contact_name, 
                    email = :email, 
                    phone = :phone, 
                    package = :package, 
                    website = :website, 
                    message = :message, 
                    status = :status,
                    submitted_at = :submitted_at';

        // Only update file_path if a new file was uploaded
        if (!empty($data['file_path'])) {
            $sql .= ', file_path = :file_path';
        }

        $sql .= ' WHERE id = :id AND user_id = :uid';

        $this->db->query($sql);
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':contact_name', $data['contact_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':package', $data['package']);
        $this->db->bind(':website', $data['website'] ?? null);
        $this->db->bind(':message', $data['message'] ?? null);
        $this->db->bind(':status', 'pending'); // Reset to pending for re-approval
        $this->db->bind(':submitted_at', date('Y-m-d H:i:s'));
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);

        if (!empty($data['file_path'])) {
            $this->db->bind(':file_path', $data['file_path']);
        }

        return $this->db->execute();
    }
}
