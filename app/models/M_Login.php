<?php
class M_Login {
    private $db;

    public function __construct(){
        try {
            $this->db = new Database();
            error_log('M_Login: Database connection successful');
        } catch (Exception $e) {
            error_log('M_Login: Database connection error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Find user by email (includes both regular users and admins)
    public function findUserByEmail($email) {
        try {
            $this->db->query('SELECT * FROM users WHERE email = :email');
            $this->db->bind(':email', $email);
            
            $row = $this->db->single();
            
            if($this->db->rowCount() > 0) {
                error_log('M_Login: User found in users table: ' . $email);
                return $row;
            }
            
            error_log('M_Login: User not found in users table: ' . $email);
            return false;
        } catch (Exception $e) {
            error_log('M_Login: Error finding user by email: ' . $e->getMessage());
            return false;
        }
    }

    // Find admin by email
    public function findAdminByEmail($email) {
        try {
            $this->db->query('SELECT * FROM admins WHERE email = :email');
            $this->db->bind(':email', $email);
            
            $row = $this->db->single();
            
            if($this->db->rowCount() > 0) {
                error_log('M_Login: Admin found: ' . $email);
                return $row;
            }
            
            error_log('M_Login: Admin not found: ' . $email);
            return false;
        } catch (Exception $e) {
            error_log('M_Login: Error finding admin by email: ' . $e->getMessage());
            return false;
        }
    }

    // Verify login credentials (checks both users and admins)
    public function login($email, $password) {
        try {
            error_log('M_Login: Attempting login for email: ' . $email);
            
            // First check if it's an admin
            $admin = $this->findAdminByEmail($email);
            if($admin && password_verify($password, $admin->password)) {
                error_log('M_Login: Admin login successful: ' . $email);
                // Return admin data with role set to 'admin'
                $admin->role = 'admin';
                $admin->full_name = $admin->full_name; // Keep admin structure
                return $admin;
            }

            // If not admin, check regular users
            $user = $this->findUserByEmail($email);
            if($user && password_verify($password, $user->password)) {
                error_log('M_Login: User login successful: ' . $email . ', Role: ' . $user->role);
                return $user;
            }

            error_log('M_Login: Login failed for email: ' . $email);
            return false;
        } catch (Exception $e) {
            error_log('M_Login: Login error: ' . $e->getMessage());
            return false;
        }
    }

    // Check if email exists (in either table)
    public function emailExists($email) {
        try {
            // Check users table
            $this->db->query('SELECT id FROM users WHERE email = :email');
            $this->db->bind(':email', $email);
            $this->db->execute();
            
            if($this->db->rowCount() > 0) {
                return true;
            }

            // Check admins table
            $this->db->query('SELECT id FROM admins WHERE email = :email');
            $this->db->bind(':email', $email);
            $this->db->execute();
            
            return $this->db->rowCount() > 0;
        } catch (Exception $e) {
            error_log('M_Login: Error checking email exists: ' . $e->getMessage());
            return false;
        }
    }

    // Create password reset token (expiry uses MySQL NOW() to avoid timezone mismatch)
    public function createPasswordResetToken($email) {
        try {
            $token = bin2hex(random_bytes(32));
            
            // Check if it's admin
            $admin = $this->findAdminByEmail($email);
            if($admin) {
                $this->db->query('UPDATE admins SET 
                    reset_password_token = :token,
                    reset_password_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                    WHERE email = :email');
            } else {
                $this->db->query('UPDATE users SET 
                    reset_password_token = :token,
                    reset_password_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                    WHERE email = :email');
            }
            
            $this->db->bind(':token', $token);
            $this->db->bind(':email', $email);
            
            if($this->db->execute()) {
                return $token;
            }
            return false;
        } catch (Exception $e) {
            error_log('M_Login: Error creating password reset token: ' . $e->getMessage());
            return false;
        }
    }

    // Log user activity
    public function logActivity($user_id, $activity, $is_admin = false) {
        try {
            error_log('M_Login: Logging activity - User ID: ' . $user_id . ', Activity: ' . $activity . ', Is Admin: ' . ($is_admin ? 'Yes' : 'No'));
            // You can implement activity logging here if needed
            return true;
        } catch (Exception $e) {
            error_log('M_Login: Error logging activity: ' . $e->getMessage());
            return false;
        }
    }

    // Get login attempts for security
    public function getLoginAttempts($email) {
        try {
            $this->db->query('SELECT COUNT(*) as attempts 
                FROM login_attempts 
                WHERE email = :email 
                AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
            
            $this->db->bind(':email', $email);
            $result = $this->db->single();
            
            return $result ? $result->attempts : 0;
        } catch (Exception $e) {
            error_log('M_Login: Error getting login attempts: ' . $e->getMessage());
            return 0;
        }
    }

    // Record failed login attempt
    public function recordLoginAttempt($email, $ip_address) {
        try {
            $this->db->query('INSERT INTO login_attempts (email, ip_address, attempted_at) 
                VALUES (:email, :ip, NOW())');
            
            $this->db->bind(':email', $email);
            $this->db->bind(':ip', $ip_address);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('M_Login: Error recording login attempt: ' . $e->getMessage());
            return false;
        }
    }

    // Clear login attempts after successful login
    public function clearLoginAttempts($email) {
        try {
            $this->db->query('DELETE FROM login_attempts WHERE email = :email');
            $this->db->bind(':email', $email);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('M_Login: Error clearing login attempts: ' . $e->getMessage());
            return false;
        }
    }

    // Update last login time (for both users and admins)
    public function updateLastLogin($user_id, $is_admin = false) {
        try {
            if($is_admin) {
                $this->db->query('UPDATE admins SET last_login = NOW() WHERE id = :id');
            } else {
                $this->db->query('UPDATE users SET last_login = NOW() WHERE id = :id');
            }
            
            $this->db->bind(':id', $user_id);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('M_Login: Error updating last login: ' . $e->getMessage());
            return false;
        }
    }

    // Verify email token
    public function verifyEmail($token) {
        try {
            $this->db->query('UPDATE users SET 
                email_verified = 1,
                email_verification_token = NULL,
                status = "active"
                WHERE email_verification_token = :token');
            
            $this->db->bind(':token', $token);
            
            return $this->db->execute() && $this->db->rowCount() > 0;
        } catch (Exception $e) {
            error_log('M_Login: Error verifying email: ' . $e->getMessage());
            return false;
        }
    }

    // Get email for a valid reset token (call before resetPassword, which clears the token)
    public function getEmailByResetToken($token) {
        $token = $token === null ? '' : trim((string) $token);
        if ($token === '') {
            return null;
        }
        try {
            $this->db->query('SELECT email FROM users WHERE reset_password_token = :token AND reset_password_expires > NOW()');
            $this->db->bind(':token', $token);
            $this->db->execute();
            if ($this->db->rowCount() > 0) {
                $row = $this->db->single();
                return $row->email;
            }
            $this->db->query('SELECT email FROM admins WHERE reset_password_token = :token AND reset_password_expires > NOW()');
            $this->db->bind(':token', $token);
            $this->db->execute();
            if ($this->db->rowCount() > 0) {
                $row = $this->db->single();
                return $row->email;
            }
            return null;
        } catch (Exception $e) {
            error_log('M_Login: getEmailByResetToken error: ' . $e->getMessage());
            return null;
        }
    }

    // Verify reset token (token trimmed in case of URL/whitespace issues)
    public function verifyResetToken($token) {
        $token = $token === null ? '' : trim((string) $token);
        if ($token === '') {
            return false;
        }
        try {
            // Check users table
            $this->db->query('SELECT id FROM users WHERE reset_password_token = :token AND reset_password_expires > NOW()');
            $this->db->bind(':token', $token);
            $this->db->execute();
            
            if($this->db->rowCount() > 0) {
                return true;
            }

            // Check admins table
            $this->db->query('SELECT id FROM admins WHERE reset_password_token = :token AND reset_password_expires > NOW()');
            $this->db->bind(':token', $token);
            $this->db->execute();
            
            return $this->db->rowCount() > 0;
        } catch (Exception $e) {
            error_log('M_Login: Error verifying reset token: ' . $e->getMessage());
            return false;
        }
    }

    // Reset password (token trimmed for consistency with verifyResetToken)
    public function resetPassword($token, $password) {
        $token = $token === null ? '' : trim((string) $token);
        if ($token === '') {
            return false;
        }
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Try users table first
            $this->db->query('UPDATE users SET 
                password = :password,
                reset_password_token = NULL,
                reset_password_expires = NULL,
                updated_at = NOW()
                WHERE reset_password_token = :token AND reset_password_expires > NOW()');
            
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':token', $token);
            
            if($this->db->execute() && $this->db->rowCount() > 0) {
                return true;
            }

            // Try admins table
            $this->db->query('UPDATE admins SET 
                password = :password,
                reset_password_token = NULL,
                reset_password_expires = NULL,
                updated_at = NOW()
                WHERE reset_password_token = :token AND reset_password_expires > NOW()');
            
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':token', $token);
            
            return $this->db->execute() && $this->db->rowCount() > 0;
        } catch (Exception $e) {
            error_log('M_Login: Error resetting password: ' . $e->getMessage());
            return false;
        }
    }
    
    // Update user role in database
    public function updateUserRole($userId, $newRole) {
        try {
            // Validate role
            $validRoles = ['customer', 'stadium_owner', 'coach', 'rental_owner'];
            if (!in_array($newRole, $validRoles)) {
                error_log('M_Login: Invalid role attempted: ' . $newRole);
                return false;
            }
            
            $this->db->query('UPDATE users SET role = :role, updated_at = NOW() WHERE id = :user_id');
            $this->db->bind(':role', $newRole);
            $this->db->bind(':user_id', $userId);
            
            if ($this->db->execute()) {
                error_log('M_Login: Updated user ' . $userId . ' role to ' . $newRole);
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log('M_Login: Error updating user role: ' . $e->getMessage());
            return false;
        }
    }
}