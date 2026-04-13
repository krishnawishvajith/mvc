<?php
class M_Register {
    private $db;

    public function __construct(){
        try {
            $this->db = new Database();
            error_log('M_Register: Database connection successful');
        } catch (Exception $e) {
            error_log('M_Register: Database connection error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Check if email already exists
    public function emailExists($email) {
        try {
            $this->db->query('SELECT id FROM users WHERE email = :email');
            $this->db->bind(':email', $email);
            $this->db->execute();
            
            $result = $this->db->rowCount() > 0;
            error_log('M_Register: Email exists check for ' . $email . ': ' . ($result ? 'true' : 'false'));
            return $result;
        } catch (Exception $e) {
            error_log('M_Register: Email exists check error: ' . $e->getMessage());
            return false;
        }
    }

    // Check if phone number already exists
    public function phoneExists($phone) {
        try {
            $this->db->query('SELECT id FROM users WHERE phone = :phone');
            $this->db->bind(':phone', $phone);
            $this->db->execute();
            
            $result = $this->db->rowCount() > 0;
            error_log('M_Register: Phone exists check for ' . $phone . ': ' . ($result ? 'true' : 'false'));
            return $result;
        } catch (Exception $e) {
            error_log('M_Register: Phone exists check error: ' . $e->getMessage());
            return false;
        }
    }

    // Create a new user account
    public function createUser($userData) {
        try {
            error_log('M_Register: Creating user with data: ' . print_r($userData, true));
            
            $this->db->query('INSERT INTO users (
                first_name, 
                last_name, 
                email, 
                phone, 
                password, 
                role, 
                status, 
                created_at
            ) VALUES (
                :first_name, 
                :last_name, 
                :email, 
                :phone, 
                :password, 
                :role, 
                :status, 
                :created_at
            )');

            // Bind parameters
            $this->db->bind(':first_name', $userData['first_name']);
            $this->db->bind(':last_name', $userData['last_name']);
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':phone', $userData['phone']);
            $this->db->bind(':password', password_hash($userData['password'], PASSWORD_DEFAULT));
            $this->db->bind(':role', $userData['role']);
            $this->db->bind(':status', 'active'); // Set all accounts to active immediately
            $this->db->bind(':created_at', date('Y-m-d H:i:s'));

            // Execute
            if ($this->db->execute()) {
                $userId = $this->db->lastInsertId();
                error_log('M_Register: User created successfully with ID: ' . $userId);
                return $userId;
            } else {
                error_log('M_Register: Failed to execute user creation query');
                return false;
            }
        } catch (Exception $e) {
            error_log('M_Register: Create User Error: ' . $e->getMessage());
            error_log('M_Register: Error trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Create customer profile
    public function createCustomerProfile($userId, $profileData) {
        try {
            error_log('M_Register: Creating customer profile for user ID: ' . $userId);
            
            $this->db->query('INSERT INTO customer_profiles (
                user_id,
                district,
                sports,
                age_group,
                skill_level,
                created_at
            ) VALUES (
                :user_id,
                :district,
                :sports,
                :age_group,
                :skill_level,
                :created_at
            )');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':district', $profileData['district']);
            $this->db->bind(':sports', $profileData['sports']);
            $this->db->bind(':age_group', $profileData['age_group']);
            $this->db->bind(':skill_level', $profileData['skill_level']);
            $this->db->bind(':created_at', date('Y-m-d H:i:s'));

            $result = $this->db->execute();
            error_log('M_Register: Customer profile creation result: ' . ($result ? 'success' : 'failed'));
            return $result;
        } catch (Exception $e) {
            error_log('M_Register: Create Customer Profile Error: ' . $e->getMessage());
            return false;
        }
    }

    // Create stadium owner profile
    public function createStadiumOwnerProfile($userId, $profileData) {
        try {
            error_log('M_Register: Creating stadium owner profile for user ID: ' . $userId);
            
            $this->db->query('INSERT INTO stadium_owner_profiles (
                user_id,
                owner_name,
                business_name,
                district,
                venue_type,
                business_registration,
                created_at
            ) VALUES (
                :user_id,
                :owner_name,
                :business_name,
                :district,
                :venue_type,
                :business_registration,
                :created_at
            )');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':owner_name', $profileData['owner_name']);
            $this->db->bind(':business_name', $profileData['business_name']);
            $this->db->bind(':district', $profileData['district']);
            $this->db->bind(':venue_type', $profileData['venue_type']);
            $this->db->bind(':business_registration', $profileData['business_reg']);
            $this->db->bind(':created_at', date('Y-m-d H:i:s'));

            $result = $this->db->execute();
            error_log('M_Register: Stadium owner profile creation result: ' . ($result ? 'success' : 'failed'));
            return $result;
        } catch (Exception $e) {
            error_log('M_Register: Create Stadium Owner Profile Error: ' . $e->getMessage());
            return false;
        }
    }

    // Create coach profile - FIXED
    public function createCoachProfile($userId, $profileData) {
        try {
            error_log('M_Register: Creating coach profile for user ID: ' . $userId);
            error_log('M_Register: Coach profile data: ' . print_r($profileData, true));
            
            $this->db->query('INSERT INTO coach_profiles (
                user_id,
                specialization,
                experience,
                certification,
                coaching_type,
                district,
                availability,
                created_at
            ) VALUES (
                :user_id,
                :specialization,
                :experience,
                :certification,
                :coaching_type,
                :district,
                :availability,
                :created_at
            )');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':specialization', $profileData['specialization']);
            $this->db->bind(':experience', $profileData['experience']);
            $this->db->bind(':certification', $profileData['certification']);
            $this->db->bind(':coaching_type', $profileData['coaching_type']);
            $this->db->bind(':district', $profileData['district']);
            $this->db->bind(':availability', $profileData['availability']);
            $this->db->bind(':created_at', date('Y-m-d H:i:s'));

            $result = $this->db->execute();
            
            if (!$result) {
                error_log('M_Register: Failed to create coach profile');
            } else {
                error_log('M_Register: Successfully created coach profile');
            }
            
            return $result;
        } catch (Exception $e) {
            error_log('M_Register: Create Coach Profile Error: ' . $e->getMessage());
            error_log('M_Register: Error details: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Create rental owner profile - FIXED
    public function createRentalOwnerProfile($userId, $profileData) {
        try {
            error_log('M_Register: Creating rental owner profile for user ID: ' . $userId);
            error_log('M_Register: Rental owner profile data: ' . print_r($profileData, true));

            $this->db->query('INSERT INTO rental_owner_profiles (
                user_id,
                owner_name,
                business_name,
                district,
                business_type,
                equipment_categories,
                delivery_service,
                created_at
            ) VALUES (
                :user_id,
                :owner_name,
                :business_name,
                :district,
                :business_type,
                :equipment_categories,
                :delivery_service,
                :created_at
            )');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':owner_name', $profileData['owner_name']);
            $this->db->bind(':business_name', $profileData['business_name']);
            $this->db->bind(':district', $profileData['district']);
            $this->db->bind(':business_type', $profileData['business_type']);
            $this->db->bind(':equipment_categories', $profileData['equipment_categories']);
            $this->db->bind(':delivery_service', $profileData['delivery_service']);
            $this->db->bind(':created_at', date('Y-m-d H:i:s'));

            $result = $this->db->execute();
            
            if (!$result) {
                error_log('M_Register: Failed to create rental owner profile');
            } else {
                error_log('M_Register: Successfully created rental owner profile');
            }
            
            return $result;
        } catch (Exception $e) {
            error_log('M_Register: Create Rental Owner Profile Error: ' . $e->getMessage());
            error_log('M_Register: Error details: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Send welcome email (uses Mail class when SMTP configured, else PHP mail())
    public function sendWelcomeEmail($email, $name, $role) {
        $roleLabel = ucfirst(str_replace('_', ' ', $role));
        $subject = 'Welcome to ' . SITENAME . '!';
        $message = "Hello " . trim($name) . ",\n\n";
        $message .= "Thank you for creating your " . $roleLabel . " account on " . SITENAME . ".\n\n";
        $message .= "You can now log in and use your dashboard.\n\n";
        $message .= "— " . SITENAME;

        $fromEmail = (defined('SMTP_FROM_EMAIL') && trim(SMTP_FROM_EMAIL) !== '')
            ? SMTP_FROM_EMAIL
            : (defined('MAIL_FROM') ? MAIL_FROM : 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));

        if (class_exists('Mail') && Mail::isConfigured()) {
            $mail = new Mail();
            return $mail->send($email, $subject, $message, $fromEmail, SITENAME);
        }
        $headers = "From: " . SITENAME . " <" . $fromEmail . ">\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        return @mail($email, $subject, $message, $headers);
    }

    // Delete user (for rollback if profile creation fails)
    public function deleteUser($userId) {
        try {
            $this->db->query('DELETE FROM users WHERE id = :id');
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('M_Register: Delete User Error: ' . $e->getMessage());
            return false;
        }
    }

    // Get all dropdown data methods
    public function getCities() {
        return [
            'Colombo', 'Kandy', 'Galle', 'Jaffna', 'Negombo',
            'Anuradhapura', 'Polonnaruwa', 'Kurunegala', 'Ratnapura',
            'Batticaloa', 'Matara', 'Vavuniya', 'Trincomalee',
            'Kalutara', 'Badulla', 'Gampaha', 'Hambantota', 'Monaragala',
            'Nuwara Eliya', 'Puttalam', 'Kegalle', 'Ampara', 'Kilinochchi',
            'Mannar', 'Mullaitivu'
        ];
    }

    public function getSportsSpecializations() {
        return [
            'football' => 'Football',
            'cricket' => 'Cricket',
            'basketball' => 'Basketball',
            'tennis' => 'Tennis',
            'badminton' => 'Badminton',
            'swimming' => 'Swimming',
            'volleyball' => 'Volleyball',
            'rugby' => 'Rugby',
            'athletics' => 'Athletics',
            'hockey' => 'Hockey',
            'futsal' => 'Futsal',
            'table_tennis' => 'Table Tennis',
            'squash' => 'Squash',
            'boxing' => 'Boxing',
            'martial_arts' => 'Martial Arts',
            'cycling' => 'Cycling',
            'golf' => 'Golf',
            'baseball' => 'Baseball',
            'other' => 'Other sports'
        ];
    }

    public function getAgeGroups() {
        return [
            'under_18' => 'Under 18',
            '18_25' => '18-25 years',
            '26_35' => '26-35 years',
            '36_45' => '36-45 years',
            'above_45' => 'Above 45'
        ];
    }

    public function getSkillLevels() {
        return [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'professional' => 'Professional'
        ];
    }

    public function getVenueTypes() {
        return [
            'stadium' => 'Stadium',
            'indoor_court' => 'Indoor Court',
            'outdoor_court' => 'Outdoor Court',
            'sports_complex' => 'Sports Complex',
            'practice_nets' => 'Practice Nets',
            'swimming_pool' => 'Swimming Pool',
            'gym' => 'Gym/Fitness Center',
            'multi_purpose' => 'Multi-Purpose Hall'
        ];
    }

    public function getBusinessTypes() {
        return [
            'private_stadium' => 'Private Stadium',
            'sports_complex' => 'Sports Complex',
            'community_center' => 'Community Center',
            'school_university' => 'School/University',
            'hotel_resort' => 'Hotel/Resort',
            'government_facility' => 'Government Facility',
            'sports_club' => 'Sports Club',
            'retail_chain' => 'Retail Chain',
            'independent' => 'Independent Store',
            'sports_shop' => 'Sports Shop',
            'equipment_specialist' => 'Equipment Specialist',
            'franchise' => 'Franchise',
            'other' => 'Other'
        ];
    }

    public function getExperienceLevels() {
        return [
            '1_3' => '1-3 years',
            '4_6' => '4-6 years',
            '7_10' => '7-10 years',
            '11_15' => '11-15 years',
            '15_plus' => '15+ years'
        ];
    }

    public function getCertificationLevels() {
        return [
            'none' => 'No Certification',
            'basic' => 'Basic Certification',
            'intermediate' => 'Intermediate Level',
            'advanced' => 'Advanced Level',
            'professional' => 'Professional License',
            'international' => 'International Certification'
        ];
    }

    public function getCoachingTypes() {
        return [
            'individual' => 'Individual Training',
            'group' => 'Group Sessions',
            'team' => 'Team Training',
            'both' => 'Both Individual & Group',
            'all' => 'All Types'
        ];
    }

    public function getAvailabilityOptions() {
        return [
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'weekends' => 'Weekends Only',
            'evenings' => 'Evenings Only',
            'flexible' => 'Flexible Schedule'
        ];
    }

    public function getEquipmentTypes() {
        return [
            'football_equipment' => 'Football Equipment',
            'cricket_equipment' => 'Cricket Equipment',
            'basketball_equipment' => 'Basketball Equipment',
            'tennis_equipment' => 'Tennis Equipment',
            'badminton_equipment' => 'Badminton Equipment',
            'swimming_equipment' => 'Swimming Equipment',
            'volleyball_equipment' => 'Volleyball Equipment',
            'rugby_equipment' => 'Rugby Equipment',
            'athletics_equipment' => 'Athletics Equipment',
            'hockey_equipment' => 'Hockey Equipment',
            'futsal_equipment' => 'Futsal Equipment',
            'table_tennis_equipment' => 'Table Tennis Equipment',
            'gym_equipment' => 'Gym Equipment',
            'safety_gear' => 'Safety Gear',
            'fitness_equipment' => 'Fitness Equipment',
            'outdoor_gear' => 'Outdoor Sports Gear',
            'team_uniforms' => 'Team Uniforms',
            'protective_gear' => 'Protective Equipment',
            'training_equipment' => 'Training Equipment',
            'multi_sport' => 'Multi-Sport Equipment'
        ];
    }

    public function getDeliveryOptions() {
        return [
            'yes' => 'Yes, We Deliver',
            'no' => 'Pickup Only',
            'conditional' => 'Delivery Available for Large Orders',
            'paid' => 'Paid Delivery Service'
        ];
    }
}