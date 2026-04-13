<?php
class M_Contact {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // ---------- Contact page settings (admin-editable) ----------
    public function getContactPageSettings() {
        $defaults = [
            'page_title' => 'Contact Us',
            'page_subtitle' => 'Have questions about stadium bookings, equipment rentals, or coaching services? We\'re here to help! Reach out to us and we\'ll get back to you as soon as possible.',
            'main_phone' => '(071) 111 1111',
            'support_phone' => '(071) 222 2222',
            'email' => 'support@bookmyground.lk',
            'support_email' => 'help@bookmyground.lk',
            'address' => '4200 Reid Avenue, Colombo 07',
            'working_hours' => 'Monday - Sunday: 6:00 AM - 10:00 PM',
            'emergency_contact' => '(071) 999 9999',
        ];

        try {
            $keys = array_keys($defaults);
            $in = "'" . implode("','", $keys) . "'";
            $this->db->query("SELECT setting_key, setting_value FROM contact_page_settings WHERE setting_key IN ($in)");
            $rows = $this->db->resultSet();
            foreach ($rows as $row) {
                if (isset($defaults[$row->setting_key])) {
                    $defaults[$row->setting_key] = $row->setting_value;
                }
            }
        } catch (Exception $e) {
            error_log('Contact settings load error: ' . $e->getMessage());
        }

        return $defaults;
    }

    public function updateContactPageSettings($settings) {
        try {
            if (!is_array($settings)) return false;

            $allowed = [
                'page_title',
                'page_subtitle',
                'main_phone',
                'support_phone',
                'email',
                'support_email',
                'address',
                'working_hours',
                'emergency_contact',
            ];

            foreach ($allowed as $key) {
                if (!array_key_exists($key, $settings)) continue;
                $val = trim((string)$settings[$key]);

                $this->db->query("
                    INSERT INTO contact_page_settings (setting_key, setting_value)
                    VALUES (:k, :v)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
                ");
                $this->db->bind(':k', $key);
                $this->db->bind(':v', $val);
                $this->db->execute();
            }

            return true;
        } catch (Exception $e) {
            error_log('Contact settings save error: ' . $e->getMessage());
            return false;
        }
    }

    // Save contact form submission
    public function saveContactMessage($data) {
        try {
            $this->db->query('INSERT INTO contact_messages (
                first_name, 
                last_name, 
                email, 
                phone, 
                subject, 
                message, 
                status,
                submitted_at
            ) VALUES (
                :first_name, 
                :last_name, 
                :email, 
                :phone, 
                :subject, 
                :message, 
                :status,
                :submitted_at
            )');

            $this->db->bind(':first_name', $data['first_name']);
            $this->db->bind(':last_name', $data['last_name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':subject', $data['subject']);
            $this->db->bind(':message', $data['message']);
            $this->db->bind(':status', 'new');
            $this->db->bind(':submitted_at', $data['submitted_at']);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Contact Model Error: ' . $e->getMessage());
            return false;
        }
    }

    // Get FAQ categories for support page
    public function getFAQCategories() {
        return [
            'booking' => [
                'name' => 'Stadium Booking',
                'icon' => '🏟️',
                'count' => 8
            ],
            'equipment' => [
                'name' => 'Equipment Rental',
                'icon' => '⚽',
                'count' => 6
            ],
            'coaching' => [
                'name' => 'Coaching Services',
                'icon' => '👨‍🏫',
                'count' => 5
            ],
            'payment' => [
                'name' => 'Payment & Billing',
                'icon' => '💳',
                'count' => 4
            ],
            'account' => [
                'name' => 'Account Management',
                'icon' => '👤',
                'count' => 7
            ],
            'technical' => [
                'name' => 'Technical Support',
                'icon' => '🔧',
                'count' => 5
            ]
        ];
    }

    // Get common issues for quick help
    public function getCommonIssues() {
        return [
            [
                'title' => 'How to book a stadium?',
                'description' => 'Step-by-step guide to booking your first venue',
                'link' => URLROOT . '/faq#booking'
            ],
            [
                'title' => 'Payment methods accepted',
                'description' => 'Learn about our secure payment options',
                'link' => URLROOT . '/faq#payment'
            ],
            [
                'title' => 'Cancellation policy',
                'description' => 'Understanding our booking cancellation terms',
                'link' => URLROOT . '/faq#cancellation'
            ],
            [
                'title' => 'Equipment rental process',
                'description' => 'How to rent sports equipment',
                'link' => URLROOT . '/faq#equipment'
            ]
        ];
    }

    // Get all contact messages (for admin)
    public function getAllContactMessages($limit = 50) {
        try {
            $this->db->query('SELECT * FROM contact_messages ORDER BY submitted_at DESC LIMIT :limit');
            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Contact Model Error: ' . $e->getMessage());
            return [];
        }
    }

    public function getContactMessageById($id) {
        try {
            $this->db->query('SELECT * FROM contact_messages WHERE id = :id LIMIT 1');
            $this->db->bind(':id', (int)$id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Contact Model Error: ' . $e->getMessage());
            return null;
        }
    }

    // Update message status
    public function updateMessageStatus($id, $status) {
        try {
            $this->db->query('UPDATE contact_messages SET status = :status, updated_at = NOW() WHERE id = :id');
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Contact Model Error: ' . $e->getMessage());
            return false;
        }
    }

    // Get contact statistics
    public function getContactStats() {
        try {
            $stats = [];
            
            // Total messages
            $this->db->query('SELECT COUNT(*) as total FROM contact_messages');
            $result = $this->db->single();
            $stats['total_messages'] = $result ? $result->total : 0;
            
            // New messages
            $this->db->query('SELECT COUNT(*) as total FROM contact_messages WHERE status = "new"');
            $result = $this->db->single();
            $stats['new_messages'] = $result ? $result->total : 0;
            
            // This month messages
            $this->db->query('SELECT COUNT(*) as total FROM contact_messages WHERE MONTH(submitted_at) = MONTH(NOW()) AND YEAR(submitted_at) = YEAR(NOW())');
            $result = $this->db->single();
            $stats['this_month'] = $result ? $result->total : 0;
            
            return $stats;
        } catch (Exception $e) {
            error_log('Contact Model Error: ' . $e->getMessage());
            return [
                'total_messages' => 0,
                'new_messages' => 0,
                'this_month' => 0
            ];
        }
    }
}
?>