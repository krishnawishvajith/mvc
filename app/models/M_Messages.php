<?php
class M_Messages {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Send a new message
    public function sendMessage($senderId, $receiverId, $stadiumId, $subject, $message) {
        try {
            $this->db->query('INSERT INTO messages 
                (sender_id, receiver_id, stadium_id, subject, message, is_read, created_at) 
                VALUES 
                (:sender_id, :receiver_id, :stadium_id, :subject, :message, FALSE, NOW())');
            
            $this->db->bind(':sender_id', $senderId);
            $this->db->bind(':receiver_id', $receiverId);
            $this->db->bind(':stadium_id', $stadiumId);
            $this->db->bind(':subject', $subject);
            $this->db->bind(':message', $message);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in sendMessage: ' . $e->getMessage());
            return false;
        }
    }
    
    // Get user by ID
    public function getUserById($userId) {
        try {
            $this->db->query('SELECT id, first_name, last_name, email, phone FROM users WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Error in getUserById: ' . $e->getMessage());
            return null;
        }
    }
    
    // Get inbox messages for a user
    public function getInboxMessages($userId, $limit = 50) {
        try {
            $this->db->query('SELECT m.*, 
                u.first_name as sender_first_name, 
                u.last_name as sender_last_name,
                u.email as sender_email,
                s.name as stadium_name
                FROM messages m
                LEFT JOIN users u ON m.sender_id = u.id
                LEFT JOIN stadiums s ON m.stadium_id = s.id
                WHERE m.receiver_id = :user_id
                ORDER BY m.created_at DESC
                LIMIT :limit');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit);
            
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getInboxMessages: ' . $e->getMessage());
            return [];
        }
    }
    
    // Get sent messages for a user
    public function getSentMessages($userId, $limit = 50) {
        try {
            $this->db->query('SELECT m.*, 
                u.first_name as receiver_first_name, 
                u.last_name as receiver_last_name,
                u.email as receiver_email,
                s.name as stadium_name
                FROM messages m
                LEFT JOIN users u ON m.receiver_id = u.id
                LEFT JOIN stadiums s ON m.stadium_id = s.id
                WHERE m.sender_id = :user_id
                ORDER BY m.created_at DESC
                LIMIT :limit');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit);
            
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getSentMessages: ' . $e->getMessage());
            return [];
        }
    }

    // Get a list of conversations for a user
    public function getConversationList($userId, $limit = 100) {
        try {
            $this->db->query('SELECT m.*, 
                us.first_name as sender_first_name, 
                us.last_name as sender_last_name, 
                us.email as sender_email, 
                ur.first_name as receiver_first_name, 
                ur.last_name as receiver_last_name, 
                ur.email as receiver_email, 
                COALESCE(s.name, rs.store_name) as property_name
                FROM messages m
                LEFT JOIN users us ON m.sender_id = us.id
                LEFT JOIN users ur ON m.receiver_id = ur.id
                LEFT JOIN stadiums s ON m.stadium_id = s.id
                LEFT JOIN rental_shops rs ON m.stadium_id = rs.id
                WHERE m.sender_id = :user_id OR m.receiver_id = :user_id
                ORDER BY m.created_at DESC
                LIMIT :limit');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit);
            $messages = $this->db->resultSet();

            $conversations = [];
            foreach ($messages as $message) {
                $otherUserId = ($message->sender_id == $userId) ? $message->receiver_id : $message->sender_id;
                $conversationKey = $otherUserId . '_' . ($message->stadium_id ?? 0);

                if (!isset($conversations[$conversationKey])) {
                    if ($message->sender_id == $userId) {
                        $otherFirst = $message->receiver_first_name;
                        $otherLast = $message->receiver_last_name;
                        $otherEmail = $message->receiver_email;
                    } else {
                        $otherFirst = $message->sender_first_name;
                        $otherLast = $message->sender_last_name;
                        $otherEmail = $message->sender_email;
                    }

                    $conversations[$conversationKey] = [
                        'other_user_id' => $otherUserId,
                        'stadium_id' => $message->stadium_id,
                        'stadium_name' => $message->stadium_name,
                        'subject' => $message->subject,
                        'last_message' => $message->message,
                        'last_message_id' => $message->id,
                        'last_created_at' => $message->created_at,
                        'other_first_name' => $otherFirst,
                        'other_last_name' => $otherLast,
                        'other_email' => $otherEmail,
                        'unread_count' => 0
                    ];
                }

                if ($message->receiver_id == $userId && empty($message->is_read)) {
                    $conversations[$conversationKey]['unread_count']++;
                }
            }

            return array_values($conversations);
        } catch (Exception $e) {
            error_log('Error in getConversationList: ' . $e->getMessage());
            return [];
        }
    }

    // Get full conversation between two users
    public function getConversation($userId, $otherUserId, $stadiumId = null) {
        try {
            $sql = 'SELECT m.*, 
                us.first_name as sender_first_name, 
                us.last_name as sender_last_name, 
                ur.first_name as receiver_first_name, 
                ur.last_name as receiver_last_name, 
                COALESCE(s.name, rs.store_name) as property_name
                FROM messages m
                LEFT JOIN users us ON m.sender_id = us.id
                LEFT JOIN users ur ON m.receiver_id = ur.id
                LEFT JOIN stadiums s ON m.stadium_id = s.id
                LEFT JOIN rental_shops rs ON m.stadium_id = rs.id
                WHERE ((m.sender_id = :user_id AND m.receiver_id = :other_user_id) OR (m.sender_id = :other_user_id AND m.receiver_id = :user_id))';

            if ($stadiumId !== null) {
                $sql .= ' AND m.stadium_id = :stadium_id';
            }

            $sql .= ' ORDER BY m.created_at ASC';
            $this->db->query($sql);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':other_user_id', $otherUserId);
            if ($stadiumId !== null) {
                $this->db->bind(':stadium_id', $stadiumId);
            }

            $messages = $this->db->resultSet();

            foreach ($messages as &$message) {
                $message->is_sent = ($message->sender_id == $userId);
                $message->sender_name = trim(($message->sender_first_name ?? '') . ' ' . ($message->sender_last_name ?? '')) ?: 'Unknown';
            }

            return $messages;
        } catch (Exception $e) {
            error_log('Error in getConversation: ' . $e->getMessage());
            return [];
        }
    }

    // Mark a conversation as read for a user
    public function markConversationAsRead($userId, $otherUserId, $stadiumId = null) {
        try {
            $sql = 'UPDATE messages SET is_read = TRUE, updated_at = NOW() WHERE receiver_id = :user_id AND sender_id = :other_user_id';
            if ($stadiumId !== null) {
                $sql .= ' AND stadium_id = :stadium_id';
            }

            $this->db->query($sql);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':other_user_id', $otherUserId);
            if ($stadiumId !== null) {
                $this->db->bind(':stadium_id', $stadiumId);
            }

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in markConversationAsRead: ' . $e->getMessage());
            return false;
        }
    }
    
    // Mark message as read
    public function markAsRead($messageId, $userId) {
        try {
            $this->db->query('UPDATE messages 
                SET is_read = TRUE, updated_at = NOW() 
                WHERE id = :message_id AND receiver_id = :user_id');
            
            $this->db->bind(':message_id', $messageId);
            $this->db->bind(':user_id', $userId);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in markAsRead: ' . $e->getMessage());
            return false;
        }
    }
    
    // Get unread message count
    public function getUnreadCount($userId) {
        try {
            $this->db->query('SELECT COUNT(*) as count 
                FROM messages 
                WHERE receiver_id = :user_id AND is_read = FALSE');
            
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            
            return $result->count ?? 0;
        } catch (Exception $e) {
            error_log('Error in getUnreadCount: ' . $e->getMessage());
            return 0;
        }
    }
    
    // Check if user is admin
    public function isUserAdmin($userId) {
        try {
            $this->db->query('SELECT role FROM admins WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            
            return $result && $result->role === 'admin';
        } catch (Exception $e) {
            error_log('Error in isUserAdmin: ' . $e->getMessage());
            return false;
        }
    }
    
    // Check if user has sent a message to admin in the last 24 hours
    public function hasUserMessagedAdminRecently($userId) {
        try {
            $this->db->query("SELECT COUNT(*) as count 
                FROM messages m
                INNER JOIN admins a ON m.receiver_id = a.id
                WHERE m.sender_id = :user_id 
                AND m.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
            
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            
            return ($result->count ?? 0) > 0;
        } catch (Exception $e) {
            error_log('Error in hasUserMessagedAdminRecently: ' . $e->getMessage());
            return false;
        }
    }
}
