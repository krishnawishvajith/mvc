<?php

class M_Newsletter {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Get total subscribers count
    public function getTotalSubscribers() {
        try {
            $this->db->query("SELECT COUNT(*) AS total FROM newsletter_subscribers");
            $result = $this->db->single();
            return (int)($result->total ?? 0);
        } catch (Exception $e) {
            error_log('M_Newsletter getTotalSubscribers error: ' . $e->getMessage());
            return 0;
        }
    }

    // Get active subscribers count
    public function getActiveSubscribers() {
        try {
            $this->db->query("SELECT COUNT(*) AS total FROM newsletter_subscribers WHERE status = 'active'");
            $result = $this->db->single();
            return (int)($result->total ?? 0);
        } catch (Exception $e) {
            error_log('M_Newsletter getActiveSubscribers error: ' . $e->getMessage());
            return 0;
        }
    }

    // Get newsletters sent this month
    public function getNewslettersSent() {
        // Return sample data for now
        return 8;
    }

    // Get recent newsletters
    public function getRecentNewsletters($limit = 5) {
        // Return sample data for now
        return [
            [
                'id' => 1,
                'subject' => 'New Stadium Openings This Month',
                'recipients' => 1195,
                'open_rate' => 34.2,
                'click_rate' => 8.5,
                'sent_date' => '2025-01-20',
                'status' => 'Sent'
            ],
            [
                'id' => 2,
                'subject' => 'Equipment Rental Special Offers',
                'recipients' => 1180,
                'open_rate' => 28.7,
                'click_rate' => 6.2,
                'sent_date' => '2025-01-15',
                'status' => 'Sent'
            ],
            [
                'id' => 3,
                'subject' => 'Coaching Sessions Available',
                'recipients' => 1165,
                'open_rate' => 31.5,
                'click_rate' => 9.1,
                'sent_date' => '2025-01-10',
                'status' => 'Sent'
            ]
        ];
    }

    // Get subscriber growth data
    public function getSubscriberGrowth() {
        // Return sample data for now
        return [
            'this_month' => 52,
            'last_month' => 38,
            'growth_percentage' => 36.8
        ];
    }

    // Get top content categories
    public function getTopCategories() {
        // Return sample data for now
        return [
            ['name' => 'Stadium Updates', 'percentage' => 35],
            ['name' => 'Equipment News', 'percentage' => 28],
            ['name' => 'Coaching Tips', 'percentage' => 22],
            ['name' => 'Special Offers', 'percentage' => 15]
        ];
    }

