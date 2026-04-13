<?php
class M_Stadium_owner
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new Database();
        } catch (Exception $e) {
            error_log('Database connection error in M_Stadium_owner: ' . $e->getMessage());
        }
    }

    // Get stadium owner dashboard stats
    public function getOwnerStats($owner_id)
    {
        try {
            if (!$this->db) {
                return $this->getDefaultStats();
            }

            // Get actual stats from database
            $stats = [];

            // Total stadiums count
            $this->db->query('SELECT COUNT(*) as total FROM stadiums WHERE owner_id = :owner_id');
            $this->db->bind(':owner_id', $owner_id);
            $result = $this->db->single();
            $stats['total_properties'] = $result ? $result->total : 0;

            // Get stadiums by status if status column exists
            try {
                $this->db->query('SELECT COUNT(*) as count FROM stadiums WHERE owner_id = :owner_id AND approval_status = "approved"');
                $this->db->bind(':owner_id', $owner_id);
                $approvedResult = $this->db->single();
                $stats['approved_stadiums'] = $approvedResult ? $approvedResult->count : 0;
            } catch (Exception $e) {
                $stats['approved_stadiums'] = $stats['total_properties'];
            }

            // Get actual bookings data
            $this->db->query('SELECT COUNT(*) as total FROM bookings WHERE owner_id = :owner_id AND status IN ("confirmed", "pending")');
            $this->db->bind(':owner_id', $owner_id);
            $bookingsResult = $this->db->single();
            $stats['active_bookings'] = $bookingsResult ? $bookingsResult->total : 0;

            // Get monthly revenue
            $this->db->query('SELECT COALESCE(SUM(total_price), 0) as revenue FROM bookings WHERE owner_id = :owner_id AND MONTH(booking_date) = MONTH(CURRENT_DATE()) AND YEAR(booking_date) = YEAR(CURRENT_DATE()) AND payment_status = "paid"');
            $this->db->bind(':owner_id', $owner_id);
            $revenueResult = $this->db->single();
            $stats['monthly_revenue'] = $revenueResult ? $revenueResult->revenue : 0;

            // Get unique customers
            $this->db->query('SELECT COUNT(DISTINCT customer_id) as total FROM bookings WHERE owner_id = :owner_id');
            $this->db->bind(':owner_id', $owner_id);
            $customersResult = $this->db->single();
            $stats['total_customers'] = $customersResult ? $customersResult->total : 0;

            $stats['occupancy_rate'] = 0;
            $stats['average_rating'] = 0;

            return $stats;
        } catch (Exception $e) {
            error_log('Error in getOwnerStats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    private function getDefaultStats()
    {
        return [
            'total_properties' => 0,
            'active_bookings' => 0,
            'monthly_revenue' => 0,
            'total_customers' => 0,
            'occupancy_rate' => 0,
            'average_rating' => 0.0
        ];
    }

    // Get recent bookings for owner
    public function getRecentBookings($owner_id, $limit = 5)
    {
        try {
            if (!$this->db) {
                return [];
            }

            $this->db->query('SELECT b.*, 
                u.first_name, u.last_name, u.email, u.phone,
                s.name as stadium_name
                FROM bookings b
                JOIN users u ON b.customer_id = u.id
                JOIN stadiums s ON b.stadium_id = s.id
                WHERE b.owner_id = :owner_id
                ORDER BY b.booking_date DESC, b.start_time DESC
                LIMIT :limit');

            $this->db->bind(':owner_id', $owner_id);
            $this->db->bind(':limit', $limit);

            $bookings = $this->db->resultSet();

            $formatted = [];
            foreach ($bookings as $booking) {
                $formatted[] = [
                    'id' => $booking->id,
                    'customer' => $booking->first_name . ' ' . $booking->last_name,
                    'property' => $booking->stadium_name,
                    'date' => date('M d, Y', strtotime($booking->booking_date)),
                    'time' => date('g:i A', strtotime($booking->start_time)) . ' - ' . date('g:i A', strtotime($booking->end_time)),
                    'amount' => $booking->total_price,
                    'status' => ucfirst($booking->status),
                    'payment_status' => ucfirst($booking->payment_status)
                ];
            }

            return $formatted;
        } catch (Exception $e) {
            error_log('Error in getRecentBookings: ' . $e->getMessage());
            return [];
        }
    }

    // Get upcoming schedules
    public function getUpcomingSchedules($owner_id)
    {
        try {
            return [
                [
                    'property' => 'Colombo Cricket Ground',
                    'customer' => 'Krishna Wishvajith',
                    'date' => '25',
                    'month' => 'JAN',
                    'time' => '2:00 PM - 4:00 PM',
                    'status' => 'Confirmed'
                ],
                [
                    'property' => 'Football Arena Pro',
                    'customer' => 'Team Phoenix',
                    'date' => '26',
                    'month' => 'JAN',
                    'time' => '6:00 PM - 8:00 PM',
                    'status' => 'Pending'
                ]
            ];
        } catch (Exception $e) {
            error_log('Error in getUpcomingSchedules: ' . $e->getMessage());
            return [];
        }
    }

    // Get revenue overview
    public function getRevenueOverview($owner_id)
    {
        try {
            if (!$this->db) {
                return [
                    'this_month' => 0,
                    'last_month' => 0,
                    'growth_percentage' => 0,
                    'pending_payouts' => 0,
                    'next_payout_date' => date('Y-m-d', strtotime('+1 month'))
                ];
            }

            // This month revenue
            $this->db->query('SELECT COALESCE(SUM(total_price), 0) as revenue FROM bookings WHERE owner_id = :owner_id AND MONTH(booking_date) = MONTH(CURRENT_DATE()) AND YEAR(booking_date) = YEAR(CURRENT_DATE()) AND payment_status = "paid"');
            $this->db->bind(':owner_id', $owner_id);
            $thisMonthResult = $this->db->single();
            $thisMonth = $thisMonthResult ? $thisMonthResult->revenue : 0;

            // Last month revenue
            $this->db->query('SELECT COALESCE(SUM(total_price), 0) as revenue FROM bookings WHERE owner_id = :owner_id AND MONTH(booking_date) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(booking_date) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND payment_status = "paid"');
            $this->db->bind(':owner_id', $owner_id);
            $lastMonthResult = $this->db->single();
            $lastMonth = $lastMonthResult ? $lastMonthResult->revenue : 0;

            // Calculate growth percentage
            $growth = ($lastMonth > 0) ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

            // Pending payouts
            $this->db->query('SELECT COALESCE(SUM(total_price), 0) as revenue FROM bookings WHERE owner_id = :owner_id AND payment_status IN ("pending", "partial")');
            $this->db->bind(':owner_id', $owner_id);
            $pendingResult = $this->db->single();
            $pending = $pendingResult ? $pendingResult->revenue : 0;

            return [
                'this_month' => round($thisMonth, 2),
                'last_month' => round($lastMonth, 2),
                'growth_percentage' => round($growth, 1),
                'pending_payouts' => round($pending, 2),
                'next_payout_date' => date('Y-m-d', strtotime('+3 days'))
            ];
        } catch (Exception $e) {
            error_log('Error in getRevenueOverview: ' . $e->getMessage());
            return [
                'this_month' => 0,
                'last_month' => 0,
                'growth_percentage' => 0,
                'pending_payouts' => 0,
                'next_payout_date' => date('Y-m-d')
            ];
        }
    }

    // Get property summary
    public function getPropertySummary($owner_id)
    {
        try {
            return [
                'total_properties' => 3,
                'active_properties' => 3,
                'under_maintenance' => 0,
                'package_type' => 'Standard',
                'properties_limit' => 6,
                'can_add_more' => true
            ];
        } catch (Exception $e) {
            error_log('Error in getPropertySummary: ' . $e->getMessage());
            return [];
        }
    }

    // Get package information
    // Get all properties for owner
    public function getAllProperties($owner_id)
    {
        try {
            if (!$this->db) {
                throw new Exception('Database connection not established');
            }

            $this->db->query("SELECT 
                s.id,
                s.name,
                s.type,
                s.category,
                s.price,
                s.location,
                s.district,
                s.description,
                s.status,
                s.image,
                s.approval_status,
                s.rejection_reason,
                s.created_at
            FROM stadiums s
            ORDER BY s.created_at DESC");

            $results = $this->db->resultSet();

            // Convert objects to arrays
            $properties = [];
            foreach ($results as $row) {
                // Get all images for this stadium
                $images = $this->getStadiumImages($row->id);

                $properties[] = [
                    'id' => $row->id,
                    'name' => $row->name,
                    'type' => $row->type,
                    'category' => $row->category,
                    'price' => $row->price,
                    'location' => $row->location,
                    'district' => $row->district,
                    'description' => $row->description,
                    'status' => $row->status,
                    'image' => $row->image,
                    'images' => $images, // All images from stadium_images table
                    'image_count' => count($images),
                    'approval_status' => $row->approval_status ?? 'pending',
                    'rejection_reason' => $row->rejection_reason ?? null,
                    'created_at' => $row->created_at,
                    'rating' => 0, // Default value
                    'total_bookings' => 0, // Default value
                    'monthly_revenue' => 0 // Default value
                ];
            }

            return $properties;
        } catch (Exception $e) {
            error_log('Error in getAllProperties: ' . $e->getMessage());
            return [];
        }
    }

    // Get package limits
    // Get all bookings for owner
    public function getAllBookings($owner_id)
    {
        try {
            if (!$this->db) {
                return [];
            }

            $this->db->query('SELECT b.*, 
                u.first_name, u.last_name, u.email, u.phone,
                s.name as stadium_name
                FROM bookings b
                JOIN users u ON b.customer_id = u.id
                JOIN stadiums s ON b.stadium_id = s.id
                WHERE b.owner_id = :owner_id
                ORDER BY b.booking_date DESC, b.start_time DESC');

            $this->db->bind(':owner_id', $owner_id);
            $bookings = $this->db->resultSet();

            $formatted = [];
            foreach ($bookings as $booking) {
                $formatted[] = [
                    'id' => $booking->id,
                    'customer' => $booking->first_name . ' ' . $booking->last_name,
                    'email' => $booking->email,
                    'phone' => $booking->phone,
                    'property' => $booking->stadium_name,
                    'date' => date('M d, Y', strtotime($booking->booking_date)),
                    'time' => date('g:i A', strtotime($booking->start_time)) . ' - ' . date('g:i A', strtotime($booking->end_time)),
                    'duration' => $booking->duration_hours . ' hours',
                    'amount' => $booking->total_price,
                    'commission' => round($booking->total_price * 0.12, 2), // 12% commission
                    'net_amount' => round($booking->total_price * 0.88, 2),
                    'status' => ucfirst($booking->status),
                    'payment_status' => ucfirst($booking->payment_status)
                ];
            }

            return $formatted;
        } catch (Exception $e) {
            error_log('Error in getAllBookings: ' . $e->getMessage());
            return [];
        }
    }

    // Get booking stats
    public function getBookingStats($owner_id)
    {
        try {
            if (!$this->db) {
                return [
                    'confirmed' => 0,
                    'pending' => 0,
                    'today' => 0,
                    'revenue' => 0
                ];
            }

            // Get confirmed bookings count
            $this->db->query('SELECT COUNT(*) as total FROM bookings WHERE owner_id = :owner_id AND status = "confirmed"');
            $this->db->bind(':owner_id', $owner_id);
            $confirmedResult = $this->db->single();
            $confirmed = $confirmedResult ? $confirmedResult->total : 0;

            // Get pending bookings count
            $this->db->query('SELECT COUNT(*) as total FROM bookings WHERE owner_id = :owner_id AND status = "pending"');
            $this->db->bind(':owner_id', $owner_id);
            $pendingResult = $this->db->single();
            $pending = $pendingResult ? $pendingResult->total : 0;

            // Get today's bookings
            $this->db->query('SELECT COUNT(*) as total FROM bookings WHERE owner_id = :owner_id AND DATE(booking_date) = CURDATE()');
            $this->db->bind(':owner_id', $owner_id);
            $todayResult = $this->db->single();
            $today = $todayResult ? $todayResult->total : 0;

            // Get total revenue (paid bookings only)
            $this->db->query('SELECT COALESCE(SUM(total_price), 0) as revenue FROM bookings WHERE owner_id = :owner_id AND payment_status = "paid"');
            $this->db->bind(':owner_id', $owner_id);
            $revenueResult = $this->db->single();
            $revenue = $revenueResult ? $revenueResult->revenue : 0;

            return [
                'confirmed' => $confirmed,
                'pending' => $pending,
                'today' => $today,
                'revenue' => round($revenue, 2)
            ];
        } catch (Exception $e) {
            error_log('Error in getBookingStats: ' . $e->getMessage());
            return [
                'confirmed' => 0,
                'pending' => 0,
                'today' => 0,
                'revenue' => 0
            ];
        }
    }

    // Get messages for owner
    public function getMessages($owner_id)
    {
        try {
            $this->db->query('SELECT m.*, 
                    u.first_name as sender_first_name, 
                    u.last_name as sender_last_name, 
                    u.email as sender_email, 
                    s.name as stadium_name 
                FROM messages m 
                LEFT JOIN users u ON m.sender_id = u.id 
                LEFT JOIN stadiums s ON m.stadium_id = s.id 
                WHERE m.receiver_id = :owner_id 
                ORDER BY m.created_at DESC');
            $this->db->bind(':owner_id', $owner_id);

            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getMessages: ' . $e->getMessage());
            return [];
        }
    }

    // Get unread message count
    public function getUnreadMessageCount($owner_id)
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM messages WHERE receiver_id = :owner_id AND is_read = FALSE');
            $this->db->bind(':owner_id', $owner_id);
            $result = $this->db->single();
            return $result ? (int)$result->count : 0;
        } catch (Exception $e) {
            error_log('Error in getUnreadMessageCount: ' . $e->getMessage());
            return 0;
        }
    }

    // Send reply to message
    public function sendReply($owner_id, $messageData)
    {
        try {
            $message_id = isset($messageData['message_id']) ? (int)$messageData['message_id'] : 0;
            $other_user_id = isset($messageData['other_user_id']) ? (int)$messageData['other_user_id'] : 0;
            $stadium_id = isset($messageData['stadium_id']) && $messageData['stadium_id'] !== '' ? (int)$messageData['stadium_id'] : null;
            $reply_content = trim($messageData['reply_content'] ?? '');

            if (empty($reply_content) || (!$message_id && !$other_user_id)) {
                return false;
            }

            $this->db->query('SELECT sender_id, subject, stadium_id FROM messages WHERE id = :message_id AND receiver_id = :owner_id LIMIT 1');
            $this->db->bind(':message_id', $message_id);
            $this->db->bind(':owner_id', $owner_id);
            $original = $this->db->single();

            if (!$original && $other_user_id) {
                $sql = 'SELECT sender_id, subject, stadium_id FROM messages WHERE receiver_id = :owner_id AND sender_id = :other_user_id';
                if ($stadium_id !== null) {
                    $sql .= ' AND stadium_id = :stadium_id';
                }
                $sql .= ' ORDER BY created_at DESC LIMIT 1';

                $this->db->query($sql);
                $this->db->bind(':owner_id', $owner_id);
                $this->db->bind(':other_user_id', $other_user_id);
                if ($stadium_id !== null) {
                    $this->db->bind(':stadium_id', $stadium_id);
                }
                $original = $this->db->single();
            }

            if (!$original) {
                return false;
            }

            $replySubject = 'Re: ' . $original->subject;
            $this->db->query('INSERT INTO messages (sender_id, receiver_id, stadium_id, subject, message, is_read, created_at) VALUES (:sender_id, :receiver_id, :stadium_id, :subject, :message, FALSE, NOW())');
            $this->db->bind(':sender_id', $owner_id);
            $this->db->bind(':receiver_id', $original->sender_id);
            $this->db->bind(':stadium_id', $original->stadium_id);
            $this->db->bind(':subject', $replySubject);
            $this->db->bind(':message', $reply_content);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in sendReply: ' . $e->getMessage());
            return false;
        }
    }

    // Get revenue data
    public function getRevenueData($owner_id)
    {
        try {
            return [
                'total_revenue' => 278000,
                'this_month' => 45000,
                'pending_payout' => 12000,
                'monthly_data' => [
                    'January' => 45000,
                    'February' => 38000,
                    'March' => 42000,
                    'April' => 50000,
                    'May' => 55000,
                    'June' => 48000
                ]
            ];
        } catch (Exception $e) {
            error_log('Error in getRevenueData: ' . $e->getMessage());
            return [];
        }
    }

    // Get analytics data
    public function getAnalytics($owner_id)
    {
        try {
            return [
                'property_performance' => [
                    ['name' => 'Football Arena Pro', 'bookings' => 32, 'revenue' => 19000],
                    ['name' => 'Colombo Cricket Ground', 'bookings' => 45, 'revenue' => 18000],
                    ['name' => 'Tennis Academy Courts', 'bookings' => 28, 'revenue' => 8000]
                ]
            ];
        } catch (Exception $e) {
            error_log('Error in getAnalytics: ' . $e->getMessage());
            return [];
        }
    }

    // Add new property
    public function addProperty($owner_id, $data)
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            // Get primary image (first uploaded image or default)
            $primaryImage = isset($data['primary_image']) ? $data['primary_image'] : 'default-stadium.jpg';

            // Prepare the query using Database class method
            $this->db->query("INSERT INTO stadiums
                         (owner_id, name, type, category, price, location, latitude, longitude, owner,
                          district, postal_code, description, opening_hours,
                          advance_booking, minimum_duration, cancellation_policy,
                          contact_person, contact_phone, contact_email,
                          whatsapp_number, special_instructions, image, status, approval_status)
                         VALUES
                         (:owner_id, :name, :type, :category, :price, :location, :latitude, :longitude, :owner,
                          :district, :postal_code, :description, :opening_hours,
                          :advance_booking, :minimum_duration, :cancellation_policy,
                          :contact_person, :contact_phone, :contact_email,
                          :whatsapp_number, :special_instructions, :image, 'Available', 'pending')");

            // Bind all parameters using Database class bind method
            $this->db->bind(':owner_id', $owner_id);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':category', $data['category']);
            $this->db->bind(':price', $data['price']);
            $this->db->bind(':location', $data['location']);
            $this->db->bind(':latitude', !empty($data['latitude']) ? $data['latitude'] : null);
            $this->db->bind(':longitude', !empty($data['longitude']) ? $data['longitude'] : null);
            $this->db->bind(':owner', $data['contact_person'] ?? 'Owner'); // Keep for legacy compatibility
            $this->db->bind(':district', $data['district']);
            $this->db->bind(':postal_code', $data['postal_code'] ?? '');
            $this->db->bind(':description', $data['description'] ?? '');
            $this->db->bind(':opening_hours', $data['opening_hours'] ?? '24/7');
            $this->db->bind(':advance_booking', $data['advance_booking'] ?? '1');
            $this->db->bind(':minimum_duration', $data['minimum_duration'] ?? '1');
            $this->db->bind(':cancellation_policy', $data['cancellation_policy'] ?? 'Flexible');
            $this->db->bind(':contact_person', $data['contact_person'] ?? '');
            $this->db->bind(':contact_phone', $data['contact_phone'] ?? '');
            $this->db->bind(':contact_email', $data['contact_email'] ?? '');
            $this->db->bind(':whatsapp_number', $data['whatsapp_number'] ?? '');
            $this->db->bind(':special_instructions', $data['special_instructions'] ?? '');
            $this->db->bind(':image', $primaryImage);

            // Execute the query
            if ($this->db->execute()) {
                $stadium_id = $this->db->lastInsertId();

                // Add features if provided
                if (!empty($data['features']) && is_array($data['features'])) {
                    $this->addFeatures($stadium_id, $data['features']);
                }

                // Add images if provided
                if (!empty($data['uploaded_images']) && is_array($data['uploaded_images'])) {
                    $this->addStadiumImages($stadium_id, $data['uploaded_images']);
                }

                return array(
                    'success' => true,
                    'stadium_id' => $stadium_id,
                    'message' => 'Stadium added successfully'
                );
            } else {
                throw new Exception("Failed to insert stadium data");
            }
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add stadium'
            );
        }
    }


    // Update property
    public function updateProperty($owner_id, $property_id, $data)
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            // First verify that this stadium belongs to this owner
            $this->db->query("SELECT id FROM stadiums WHERE id = :id AND owner = :owner_id");
            $this->db->bind(':id', $property_id);
            $this->db->bind(':owner_id', $owner_id);

            if (!$this->db->single()) {
                throw new Exception("Stadium not found or unauthorized");
            }

            // Update the stadium information
            $this->db->query("UPDATE stadiums SET 
                      name = :name,
                      type = :type,
                      category = :category,
                      price = :price,
                      location = :location,
                      district = :district,
                      postal_code = :postal_code,
                      description = :description,
                      opening_hours = :opening_hours,
                      advance_booking = :advance_booking,
                      minimum_duration = :minimum_duration,
                      cancellation_policy = :cancellation_policy,
                      contact_person = :contact_person,
                      contact_phone = :contact_phone,
                      contact_email = :contact_email,
                      whatsapp_number = :whatsapp_number,
                      special_instructions = :special_instructions,
                      status = :status
                      WHERE id = :id");

            // Bind all parameters
            $this->db->bind(':id', $property_id);
            // $this->db->bind(':owner_id', $owner_id);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':category', $data['category']);
            $this->db->bind(':price', $data['price']);
            $this->db->bind(':location', $data['location']);
            $this->db->bind(':district', $data['district']);
            $this->db->bind(':postal_code', $data['postal_code']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':opening_hours', $data['opening_hours']);
            $this->db->bind(':advance_booking', $data['advance_booking']);
            $this->db->bind(':minimum_duration', $data['minimum_duration']);
            $this->db->bind(':cancellation_policy', $data['cancellation_policy']);
            $this->db->bind(':contact_person', $data['contact_person']);
            $this->db->bind(':contact_phone', $data['contact_phone']);
            $this->db->bind(':contact_email', $data['contact_email']);
            $this->db->bind(':whatsapp_number', $data['whatsapp_number']);
            $this->db->bind(':special_instructions', $data['special_instructions']);
            $this->db->bind(':status', $data['status']);

            if ($this->db->execute()) {
                // Update features if provided
                if (isset($data['features']) && is_array($data['features'])) {
                    // First delete existing features
                    $this->db->query("DELETE FROM stadium_features WHERE stadium_id = :stadium_id");
                    $this->db->bind(':stadium_id', $property_id);
                    $this->db->execute();

                    // Then add new features
                    $this->addFeatures($property_id, $data['features']);
                    echo "features updated<br>";
                }

                return array(
                    'success' => true,
                    'message' => 'Stadium updated successfully'
                );
            } else {
                throw new Exception("Failed to update stadium data");
            }
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update stadium'
            );
        }
    }

    // Get single property
    public function getProperty($owner_id, $property_id)
    {
        try {
            if (!$this->db) {
                throw new Exception('Database connection not established');
            }

            // First verify that this stadium belongs to this owner
            $this->db->query("SELECT 
                s.*,
                COALESCE((SELECT COUNT(*) FROM bookings b WHERE b.stadium_id = s.id), 0) as total_bookings,
                COALESCE((SELECT SUM(amount) FROM bookings b WHERE b.stadium_id = s.id AND MONTH(booking_date) = MONTH(CURRENT_DATE())), 0) as monthly_revenue,
                COALESCE((SELECT AVG(rating) FROM reviews r WHERE r.stadium_id = s.id), 0) as rating
            FROM stadiums s 
            WHERE s.id = :id");

            $this->db->bind(':id', $property_id);
            $this->db->bind(':owner_id', $owner_id);

            $property = $this->db->single();

            if (!$property) {
                throw new Exception('Property not found or unauthorized access');
            }

            // Get features for this stadium
            $this->db->query("SELECT feature_name FROM stadium_features WHERE stadium_id = :stadium_id");
            $this->db->bind(':stadium_id', $property_id);
            $features = $this->db->resultSet();

            // Get images for this stadium
            $this->db->query("SELECT image_path FROM stadium_images WHERE stadium_id = :stadium_id");
            $this->db->bind(':stadium_id', $property_id);
            $images = $this->db->resultSet();

            // Convert to array format
            return [
                'id' => $property->id,
                'name' => $property->name,
                'type' => $property->type,
                'category' => $property->category,
                'price' => $property->price,
                'location' => $property->location,
                'district' => $property->district,
                'postal_code' => $property->postal_code,
                'description' => $property->description,
                'opening_hours' => $property->opening_hours,
                'advance_booking' => $property->advance_booking,
                'minimum_duration' => $property->minimum_duration,
                'cancellation_policy' => $property->cancellation_policy,
                'contact_person' => $property->contact_person,
                'contact_phone' => $property->contact_phone,
                'contact_email' => $property->contact_email,
                'whatsapp_number' => $property->whatsapp_number,
                'special_instructions' => $property->special_instructions,
                'status' => $property->status,
                'total_bookings' => $property->total_bookings,
                'monthly_revenue' => $property->monthly_revenue,
                'rating' => number_format($property->rating, 1),
                'features' => array_map(function ($f) {
                    return $f->feature_name;
                }, $features),
                'images' => array_map(function ($i) {
                    return $i->image_path;
                }, $images),
                'created_at' => $property->created_at,
                'updated_at' => $property->updated_at
            ];
        } catch (Exception $e) {
            error_log('Error in getProperty: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            error_log('Property ID: ' . $property_id . ', Owner ID: ' . $owner_id);
            return [];
        }
    }

    // Get profile data
    public function getProfileData($owner_id)
    {
        try {
            if (!$this->db) {
                return $this->getDefaultProfileData();
            }

            $this->db->query('SELECT u.*, sop.* FROM users u
                LEFT JOIN stadium_owner_profiles sop ON u.id = sop.user_id
                WHERE u.id = :id AND u.role = "stadium_owner"');
            $this->db->bind(':id', $owner_id);

            $profile = $this->db->single();

            if ($profile) {
                return [
                    'owner_name' => $profile->owner_name ?? 'Stadium Owner',
                    'business_name' => $profile->business_name ?? 'Sports Complex',
                    'email' => $profile->email ?? 'owner@example.com',
                    'phone' => $profile->phone ?? 'Not set',
                    'address' => $profile->address ?? 'Not set',
                    'business_registration' => $profile->business_registration ?? 'Not set',
                    'website' => $profile->website ?? '',
                    'package_type' => 'Standard', // This would come from a separate package tracking
                    'total_properties' => 3,
                    'total_revenue' => 278000,
                    'rating' => '4.6',
                    'member_since' => isset($profile->created_at) ? date('F Y', strtotime($profile->created_at)) : 'January 2024'
                ];
            }

            return $this->getDefaultProfileData();
        } catch (Exception $e) {
            error_log('Error in getProfileData: ' . $e->getMessage());
            return $this->getDefaultProfileData();
        }
    }

    private function getDefaultProfileData()
    {
        return [
            'owner_name' => 'Stadium Owner',
            'business_name' => 'Sports Complex',
            'email' => 'owner@example.com',
            'phone' => 'Not set',
            'address' => 'Not set',
            'business_registration' => 'Not set',
            'website' => '',
            'package_type' => 'Standard',
            'total_properties' => 0,
            'total_revenue' => 0,
            'rating' => '0.0',
            'member_since' => 'January 2025'
        ];
    }

    // Update profile
    public function updateProfile($owner_id, $profileData)
    {
        try {
            if (!$this->db) {
                return false;
            }

            // Update main user data
            $this->db->query('UPDATE users SET
                phone = :phone,
                updated_at = NOW()
                WHERE id = :id');

            $this->db->bind(':phone', $profileData['phone']);
            $this->db->bind(':id', $owner_id);

            $result1 = $this->db->execute();

            // Update stadium owner profile data
            $this->db->query('UPDATE stadium_owner_profiles SET
                owner_name = :owner_name,
                business_name = :business_name,
                updated_at = NOW()
                WHERE user_id = :id');

            $this->db->bind(':owner_name', $profileData['owner_name']);
            $this->db->bind(':business_name', $profileData['business_name']);
            $this->db->bind(':id', $owner_id);

            $result2 = $this->db->execute();

            return $result1 && $result2;
        } catch (Exception $e) {
            error_log('Error in updateProfile: ' . $e->getMessage());
            return false;
        }
    }

    private function addFeatures($stadium_id, $features)
    {
        try {
            foreach ($features as $feature) {
                $this->db->query("INSERT INTO stadium_features (stadium_id, feature_name) VALUES (:stadium_id, :feature_name)");
                $this->db->bind(':stadium_id', $stadium_id);
                $this->db->bind(':feature_name', $feature);
                $this->db->execute();
            }
            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to add stadium features');
        }
    }

    // Delete property
    public function deleteProperty($owner_id, $property_id)
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            // First verify that this stadium belongs to this owner
            $this->db->query("SELECT id FROM stadiums WHERE id = :id ");
            $this->db->bind(':id', $property_id);

            if (!$this->db->single()) {
                throw new Exception("Stadium not found or unauthorized");
            }

            // Delete related records first (foreign key constraints)
            // Delete features
            $this->db->query("DELETE FROM stadium_features WHERE stadium_id = :stadium_id");
            $this->db->bind(':stadium_id', $property_id);
            $this->db->execute();

            // Delete images
            $this->db->query("DELETE FROM stadium_images WHERE stadium_id = :stadium_id");
            $this->db->bind(':stadium_id', $property_id);
            $this->db->execute();

            // Finally delete the stadium
            $this->db->query("DELETE FROM stadiums WHERE id = :id ");
            $this->db->bind(':id', $property_id);

            if ($this->db->execute()) {
                return array(
                    'success' => true,
                    'message' => 'Stadium deleted successfully'
                );
            } else {
                throw new Exception("Failed to delete stadium");
            }
        } catch (Exception $e) {
            error_log('Error in deleteProperty: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return array(
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete stadium'
            );
        }
    }

    /* ============================================
       ADVERTISEMENT METHODS
    ============================================ */

    public function getAdvertisements($user_id)
    {
        try {
            $this->db->query('SELECT * FROM advertisement_requests 
                              WHERE user_id = :uid AND is_active = 1 
                              ORDER BY submitted_at DESC');
            $this->db->bind(':uid', $user_id);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getAdvertisements: ' . $e->getMessage());
            return [];
        }
    }

    // ==================== BOOKING SYSTEM METHODS ====================

    // Create a new booking
    public function createBooking($bookingData)
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            error_log('Creating booking with data: ' . json_encode($bookingData));

            // Try simpler query first to debug which fields exist
            // Build dynamic query based on available data
            $fields = [];
            $placeholders = [];
            $bindings = [];

            foreach ($bookingData as $key => $value) {
                $fields[] = $key;
                $placeholders[] = ':' . $key;
                $bindings[$key] = $value;
            }

            $fieldString = implode(', ', $fields);
            $placeholderString = implode(', ', $placeholders);
            
            $query = "INSERT INTO bookings ($fieldString) VALUES ($placeholderString)";
            error_log("Executing query: $query");
            error_log("With bindings: " . json_encode($bindings));

            $this->db->query($query);

            foreach ($bindings as $key => $value) {
                $this->db->bind(':' . $key, $value);
            }

            if ($this->db->execute()) {
                $bookingId = $this->db->lastInsertId();
                error_log('Booking created successfully with ID: ' . $bookingId);
                return [
                    'success' => true,
                    'booking_id' => $bookingId,
                    'message' => 'Booking created successfully'
                ];
            } else {
                $errorMsg = 'Database execute failed. Check database error logs.';
                error_log('Booking creation execute failed: ' . $errorMsg);
                throw new Exception($errorMsg);
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            error_log('Error in createBooking: ' . $errorMsg);
            error_log('Stack trace: ' . $e->getTraceAsString());
            return [
                'success' => false,
                'error' => $errorMsg,
                'message' => 'Booking creation failed: ' . $errorMsg
            ];
        }
    }

    // Get booking details
    public function getBookingDetails($booking_id)
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            $this->db->query("SELECT b.*, 
                u.first_name, u.last_name, u.email, u.phone,
                s.name as stadium_name, s.location, s.price as stadium_price
                FROM bookings b
                JOIN users u ON b.customer_id = u.id
                JOIN stadiums s ON b.stadium_id = s.id
                WHERE b.id = :id");

            $this->db->bind(':id', $booking_id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Error in getBookingDetails: ' . $e->getMessage());
            return null;
        }
    }

    // Update booking status
    public function updateBookingStatus($booking_id, $status, $payment_status = null)
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            if ($payment_status) {
                $this->db->query("UPDATE bookings SET status = :status, payment_status = :payment_status, updated_at = NOW() WHERE id = :id");
                $this->db->bind(':payment_status', $payment_status);
            } else {
                $this->db->query("UPDATE bookings SET status = :status, updated_at = NOW() WHERE id = :id");
            }

            $this->db->bind(':status', $status);
            $this->db->bind(':id', $booking_id);

            if ($this->db->execute()) {
                return ['success' => true, 'message' => 'Booking status updated'];
            } else {
                throw new Exception("Failed to update booking");
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // Cancel booking
    public function cancelBooking($booking_id, $reason = '')
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            $this->db->query("UPDATE bookings SET status = 'cancelled', cancellation_reason = :reason, cancelled_at = NOW() WHERE id = :id");
            $this->db->bind(':reason', $reason);
            $this->db->bind(':id', $booking_id);

            if ($this->db->execute()) {
                return ['success' => true, 'message' => 'Booking cancelled successfully'];
            } else {
                throw new Exception("Failed to cancel booking");
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // Get bookings for a specific stadium
    public function getStadiumBookings($stadium_id, $owner_id)
    {
        try {
            if (!$this->db) {
                return [];
            }

            $this->db->query("SELECT b.*, 
                u.first_name, u.last_name, u.email
                FROM bookings b
                JOIN users u ON b.customer_id = u.id
                WHERE b.stadium_id = :stadium_id AND b.owner_id = :owner_id
                ORDER BY b.booking_date DESC");

            $this->db->bind(':stadium_id', $stadium_id);
            $this->db->bind(':owner_id', $owner_id);

            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getStadiumBookings: ' . $e->getMessage());
            return [];
        }
    }

    public function createAdvertisement($user_id, $data)
    {
        try {
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
        } catch (Exception $e) {
            error_log('Error in createAdvertisement: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteAdvertisement($id, $user_id)
    {
        try {
            $this->db->query('UPDATE advertisement_requests 
                              SET is_active = 0 
                              WHERE id = :id AND user_id = :uid');
            $this->db->bind(':id', $id);
            $this->db->bind(':uid', $user_id);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in deleteAdvertisement: ' . $e->getMessage());
            return false;
        }
    }

    public function getAdvertisementById($id, $user_id)
    {
        try {
            $this->db->query('SELECT * FROM advertisement_requests 
                              WHERE id = :id AND user_id = :uid AND is_active = 1');
            $this->db->bind(':id', $id);
            $this->db->bind(':uid', $user_id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Error in getAdvertisementById: ' . $e->getMessage());
            return null;
        }
    }

    public function updateAdvertisement($id, $user_id, $data)
    {
        try {
            $sql = 'UPDATE advertisement_requests 
                    SET company_name = :company_name, 
                        website = :website, 
                        message = :message, 
                        status = :status,
                        submitted_at = :submitted_at';

            if (!empty($data['file_path'])) {
                $sql .= ', file_path = :file_path';
            }

            $sql .= ' WHERE id = :id AND user_id = :uid';

            $this->db->query($sql);
            $this->db->bind(':company_name', $data['company_name']);
            $this->db->bind(':website', $data['website'] ?? null);
            $this->db->bind(':message', $data['message'] ?? null);
            $this->db->bind(':status', 'pending');
            $this->db->bind(':submitted_at', date('Y-m-d H:i:s'));
            $this->db->bind(':id', $id);
            $this->db->bind(':uid', $user_id);

            if (!empty($data['file_path'])) {
                $this->db->bind(':file_path', $data['file_path']);
            }

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in updateAdvertisement: ' . $e->getMessage());
            return false;
        }
    }

    public function getAdvertisementPackages()
    {
        return [
            'basic' => ['name' => 'Basic', 'price' => 5000, 'duration' => '7 days'],
            'professional' => ['name' => 'Professional', 'price' => 12000, 'duration' => '30 days'],
            'premium' => ['name' => 'Premium', 'price' => 30000, 'duration' => '90 days']
        ];
    }

    // Check stadium availability for a date and time
    public function checkAvailability($stadium_id, $date, $start_time, $end_time)
    {
        try {
            if (!$this->db) {
                return true; // Assume available if no DB connection
            }

            // Only check for confirmed bookings, not reserved (temporary) ones
            // This allows slots to open up when reserved bookings expire
            $this->db->query("SELECT COUNT(*) as count FROM bookings 
                WHERE stadium_id = :stadium_id 
                AND DATE(start_date) = :date
                AND status = 'confirmed'
                AND (
                    (start_time < :end_time AND end_time > :start_time)
                )");

            $this->db->bind(':stadium_id', $stadium_id);
            $this->db->bind(':date', $date);
            $this->db->bind(':start_time', $start_time);
            $this->db->bind(':end_time', $end_time);

            $result = $this->db->single();
            return ($result && $result->count == 0); // Available if no conflicting confirmed bookings
        } catch (Exception $e) {
            error_log('Error in checkAvailability: ' . $e->getMessage());
            return true;
        }
    }

    // ==================== REVIEW SYSTEM METHODS ====================

    // Get stadium reviews
    public function getStadiumReviews($stadium_id, $limit = 5)
    {
        try {
            if (!$this->db) {
                return [];
            }

            $this->db->query("SELECT r.*, 
                u.first_name, u.last_name
                FROM reviews r
                JOIN users u ON r.customer_id = u.id
                WHERE r.stadium_id = :stadium_id AND r.status = 'approved'
                ORDER BY r.created_at DESC
                LIMIT :limit");

            $this->db->bind(':stadium_id', $stadium_id);
            $this->db->bind(':limit', $limit);

            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getStadiumReviews: ' . $e->getMessage());
            return [];
        }
    }

    // Add review for stadium
    public function addReview($review_data)
    {
        try {
            if (!$this->db) {
                throw new Exception("Database connection not available");
            }

            $this->db->query("INSERT INTO reviews (
                stadium_id, customer_id, booking_id, rating, comment,
                cleanliness_rating, amenities_rating, service_rating, value_rating,
                verified_booking, status
            ) VALUES (
                :stadium_id, :customer_id, :booking_id, :rating, :comment,
                :cleanliness_rating, :amenities_rating, :service_rating, :value_rating,
                :verified_booking, 'approved'
            )");

            $this->db->bind(':stadium_id', $review_data['stadium_id']);
            $this->db->bind(':customer_id', $review_data['customer_id']);
            $this->db->bind(':booking_id', $review_data['booking_id'] ?? null);
            $this->db->bind(':rating', $review_data['rating']);
            $this->db->bind(':comment', $review_data['comment'] ?? '');
            $this->db->bind(':cleanliness_rating', $review_data['cleanliness_rating'] ?? 5);
            $this->db->bind(':amenities_rating', $review_data['amenities_rating'] ?? 5);
            $this->db->bind(':service_rating', $review_data['service_rating'] ?? 5);
            $this->db->bind(':value_rating', $review_data['value_rating'] ?? 5);
            $this->db->bind(':verified_booking', $review_data['verified_booking'] ?? true);

            if ($this->db->execute()) {
                // Update stadium ratings
                $this->updateStadiumRating($review_data['stadium_id']);
                return ['success' => true, 'message' => 'Review added successfully'];
            } else {
                throw new Exception("Failed to add review");
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // Update stadium average rating
    private function updateStadiumRating($stadium_id)
    {
        try {
            $this->db->query("UPDATE stadiums SET 
                average_rating = (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE stadium_id = :stadium_id AND status = 'approved'),
                total_reviews = (SELECT COUNT(*) FROM reviews WHERE stadium_id = :stadium_id AND status = 'approved')
                WHERE id = :stadium_id");

            $this->db->bind(':stadium_id', $stadium_id);
            $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in updateStadiumRating: ' . $e->getMessage());
        }
    }

    // ==================== PACKAGE MANAGEMENT METHODS ====================

    // Get stadium owner's active package information
    public function getPackageInfo($owner_id)
    {
        try {
            // Get active package purchase details
            $this->db->query('SELECT pp.*, sp.name as package_name, sp.stadium_limit, sp.photos_per_property, 
                sp.videos_per_property, sp.setup_fee, sp.commission_rate, sp.description
                FROM package_purchases pp
                JOIN stadium_packages sp ON pp.package_id = sp.id
                WHERE pp.user_id = :owner_id AND pp.package_status = "active"
                ORDER BY pp.purchased_at DESC LIMIT 1');

            $this->db->bind(':owner_id', $owner_id);
            $package = $this->db->single();

            if ($package) {
                // Calculate days since purchase
                $purchaseDate = new DateTime($package->purchased_at);
                $today = new DateTime();
                $daysSincePurchase = $purchaseDate->diff($today)->days;

                // One-time package (no expiry for stadium packages)
                return [
                    'package_id' => $package->package_id ?? null,
                    'purchase_id' => $package->id ?? null,
                    'package_name' => $package->package_name ?? 'No Package',
                    'package_status' => $package->package_status ?? 'inactive',
                    'stadium_limit' => $package->stadium_limit ?? '0',
                    'photos_limit' => $package->photos_per_property ?? 0,
                    'videos_limit' => $package->videos_per_property ?? 0,
                    'setup_fee' => $package->setup_fee ?? 0,
                    'commission_rate' => $package->commission_rate ?? 0,
                    'amount_paid' => $package->payment_amount ?? 0,
                    'purchased_at' => $package->purchased_at ?? null,
                    'purchased_date_formatted' => $purchaseDate->format('M d, Y'),
                    'days_since_purchase' => $daysSincePurchase,
                    'package_type' => 'one_time',
                    'stripe_payment_id' => $package->stripe_charge_id ?? null,
                    'support_type' => 'Email & Phone Support',
                    'description' => $package->description ?? '',
                    'is_unlimited' => (strtolower($package->stadium_limit) === 'unlimited')
                ];
            }

            return [
                'package_name' => 'No Active Package',
                'package_status' => 'inactive',
                'stadium_limit' => '0',
                'photos_limit' => 0,
                'videos_limit' => 0,
                'setup_fee' => 0,
                'support_type' => 'N/A'
            ];
        } catch (Exception $e) {
            error_log('Error in getPackageInfo: ' . $e->getMessage());
            return [
                'package_name' => 'Error Loading Package',
                'package_status' => 'error',
                'stadium_limit' => '0',
                'photos_limit' => 0
            ];
        }
    }

    // Get package limits for stadium listings
    public function getPackageLimits($owner_id)
    {
        try {
            error_log("getPackageLimits called for owner_id: " . $owner_id);

            // Get user's active package purchase
            $this->db->query('SELECT pp.*, sp.stadium_limit, sp.photos_per_property, sp.videos_per_property
                FROM package_purchases pp
                JOIN stadium_packages sp ON pp.package_id = sp.id
                WHERE pp.user_id = :owner_id AND pp.package_status = "active"
                ORDER BY pp.purchased_at DESC LIMIT 1');

            $this->db->bind(':owner_id', $owner_id);
            $package = $this->db->single();

            error_log("Package found: " . ($package ? 'YES - Limit: ' . $package->stadium_limit : 'NO'));

            if ($package) {
                $stadiumLimit = strtolower($package->stadium_limit) === 'unlimited' ? 999999 : intval($package->stadium_limit);

                // Get current stadium count
                $this->db->query('SELECT COUNT(*) as count FROM stadiums WHERE owner_id = :owner_id');
                $this->db->bind(':owner_id', $owner_id);
                $result = $this->db->single();
                $currentStadiums = $result ? $result->count : 0;

                return [
                    'stadium_limit' => $package->stadium_limit,
                    'stadium_limit_numeric' => $stadiumLimit,
                    'current_stadiums' => $currentStadiums,
                    'can_add_more' => $currentStadiums < $stadiumLimit,
                    'photos_per_stadium' => $package->photos_per_property ?? 0,
                    'videos_per_stadium' => $package->videos_per_property ?? 0,
                    'is_unlimited' => strtolower($package->stadium_limit) === 'unlimited'
                ];
            }

            return [
                'stadium_limit' => '0',
                'stadium_limit_numeric' => 0,
                'current_stadiums' => 0,
                'can_add_more' => false,
                'photos_per_stadium' => 0,
                'videos_per_stadium' => 0,
                'is_unlimited' => false
            ];
        } catch (Exception $e) {
            error_log('Error in getPackageLimits: ' . $e->getMessage());
            return [
                'stadium_limit' => '0',
                'stadium_limit_numeric' => 0,
                'current_stadiums' => 0,
                'can_add_more' => false,
                'photos_per_stadium' => 0,
                'videos_per_stadium' => 0
            ];
        }
    }

    // Get stadium summary with package info
    public function getStadiumSummary($owner_id)
    {
        try {
            // Get package limits
            $limits = $this->getPackageLimits($owner_id);

            // Get stadium count
            $this->db->query('SELECT COUNT(*) as total_stadiums FROM stadiums WHERE owner_id = :owner_id');
            $this->db->bind(':owner_id', $owner_id);
            $stadiumCount = $this->db->single();

            return [
                'total_stadiums' => $stadiumCount->total_stadiums ?? 0,
                'stadium_limit' => $limits['stadium_limit'],
                'current_stadiums' => $limits['current_stadiums'],
                'can_add_more' => $limits['can_add_more'],
                'is_unlimited' => $limits['is_unlimited']
            ];
        } catch (Exception $e) {
            error_log('Error in getStadiumSummary: ' . $e->getMessage());
            return [];
        }
    }

    // Add stadium images to database
    public function addStadiumImages($stadium_id, $images)
    {
        try {
            foreach ($images as $index => $imagePath) {
                $this->db->query("INSERT INTO stadium_images (stadium_id, image_path, is_primary, display_order) 
                                 VALUES (:stadium_id, :image_path, :is_primary, :display_order)");

                $this->db->bind(':stadium_id', $stadium_id);
                $this->db->bind(':image_path', $imagePath);
                $this->db->bind(':is_primary', $index === 0 ? 1 : 0); // First image is primary
                $this->db->bind(':display_order', $index);

                $this->db->execute();
            }
            return true;
        } catch (Exception $e) {
            error_log('Error in addStadiumImages: ' . $e->getMessage());
            return false;
        }
    }

    // Get stadium images
    public function getStadiumImages($stadium_id)
    {
        try {
            $this->db->query('SELECT * FROM stadium_images WHERE stadium_id = :stadium_id ORDER BY display_order ASC');
            $this->db->bind(':stadium_id', $stadium_id);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getStadiumImages: ' . $e->getMessage());
            return [];
        }
    }

    // Count stadium images
    public function getStadiumImageCount($stadium_id)
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM stadium_images WHERE stadium_id = :stadium_id');
            $this->db->bind(':stadium_id', $stadium_id);
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            error_log('Error in getStadiumImageCount: ' . $e->getMessage());
            return 0;
        }
    }
}
