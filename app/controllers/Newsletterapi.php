<?php
class Newsletterapi extends Controller {
    private $newsletterModel;

    public function __construct() {
        $this->newsletterModel = $this->model('M_Newsletter');
    }

    // Public endpoint: /newsletterapi/subscribe
    public function subscribe() {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $email = trim($_POST['email'] ?? '');
            $privacy = isset($_POST['privacy']) && ($_POST['privacy'] === '1' || $_POST['privacy'] === 'true' || $_POST['privacy'] === 'on');

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Please enter a valid email']);
                exit;
            }

            if (!$privacy) {
                echo json_encode(['success' => false, 'message' => 'Please accept the privacy policy']);
                exit;
            }

            $ok = $this->newsletterModel->subscribeEmail($email);

            if ($ok) {
                echo json_encode(['success' => true, 'message' => 'Subscribed successfully']);
                exit;
            }

            // If saving failed, check if it already exists (idempotent behavior)
            $existing = $this->newsletterModel->getSubscriberByEmail($email);
            if ($existing) {
                echo json_encode(['success' => true, 'message' => 'You are already subscribed']);
                exit;
            }

            echo json_encode(['success' => false, 'message' => 'Could not save subscription. Please try again.']);
            exit;
        } catch (Exception $e) {
            error_log('Newsletterapi subscribe error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Failed to subscribe. Please try again.',
                'debug' => $e->getMessage()
            ]);
            exit;
        }
    }
}