    // Get all subscribers
    public function getAllSubscribers() {
        try {
            $this->db->query("
                SELECT 
                    id,
                    email,
                    name,
                    status,
                    DATE_FORMAT(subscribed_at, '%Y-%m-%d') AS subscribed_date
                FROM newsletter_subscribers
                ORDER BY subscribed_at DESC
            ");
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('M_Newsletter getAllSubscribers error: ' . $e->getMessage());
            return [];
        }
    }

    // Get subscriber statistics
    public function getSubscriberStats() {
        try {
            $this->db->query("
                SELECT 
                    status,
                    COUNT(*) AS count
                FROM newsletter_subscribers
                GROUP BY status
            ");
            $results = $this->db->resultSet();

            $counts = [
                'total' => 0,
                'active' => 0,
                'inactive' => 0
            ];

            foreach ($results as $r) {
                $status = strtolower($r->status ?? '');
                if (!isset($counts[$status])) {
                    continue;
                }
                $counts[$status] = (int)($r->count ?? 0);
                $counts['total'] += (int)($r->count ?? 0);
            }

            // Keep these fields for compatibility with the existing newsletter UI.
            $counts['this_month_new'] = 0;
            $counts['unsubscribed_this_month'] = 0;

            return $counts;
        } catch (Exception $e) {
            error_log('M_Newsletter getSubscriberStats error: ' . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'this_month_new' => 0,
                'unsubscribed_this_month' => 0
            ];
        }
    }

    // Save a subscriber from the public newsletter form
    public function subscribeEmail($email, $name = null) {
        $email = strtolower(trim((string)$email));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // If subscriber exists, update status to active (idempotent subscribe)
        $this->db->query("SELECT id FROM newsletter_subscribers WHERE email = :email LIMIT 1");
        $this->db->bind(':email', $email);
        $existing = $this->db->single();

        if ($existing) {
            $this->db->query("
                UPDATE newsletter_subscribers
                SET 
                    name = :name,
                    status = 'active',
                    updated_at = NOW()
                WHERE email = :email
            ");
            $this->db->bind(':name', $name);
            $this->db->bind(':email', $email);
            return $this->db->execute();
        }

        $this->db->query("
            INSERT INTO newsletter_subscribers (email, name, status, subscribed_at, updated_at)
            VALUES (:email, :name, 'active', NOW(), NOW())
        ");
        $this->db->bind(':email', $email);
        $this->db->bind(':name', $name);
        return $this->db->execute();
    }

    // Get subscriber record by email
    public function getSubscriberByEmail($email) {
        try {
            $email = strtolower(trim((string)$email));
            $this->db->query("SELECT id, email, name, status, subscribed_at FROM newsletter_subscribers WHERE email = :email LIMIT 1");
            $this->db->bind(':email', $email);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('M_Newsletter getSubscriberByEmail error: ' . $e->getMessage());
            return null;
        }
    }

    // Get email templates
    public function getEmailTemplates() {
        return [
            ['id' => 1, 'name' => 'Default Newsletter', 'description' => 'Standard newsletter layout'],
            ['id' => 2, 'name' => 'Stadium Focus', 'description' => 'Template focused on stadium listings'],
            ['id' => 3, 'name' => 'Equipment Promotions', 'description' => 'Template for equipment deals'],
            ['id' => 4, 'name' => 'Event Announcement', 'description' => 'Template for special events']
        ];
    }

    // Get subscriber segments
    public function getSubscriberSegments() {
        return [
            'all' => 'All Subscribers (1,247)',
            'customers' => 'Customers Only (856)',
            'stadium_owners' => 'Stadium Owners (145)',
            'coaches' => 'Coaches (128)',
            'rental_owners' => 'Rental Owners (118)',
            'active_users' => 'Active Users (1,195)',
            'new_subscribers' => 'New Subscribers (52)'
        ];
    }

    // Create newsletter
    public function createNewsletter($data) {
        // In a real implementation, this would save to database
        // For demo, return true
        return true;
    }

    // Send newsletter
    public function sendNewsletter($data) {
        // In a real implementation, this would send emails
        // For demo, return true
        return true;
    }

    // Get all templates
    public function getAllTemplates() {
        return [
            [
                'id' => 1,
                'name' => 'Default Newsletter',
                'description' => 'Standard newsletter layout with header and footer',
                'preview_image' => 'template-default.jpg',
                'usage_count' => 15,
                'created_date' => '2024-12-01',
                'status' => 'Active'
            ],
            [
                'id' => 2,
                'name' => 'Stadium Focus',
                'description' => 'Template optimized for stadium listings and updates',
                'preview_image' => 'template-stadium.jpg',
                'usage_count' => 8,
                'created_date' => '2024-12-10',
                'status' => 'Active'
            ],
            [
                'id' => 3,
                'name' => 'Equipment Promotions',
                'description' => 'Perfect for equipment deals and rental offers',
                'preview_image' => 'template-equipment.jpg',
                'usage_count' => 12,
                'created_date' => '2024-12-15',
                'status' => 'Active'
            ]
        ];
    }

    // Get campaign statistics
    public function getCampaignStats() {
        return [
            'total_campaigns' => 24,
            'total_emails_sent' => 28650,
            'average_open_rate' => 31.2,
            'average_click_rate' => 7.8,
            'bounce_rate' => 2.1,
            'unsubscribe_rate' => 0.8
        ];
    }

    // Get engagement metrics
    public function getEngagementMetrics() {
        return [
            'most_opened' => 'New Stadium Openings This Month',
            'most_clicked' => 'Equipment Rental Special Offers',
            'best_day' => 'Tuesday',
            'best_time' => '10:00 AM',
            'engagement_trend' => 'increasing'
        ];
    }

    // Get subscriber analytics
    public function getSubscriberAnalytics() {
        return [
            'top_interests' => [
                'Stadium Bookings' => 45,
                'Equipment Rental' => 32,
                'Coaching Services' => 28,
                'Sports Events' => 25
            ],
            'geographic_data' => [
                'Colombo' => 423,
                'Kandy' => 198,
                'Galle' => 156,
                'Negombo' => 134,
                'Other' => 336
            ]
        ];
    }
}