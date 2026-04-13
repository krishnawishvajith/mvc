<?php
// Update your app/controllers/Newsletter.php file

class Newsletter extends Controller {
    private $newsletterModel;

    public function __construct()
    {
        // Check if admin is logged in (session already started in bootloader)
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        try {
            $this->newsletterModel = $this->model('M_Newsletter');
        } catch (Exception $e) {
            error_log('Error loading newsletter model: ' . $e->getMessage());
            die('Error loading newsletter model: ' . $e->getMessage());
        }
    }

    public function index() {
        try {
            // Get newsletter stats and recent activity
            $data = [
                'title' => 'Newsletter Management',
                'total_subscribers' => $this->newsletterModel->getTotalSubscribers(),
                'active_subscribers' => $this->newsletterModel->getActiveSubscribers(),
                'newsletters_sent' => $this->newsletterModel->getNewslettersSent(),
                'recent_newsletters' => $this->newsletterModel->getRecentNewsletters(5),
                'subscriber_growth' => $this->newsletterModel->getSubscriberGrowth(),
                'top_categories' => $this->newsletterModel->getTopCategories()
            ];

            $this->view('admin/v_newsletter', $data);
            
        } catch (Exception $e) {
            error_log('Error in newsletter index: ' . $e->getMessage());
            die('Error in newsletter index: ' . $e->getMessage());
        }
    }

    public function subscribers() {
        // Manage subscribers list
        $data = [
            'title' => 'Newsletter Subscribers',
            'subscribers' => $this->newsletterModel->getAllSubscribers(),
            'subscriber_stats' => $this->newsletterModel->getSubscriberStats()
        ];

        $this->view('admin/v_newsletter_subscribers', $data);
    }

    public function compose() {
        // Compose new newsletter
        $data = [
            'title' => 'Compose Newsletter',
            'templates' => $this->newsletterModel->getEmailTemplates(),
            'subscriber_segments' => $this->newsletterModel->getSubscriberSegments()
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->processNewsletter($data);
        }

        $this->view('admin/v_newsletter_compose', $data);
    }

    private function processNewsletter($data) {
        $newsletterData = [
            'subject' => trim($_POST['subject'] ?? ''),
            'content' => $_POST['content'] ?? '',
            'template' => $_POST['template'] ?? 'default',
            'recipient_type' => $_POST['recipient_type'] ?? 'all',
            'schedule_type' => $_POST['schedule_type'] ?? 'now',
            'schedule_date' => $_POST['schedule_date'] ?? null,
            'categories' => $_POST['categories'] ?? []
        ];

        // Validation
        if (empty($newsletterData['subject'])) {
            $data['error'] = 'Subject is required';
            return $data;
        }

        if (empty($newsletterData['content'])) {
            $data['error'] = 'Content is required';
            return $data;
        }

        // Save newsletter
        if ($this->newsletterModel->createNewsletter($newsletterData)) {
            if ($newsletterData['schedule_type'] == 'now') {
                // Send immediately
                $this->newsletterModel->sendNewsletter($newsletterData);
                $data['success'] = 'Newsletter sent successfully!';
            } else {
                // Schedule for later
                $data['success'] = 'Newsletter scheduled successfully!';
            }
        } else {
            $data['error'] = 'Failed to create newsletter';
        }

        return $data;
    }

    public function templates() {
        // Manage email templates
        $data = [
            'title' => 'Email Templates',
            'templates' => $this->newsletterModel->getAllTemplates()
        ];

        $this->view('admin/v_newsletter_templates', $data);
    }

    public function analytics() {
        // Newsletter analytics and reports
        $data = [
            'title' => 'Newsletter Analytics',
            'campaign_stats' => $this->newsletterModel->getCampaignStats(),
            'engagement_metrics' => $this->newsletterModel->getEngagementMetrics(),
            'subscriber_analytics' => $this->newsletterModel->getSubscriberAnalytics()
        ];

        $this->view('admin/v_newsletter_analytics', $data);
    }
}