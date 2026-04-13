<?php
class Messages extends Controller {
    private $messageModel;
    
    public function __construct() {
        $this->messageModel = $this->model('M_Messages');
    }
    
    // Send a new message
    public function send() {
        header('Content-Type: application/json');
        
        try {
            // Check if user is logged in
            if (!Auth::isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'You must be logged in to send messages']);
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }
            
            $senderId = Auth::getUserId();
            $receiverId = $_POST['receiver_id'] ?? null;
            $stadiumId = $_POST['stadium_id'] ?? null;
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            // Validation
            if (!$receiverId) {
                echo json_encode(['success' => false, 'message' => 'Receiver ID is required']);
                exit;
            }
            
            if (empty($subject) || strlen($subject) < 3) {
                echo json_encode(['success' => false, 'message' => 'Subject must be at least 3 characters']);
                exit;
            }
            
            if (empty($message) || strlen($message) < 10) {
                echo json_encode(['success' => false, 'message' => 'Message must be at least 10 characters']);
                exit;
            }
            
            if (strlen($subject) > 255) {
                echo json_encode(['success' => false, 'message' => 'Subject is too long (max 255 characters)']);
                exit;
            }
            
            if (strlen($message) > 1000) {
                echo json_encode(['success' => false, 'message' => 'Message is too long (max 1000 characters)']);
                exit;
            }
            
            // Check if user is trying to message themselves
            if ($senderId == $receiverId) {
                echo json_encode(['success' => false, 'message' => 'You cannot send a message to yourself']);
                exit;
            }
            
            // Check rate limiting for admin messages (24 hours)
            if ($this->messageModel->isUserAdmin($receiverId)) {
                if ($this->messageModel->hasUserMessagedAdminRecently($senderId)) {
                    echo json_encode(['success' => false, 'message' => 'You can only send one message to admin every 24 hours. Please try again later.']);
                    exit;
                }
            }
            
            // Send the message
            $result = $this->messageModel->sendMessage($senderId, $receiverId, $stadiumId, $subject, $message);
            
            if ($result) {
                // Send email notification to receiver
                $this->sendEmailNotification($receiverId, $subject, $message);
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Message sent successfully!'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send message']);
            }
            
        } catch (Exception $e) {
            error_log('Send Message Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
        }
        exit;
    }

    // Mark message as read
    public function markRead() {
        header('Content-Type: application/json');

        try {
            if (!Auth::isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'You must be logged in to mark messages as read']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $messageId = isset($_POST['message_id']) ? (int) $_POST['message_id'] : 0;
            $userId = Auth::getUserId();

            if (!$messageId) {
                echo json_encode(['success' => false, 'message' => 'Message ID is required']);
                exit;
            }

            $result = $this->messageModel->markAsRead($messageId, $userId);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Message marked as read']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Unable to update message status']);
            }
        } catch (Exception $e) {
            error_log('Mark read error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred']);
        }
        exit;
    }

    public function getConversation() {
        header('Content-Type: application/json');

        try {
            if (!Auth::isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'You must be logged in to view conversation']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $userId = Auth::getUserId();
            $otherUserId = isset($_POST['other_user_id']) ? (int) $_POST['other_user_id'] : 0;
            $stadiumId = isset($_POST['stadium_id']) && $_POST['stadium_id'] !== '' ? (int) $_POST['stadium_id'] : null;

            if (!$otherUserId) {
                echo json_encode(['success' => false, 'message' => 'Conversation partner is required']);
                exit;
            }

            $messages = $this->messageModel->getConversation($userId, $otherUserId, $stadiumId);

            echo json_encode(['success' => true, 'messages' => $messages]);
        } catch (Exception $e) {
            error_log('Get conversation error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
        }
        exit;
    }

    // Send email notification to receiver
    private function sendEmailNotification($receiverId, $subject, $message) {
        try {
            $receiver = $this->messageModel->getUserById($receiverId);
            
            if (!$receiver || empty($receiver->email)) {
                return false;
            }
            
            $senderName = Auth::getUserFirstName() . ' ' . Auth::getUserLastName();
            
            $emailSubject = 'New Message: ' . $subject;
            
            $emailBody = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #03B200; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
                    .message-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #03B200; }
                    .cta-button { display: inline-block; background: #03B200; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
                    .footer { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; color: #777; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>📧 New Message Received</h2>
                    </div>
                    <div class='content'>
                        <p>Hello {$receiver->first_name},</p>
                        
                        <p>You have received a new message from <strong>{$senderName}</strong> on BookMyGround.lk:</p>
                        
                        <div class='message-box'>
                            <h3>Subject: {$subject}</h3>
                            <p>{$message}</p>
                        </div>
                        
                        <center>
                            <a href='" . URLROOT . "/messages' class='cta-button'>View & Reply to Message</a>
                        </center>
                        
                        <p style='margin-top: 20px;'><small>💡 Tip: Reply quickly to increase your chances of getting bookings!</small></p>
                        
                        <div class='footer'>
                            <p>This is an automated notification from BookMyGround.lk</p>
                            <p>Please do not reply to this email directly.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail = new Mail();
            return $mail->send($receiver->email, $emailSubject, $emailBody, MAIL_FROM, 'BookMyGround.lk');
            
        } catch (Exception $e) {
            error_log('Failed to send message notification email: ' . $e->getMessage());
            return false;
        }
    }
}
