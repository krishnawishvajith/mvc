<?php
class M_Admin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Authenticate admin login (now using email instead of username)
    public function authenticateAdmin($email, $password)
    {
        $this->db->query('SELECT * FROM admins WHERE email = :email');
        $this->db->bind(':email', $email);

        $admin = $this->db->single();

        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        }
        return false;
    }

    // Update last login
    public function updateLastLogin($admin_id)
    {
        $this->db->query('UPDATE admins SET last_login = NOW() WHERE id = :id');
        $this->db->bind(':id', $admin_id);

        return $this->db->execute();
    }

    // Get total users count
    public function getTotalUsers()
    {
        $this->db->query('SELECT COUNT(*) as total FROM users');
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    // Get all users

    public function getAllUsers()
    {
        $this->db->query('SELECT u.*, 
        CASE 
            WHEN u.role = "customer" THEN u.first_name
            WHEN u.role = "stadium_owner" THEN COALESCE(NULLIF(sop.business_name, ""), NULLIF(sop.business_name, "Not specified"), u.first_name)
            WHEN u.role = "coach" THEN u.first_name
            WHEN u.role = "rental_owner" THEN COALESCE(NULLIF(rop.business_name, ""), NULLIF(rop.business_name, "Not specified"), u.first_name)
            ELSE u.first_name
        END as display_name
        FROM users u
        LEFT JOIN stadium_owner_profiles sop ON u.id = sop.user_id
        LEFT JOIN rental_owner_profiles rop ON u.id = rop.user_id
        ORDER BY u.created_at DESC');

        return $this->db->resultSet();
    }

    // Get user by ID
    public function getUserById($id)
    {
        $this->db->query('SELECT u.*, 
            CASE 
                WHEN u.role = "customer" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "stadium_owner" THEN COALESCE(sop.business_name, CONCAT(u.first_name, " ", u.last_name))
                WHEN u.role = "coach" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "rental_owner" THEN COALESCE(rop.business_name, CONCAT(u.first_name, " ", u.last_name))
                ELSE CONCAT(u.first_name, " ", u.last_name)
            END as display_name
            FROM users u
            LEFT JOIN stadium_owner_profiles sop ON u.id = sop.user_id
            LEFT JOIN rental_owner_profiles rop ON u.id = rop.user_id
            WHERE u.id = :id');

        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Check if email exists
    public function emailExists($email)
    {
        $this->db->query('SELECT id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->execute();

        return $this->db->rowCount() > 0;
    }

    // Check if email exists for other user (for updates)
    public function emailExistsForOtherUser($email, $userId)
    {
        $this->db->query('SELECT id FROM users WHERE email = :email AND id != :user_id');
        $this->db->bind(':email', $email);
        $this->db->bind(':user_id', $userId);
        $this->db->execute();

        return $this->db->rowCount() > 0;
    }

    // Create a new user
    public function createUser($userData)
    {
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
        $this->db->bind(':status', 'active');
        $this->db->bind(':created_at', date('Y-m-d H:i:s'));

        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Update user
    public function updateUser($userId, $userData)
    {
        $this->db->query('UPDATE users SET
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone = :phone,
            status = :status,
            updated_at = :updated_at
            WHERE id = :id');

        $this->db->bind(':first_name', $userData['first_name']);
        $this->db->bind(':last_name', $userData['last_name']);
        $this->db->bind(':email', $userData['email']);
        $this->db->bind(':phone', $userData['phone']);
        $this->db->bind(':status', $userData['status']);
        $this->db->bind(':updated_at', date('Y-m-d H:i:s'));
        $this->db->bind(':id', $userId);

        return $this->db->execute();
    }

    // Update user password
    public function updateUserPassword($userId, $newPassword)
    {
        $this->db->query('UPDATE users SET
            password = :password,
            updated_at = :updated_at
            WHERE id = :id');

        $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->bind(':updated_at', date('Y-m-d H:i:s'));
        $this->db->bind(':id', $userId);

        return $this->db->execute();
    }

    // Delete user
    public function deleteUser($userId)
    {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);

        return $this->db->execute();
    }

    // Toggle user status
    public function toggleUserStatus($userId)
    {
        $this->db->query('UPDATE users SET 
            status = CASE 
                WHEN status = "active" THEN "inactive" 
                WHEN status = "inactive" THEN "active" 
                ELSE "active" 
            END,
            updated_at = :updated_at
            WHERE id = :id');

        $this->db->bind(':updated_at', date('Y-m-d H:i:s'));
        $this->db->bind(':id', $userId);

        return $this->db->execute();
    }

    // Create customer profile
    public function createCustomerProfile($userId, $profileData)
    {
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

        return $this->db->execute();
    }

    // Create stadium owner profile
    public function createStadiumOwnerProfile($userId, $profileData)
    {
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
        $this->db->bind(':business_registration', $profileData['business_registration']);
        $this->db->bind(':created_at', date('Y-m-d H:i:s'));

        return $this->db->execute();
    }

    // Create coach profile
    public function createCoachProfile($userId, $profileData)
    {
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

        return $this->db->execute();
    }

    // Create rental owner profile
    public function createRentalOwnerProfile($userId, $profileData)
    {
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

        return $this->db->execute();
    }

    // Approve user account
    public function approveUser($user_id)
    {
        $this->db->query('UPDATE users SET status = "active" WHERE id = :id');
        $this->db->bind(':id', $user_id);

        return $this->db->execute();
    }

    // Suspend user account
    public function suspendUser($user_id)
    {
        $this->db->query('UPDATE users SET status = "suspended" WHERE id = :id');
        $this->db->bind(':id', $user_id);

        return $this->db->execute();
    }

    // Get dashboard statistics
    public function getDashboardStats()
    {
        $stats = [];

        // Total users
        $this->db->query('SELECT COUNT(*) as total FROM users');
        $stats['total_users'] = $this->db->single()->total;

        // Pending approvals
        $this->db->query('SELECT COUNT(*) as total FROM users WHERE status = "pending"');
        $stats['pending_approvals'] = $this->db->single()->total;

        // Active stadiums
        $this->db->query('SELECT COUNT(*) as total FROM users WHERE role = "stadium_owner" AND status = "active"');
        $stats['active_stadiums'] = $this->db->single()->total;

        return $stats;
    }

    // Get admin by email
    public function getAdminByEmail($email)
    {
        $this->db->query('SELECT * FROM admins WHERE email = :email');
        $this->db->bind(':email', $email);

        return $this->db->single();
    }

    // Update admin profile
    public function updateAdminProfile($admin_id, $data)
    {
        $this->db->query('UPDATE admins SET 
            full_name = :full_name,
            email = :email,
            updated_at = NOW()
            WHERE id = :id');

        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':id', $admin_id);

        return $this->db->execute();
    }

    // Change admin password
    public function changeAdminPassword($admin_id, $new_password)
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $this->db->query('UPDATE admins SET 
            password = :password,
            updated_at = NOW()
            WHERE id = :id');

        $this->db->bind(':password', $hashed_password);
        $this->db->bind(':id', $admin_id);

        return $this->db->execute();
    }

    // Get users by role
    public function getUsersByRole($role)
    {
        $this->db->query('SELECT u.*, 
            CASE 
                WHEN u.role = "customer" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "stadium_owner" THEN COALESCE(sop.business_name, CONCAT(u.first_name, " ", u.last_name))
                WHEN u.role = "coach" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "rental_owner" THEN COALESCE(rop.business_name, CONCAT(u.first_name, " ", u.last_name))
                ELSE CONCAT(u.first_name, " ", u.last_name)
            END as display_name
            FROM users u
            LEFT JOIN stadium_owner_profiles sop ON u.id = sop.user_id
            LEFT JOIN rental_owner_profiles rop ON u.id = rop.user_id
            WHERE u.role = :role
            ORDER BY u.created_at DESC');

        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    // Get users by status
    public function getUsersByStatus($status)
    {
        $this->db->query('SELECT u.*, 
            CASE 
                WHEN u.role = "customer" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "stadium_owner" THEN COALESCE(sop.business_name, CONCAT(u.first_name, " ", u.last_name))
                WHEN u.role = "coach" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "rental_owner" THEN COALESCE(rop.business_name, CONCAT(u.first_name, " ", u.last_name))
                ELSE CONCAT(u.first_name, " ", u.last_name)
            END as display_name
            FROM users u
            LEFT JOIN stadium_owner_profiles sop ON u.id = sop.user_id
            LEFT JOIN rental_owner_profiles rop ON u.id = rop.user_id
            WHERE u.status = :status
            ORDER BY u.created_at DESC');

        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    // Search users
    public function searchUsers($searchTerm)
    {
        $this->db->query('SELECT u.*, 
            CASE 
                WHEN u.role = "customer" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "stadium_owner" THEN COALESCE(sop.business_name, CONCAT(u.first_name, " ", u.last_name))
                WHEN u.role = "coach" THEN CONCAT(u.first_name, " ", u.last_name)
                WHEN u.role = "rental_owner" THEN COALESCE(rop.business_name, CONCAT(u.first_name, " ", u.last_name))
                ELSE CONCAT(u.first_name, " ", u.last_name)
            END as display_name
            FROM users u
            LEFT JOIN stadium_owner_profiles sop ON u.id = sop.user_id
            LEFT JOIN rental_owner_profiles rop ON u.id = rop.user_id
            WHERE CONCAT(u.first_name, " ", u.last_name) LIKE :search 
            OR u.email LIKE :search
            OR sop.business_name LIKE :search
            OR rop.business_name LIKE :search
            ORDER BY u.created_at DESC');

        $searchTerm = '%' . $searchTerm . '%';
        $this->db->bind(':search', $searchTerm);
        return $this->db->resultSet();
    }

    // Get user counts by role
    public function getUserCountsByRole()
    {
        $this->db->query('SELECT role, COUNT(*) as count FROM users GROUP BY role');
        $results = $this->db->resultSet();

        $counts = [
            'customer' => 0,
            'stadium_owner' => 0,
            'coach' => 0,
            'rental_owner' => 0
        ];

        foreach ($results as $result) {
            $counts[$result->role] = $result->count;
        }

        return $counts;
    }

    // ========== STADIUM PACKAGES MANAGEMENT ==========

    // Get all packages (limit to top 3 active stadium packages)
    public function getAllPackages()
    {
        $this->db->query('SELECT * FROM stadium_packages WHERE is_active = 1 ORDER BY display_order ASC, id ASC LIMIT 3');
        return $this->db->resultSet();
    }

    // Get package by ID
    public function getPackageById($id)
    {
        $this->db->query('SELECT * FROM stadium_packages WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Create new package
    public function createPackage($data)
    {
        $this->db->query('INSERT INTO stadium_packages (
            id, name, description, setup_fee, commission_rate, stadium_limit,
            photos_per_property, videos_per_property, featured_listings, support,
            marketing_tools, advanced_analytics, priority_support, dedicated_manager,
            api_access, icon, color, is_popular, display_order
        ) VALUES (
            :id, :name, :description, :setup_fee, :commission_rate, :stadium_limit,
            :photos_per_property, :videos_per_property, :featured_listings, :support,
            :marketing_tools, :advanced_analytics, :priority_support, :dedicated_manager,
            :api_access, :icon, :color, :is_popular, :display_order
        )');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':setup_fee', $data['setup_fee']);
        $this->db->bind(':commission_rate', $data['commission_rate']);
        $this->db->bind(':stadium_limit', $data['stadium_limit']);
        $this->db->bind(':photos_per_property', $data['photos_per_property']);
        $this->db->bind(':videos_per_property', $data['videos_per_property']);
        $this->db->bind(':featured_listings', $data['featured_listings']);
        $this->db->bind(':support', $data['support']);
        $this->db->bind(':marketing_tools', (int)$data['marketing_tools']);
        $this->db->bind(':advanced_analytics', (int)$data['advanced_analytics']);
        $this->db->bind(':priority_support', (int)$data['priority_support']);
        $this->db->bind(':dedicated_manager', (int)$data['dedicated_manager']);
        $this->db->bind(':api_access', (int)$data['api_access']);
        $this->db->bind(':icon', $data['icon'] ?? '⚡');
        $this->db->bind(':color', $data['color'] ?? 'standard');
        $this->db->bind(':is_popular', (int)$data['is_popular']);
        $this->db->bind(':display_order', $data['id']); // Use ID as display order initially

        return $this->db->execute();
    }

    // Update package
    public function updatePackage($data)
    {
        $this->db->query('UPDATE stadium_packages SET 
            name = :name,
            description = :description,
            setup_fee = :setup_fee,
            commission_rate = :commission_rate,
            stadium_limit = :stadium_limit,
            photos_per_property = :photos_per_property,
            videos_per_property = :videos_per_property,
            featured_listings = :featured_listings,
            support = :support,
            marketing_tools = :marketing_tools,
            advanced_analytics = :advanced_analytics,
            priority_support = :priority_support,
            dedicated_manager = :dedicated_manager,
            api_access = :api_access,
            icon = :icon,
            color = :color,
            is_popular = :is_popular,
            updated_at = NOW()
            WHERE id = :id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':setup_fee', $data['setup_fee']);
        $this->db->bind(':commission_rate', $data['commission_rate']);
        $this->db->bind(':stadium_limit', $data['stadium_limit']);
        $this->db->bind(':photos_per_property', $data['photos_per_property']);
        $this->db->bind(':videos_per_property', $data['videos_per_property']);
        $this->db->bind(':featured_listings', $data['featured_listings']);
        $this->db->bind(':support', $data['support']);
        $this->db->bind(':marketing_tools', (int)$data['marketing_tools']);
        $this->db->bind(':advanced_analytics', (int)$data['advanced_analytics']);
        $this->db->bind(':priority_support', (int)$data['priority_support']);
        $this->db->bind(':dedicated_manager', (int)$data['dedicated_manager']);
        $this->db->bind(':api_access', (int)$data['api_access']);
        $this->db->bind(':icon', $data['icon'] ?? '⚡');
        $this->db->bind(':color', $data['color'] ?? 'standard');
        $this->db->bind(':is_popular', (int)$data['is_popular']);

        return $this->db->execute();
    }

    // ========== PACKAGE PURCHASES MANAGEMENT ==========

    // Create new package purchase record
    public function createPackagePurchase($data)
    {
        $this->db->query('INSERT INTO package_purchases (
            user_id,
            package_id,
            payment_amount,
            payment_method,
            stripe_charge_id,
            package_status,
            purchased_at
        ) VALUES (
            :user_id,
            :package_id,
            :payment_amount,
            :payment_method,
            :stripe_charge_id,
            :package_status,
            NOW()
        )');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':package_id', $data['package_id']);
        $this->db->bind(':payment_amount', $data['payment_amount']);
        $this->db->bind(':payment_method', $data['payment_method'] ?? 'stripe');
        $this->db->bind(':stripe_charge_id', $data['stripe_charge_id'] ?? null);
        $this->db->bind(':package_status', $data['package_status'] ?? 'pending');

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Get all package purchases with user details
    public function getAllPackagePurchases()
    {
        $this->db->query('SELECT 
            pp.*,
            u.first_name,
            u.last_name,
            u.email,
            u.phone,
            u.status as user_status,
            sp.name as package_name,
            sp.icon as package_icon,
            sp.color as package_color,
            sop.business_name,
            sop.district,
            sop.venue_type
            FROM package_purchases pp
            JOIN users u ON pp.user_id = u.id
            JOIN stadium_packages sp ON pp.package_id = sp.id
            LEFT JOIN stadium_owner_profiles sop ON u.id = sop.user_id
            ORDER BY pp.purchased_at DESC');

        return $this->db->resultSet();
    }

    // Get package purchase by ID
    public function getPackagePurchaseById($id)
    {
        $this->db->query('SELECT 
            pp.*,
            u.first_name,
            u.last_name,
            u.email,
            u.phone,
            sp.name as package_name
            FROM package_purchases pp
            JOIN users u ON pp.user_id = u.id
            JOIN stadium_packages sp ON pp.package_id = sp.id
            WHERE pp.id = :id');

        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update package purchase status
    public function updatePackagePurchaseStatus($purchaseId, $status, $adminNotes = null)
    {
        $this->db->query('UPDATE package_purchases SET 
            package_status = :status,
            admin_notes = :admin_notes,
            activated_at = CASE WHEN :status = "active" AND activated_at IS NULL THEN NOW() ELSE activated_at END,
            updated_at = NOW()
            WHERE id = :id');

        $this->db->bind(':id', $purchaseId);
        $this->db->bind(':status', $status);
        $this->db->bind(':admin_notes', $adminNotes);

        return $this->db->execute();
    }

    // Get purchases by status
    public function getPackagePurchasesByStatus($status)
    {
        $this->db->query('SELECT 
            pp.*,
            u.first_name,
            u.last_name,
            u.email,
            sp.name as package_name,
            sp.icon as package_icon
            FROM package_purchases pp
            JOIN users u ON pp.user_id = u.id
            JOIN stadium_packages sp ON pp.package_id = sp.id
            WHERE pp.package_status = :status
            ORDER BY pp.purchased_at DESC');

        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    // Get purchase counts by status
    public function getPackagePurchaseCounts()
    {
        $this->db->query('SELECT 
            package_status, 
            COUNT(*) as count 
            FROM package_purchases 
            GROUP BY package_status');

        $results = $this->db->resultSet();

        $counts = [
            'pending' => 0,
            'active' => 0,
            'suspended' => 0,
            'expired' => 0,
            'failed' => 0,
            'total' => 0
        ];

        foreach ($results as $result) {
            $counts[$result->package_status] = $result->count;
            $counts['total'] += $result->count;
        }

        return $counts;
    }

    // ========== RENTAL SERVICE PACKAGES MANAGEMENT ==========

    // Get all rental packages
    public function getAllRentalPackages()
    {
        $this->db->query('SELECT * FROM rental_service_packages WHERE is_active = 1 ORDER BY display_order ASC');
        return $this->db->resultSet();
    }

    // Get rental package by ID
    public function getRentalPackageById($id)
    {
        $this->db->query('SELECT * FROM rental_service_packages WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update rental package
    public function updateRentalPackage($data)
    {
        $this->db->query('UPDATE rental_service_packages SET 
            name = :name,
            description = :description,
            price = :price,
            duration_text = :duration_text,
            shop_listings = :shop_listings,
            images_per_listing = :images_per_listing,
            phone_contact = :phone_contact,
            email_contact = :email_contact,
            amenities_display = :amenities_display,
            priority_placement = :priority_placement,
            email_phone_support = :email_phone_support,
            icon = :icon,
            color = :color,
            is_popular = :is_popular,
            updated_at = NOW()
            WHERE id = :id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':duration_text', $data['duration_text']);
        $this->db->bind(':shop_listings', $data['shop_listings']);
        $this->db->bind(':images_per_listing', $data['images_per_listing']);
        $this->db->bind(':phone_contact', (int)$data['phone_contact']);
        $this->db->bind(':email_contact', (int)$data['email_contact']);
        $this->db->bind(':amenities_display', (int)$data['amenities_display']);
        $this->db->bind(':priority_placement', (int)$data['priority_placement']);
        $this->db->bind(':email_phone_support', (int)$data['email_phone_support']);
        $this->db->bind(':icon', $data['icon'] ?? '⚡');
        $this->db->bind(':color', $data['color'] ?? 'standard');
        $this->db->bind(':is_popular', (int)$data['is_popular']);

        return $this->db->execute();
    }

    // ========== RENTAL PACKAGE PURCHASES MANAGEMENT ==========

    // Create new rental package purchase record
    public function createRentalPackagePurchase($data)
    {
        $this->db->query('INSERT INTO rental_package_purchases (
            user_id,
            package_id,
            payment_amount,
            payment_method,
            stripe_charge_id,
            package_status,
            purchased_at
        ) VALUES (
            :user_id,
            :package_id,
            :payment_amount,
            :payment_method,
            :stripe_charge_id,
            :package_status,
            NOW()
        )');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':package_id', $data['package_id']);
        $this->db->bind(':payment_amount', $data['payment_amount']);
        $this->db->bind(':payment_method', $data['payment_method'] ?? 'stripe');
        $this->db->bind(':stripe_charge_id', $data['stripe_charge_id'] ?? null);
        $this->db->bind(':package_status', $data['package_status'] ?? 'pending');

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Get all rental package purchases with user details
    public function getAllRentalPackagePurchases()
    {
        $this->db->query('SELECT 
            rpp.*,
            u.first_name,
            u.last_name,
            u.email,
            u.phone,
            u.status as user_status,
            rsp.name as package_name,
            rsp.icon as package_icon,
            rsp.color as package_color,
            rop.business_name,
            rop.district,
            rop.business_type
            FROM rental_package_purchases rpp
            JOIN users u ON rpp.user_id = u.id
            JOIN rental_service_packages rsp ON rpp.package_id = rsp.id
            LEFT JOIN rental_owner_profiles rop ON u.id = rop.user_id
            ORDER BY rpp.purchased_at DESC');

        return $this->db->resultSet();
    }

    // Update rental package purchase status
    public function updateRentalPackagePurchaseStatus($purchaseId, $status, $adminNotes = null)
    {
        $this->db->query('UPDATE rental_package_purchases SET 
            package_status = :status,
            admin_notes = :admin_notes,
            activated_at = CASE WHEN :status = "active" AND activated_at IS NULL THEN NOW() ELSE activated_at END,
            updated_at = NOW()
            WHERE id = :id');

        $this->db->bind(':id', $purchaseId);
        $this->db->bind(':status', $status);
        $this->db->bind(':admin_notes', $adminNotes);

        return $this->db->execute();
    }

    // Get rental purchase counts by status
    public function getRentalPackagePurchaseCounts()
    {
        $this->db->query('SELECT 
            package_status, 
            COUNT(*) as count 
            FROM rental_package_purchases 
            GROUP BY package_status');

        $results = $this->db->resultSet();

        $counts = [
            'pending' => 0,
            'active' => 0,
            'suspended' => 0,
            'expired' => 0,
            'failed' => 0,
            'total' => 0
        ];

        foreach ($results as $result) {
            $counts[$result->package_status] = $result->count;
            $counts['total'] += $result->count;
        }

        return $counts;
    }

    // ==================== RENTAL SHOP APPROVAL METHODS ====================

    // Get all pending rental shops for admin review
    public function getPendingRentalShops()
    {
        $this->db->query('SELECT rs.*, 
            u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone,
            (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id AND is_primary = 1 LIMIT 1) as primary_image,
            (SELECT COUNT(*) FROM rental_shop_images WHERE shop_id = rs.id) as image_count
            FROM rental_shops rs
            LEFT JOIN users u ON rs.owner_id = u.id
            WHERE rs.status = "pending"
            ORDER BY rs.created_at DESC');

        return $this->db->resultSet();
    }

    // Get all rental shops (all statuses) for admin view
    public function getAllRentalShops($status = null)
    {
        if ($status) {
            $this->db->query('SELECT rs.*, 
                u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone,
                (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id AND is_primary = 1 LIMIT 1) as primary_image,
                (SELECT COUNT(*) FROM rental_shop_images WHERE shop_id = rs.id) as image_count
                FROM rental_shops rs
                LEFT JOIN users u ON rs.owner_id = u.id
                WHERE rs.status = :status
                ORDER BY rs.created_at DESC');
            $this->db->bind(':status', $status);
        } else {
            $this->db->query('SELECT rs.*, 
                u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone,
                (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id AND is_primary = 1 LIMIT 1) as primary_image,
                (SELECT COUNT(*) FROM rental_shop_images WHERE shop_id = rs.id) as image_count
                FROM rental_shops rs
                LEFT JOIN users u ON rs.owner_id = u.id
                ORDER BY rs.created_at DESC');
        }

        return $this->db->resultSet();
    }

    // Get rental shop counts by status
    public function getRentalShopCounts()
    {
        $this->db->query('SELECT status, COUNT(*) as count FROM rental_shops GROUP BY status');
        $results = $this->db->resultSet();

        $counts = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total' => 0
        ];

        foreach ($results as $result) {
            if (isset($counts[$result->status])) {
                $counts[$result->status] = $result->count;
            }
            $counts['total'] += $result->count;
        }

        return $counts;
    }

    // Approve a rental shop
    public function approveRentalShop($shopId, $adminId)
    {
        $this->db->query('UPDATE rental_shops SET 
            status = "approved",
            approved_by = :admin_id,
            approved_at = NOW(),
            updated_at = NOW()
            WHERE id = :shop_id');

        $this->db->bind(':shop_id', $shopId);
        $this->db->bind(':admin_id', $adminId);

        return $this->db->execute();
    }

    // Reject a rental shop
    public function rejectRentalShop($shopId, $adminId, $reason = null)
    {
        $this->db->query('UPDATE rental_shops SET 
            status = "rejected",
            approved_by = :admin_id,
            approved_at = NOW(),
            rejection_reason = :reason,
            updated_at = NOW()
            WHERE id = :shop_id');

        $this->db->bind(':shop_id', $shopId);
        $this->db->bind(':admin_id', $adminId);
        $this->db->bind(':reason', $reason);

        return $this->db->execute();
    }

    // Get rental shop details by ID (for admin view)
    public function getRentalShopById($shopId)
    {
        $this->db->query('SELECT rs.*, 
            u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone,
            (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM rental_shops rs
            LEFT JOIN users u ON rs.owner_id = u.id
            WHERE rs.id = :shop_id');

        $this->db->bind(':shop_id', $shopId);
        $shop = $this->db->single();

        if ($shop) {
            // Get all images
            $this->db->query('SELECT * FROM rental_shop_images WHERE shop_id = :shop_id ORDER BY is_primary DESC, id ASC');
            $this->db->bind(':shop_id', $shopId);
            $shop->images = $this->db->resultSet();

            // Get equipment types
            $this->db->query('SELECT equipment_type FROM rental_equipment_types WHERE rental_id = :shop_id');
            $this->db->bind(':shop_id', $shopId);
            $types = $this->db->resultSet();
            $shop->equipment_types = array_column($types, 'equipment_type');

            // Get features
            $this->db->query('SELECT feature_name FROM rental_features WHERE rental_id = :shop_id');
            $this->db->bind(':shop_id', $shopId);
            $features = $this->db->resultSet();
            $shop->features = array_column($features, 'feature_name');
        }

        return $shop;
    }

    // ========== STADIUM APPROVAL METHODS ==========

    // Get all stadiums with filters
    public function getStadiumListings($filter = 'all')
    {
        $whereClause = '';
        if ($filter !== 'all') {
            $whereClause = "WHERE s.approval_status = :filter";
        }

        $this->db->query("SELECT s.*, 
            u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone,
            (SELECT image_path FROM stadium_images WHERE stadium_id = s.id AND is_primary = 1 LIMIT 1) as primary_image,
            (SELECT COUNT(*) FROM stadium_images WHERE stadium_id = s.id) as image_count
            FROM stadiums s
            LEFT JOIN users u ON s.owner_id = u.id
            $whereClause
            ORDER BY 
                CASE 
                    WHEN s.approval_status = 'pending' THEN 1
                    WHEN s.approval_status = 'approved' THEN 2
                    WHEN s.approval_status = 'rejected' THEN 3
                END,
                s.created_at DESC");

        if ($filter !== 'all') {
            $this->db->bind(':filter', $filter);
        }

        return $this->db->resultSet();
    }

    // Get stadium statistics by approval status
    public function getStadiumCounts()
    {
        $this->db->query('SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN approval_status = "pending" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN approval_status = "approved" THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN approval_status = "rejected" THEN 1 ELSE 0 END) as rejected
            FROM stadiums');

        $result = $this->db->single();

        return [
            'total' => $result->total ?? 0,
            'pending' => $result->pending ?? 0,
            'approved' => $result->approved ?? 0,
            'rejected' => $result->rejected ?? 0
        ];
    }

    // Approve a stadium
    public function approveStadium($stadiumId, $adminId)
    {
        try {
            // If adminId is null or invalid, just don't set it
            if ($adminId) {
                $this->db->query('UPDATE stadiums SET 
                    approval_status = "approved",
                    approved_by = :admin_id,
                    approved_at = NOW()
                    WHERE id = :stadium_id');

                $this->db->bind(':stadium_id', $stadiumId);
                $this->db->bind(':admin_id', $adminId);
            } else {
                // Don't set approved_by if admin_id is not available
                $this->db->query('UPDATE stadiums SET 
                    approval_status = "approved",
                    approved_at = NOW()
                    WHERE id = :stadium_id');

                $this->db->bind(':stadium_id', $stadiumId);
            }

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in approveStadium: ' . $e->getMessage());
            throw $e;
        }
    }

    // Reject a stadium
    public function rejectStadium($stadiumId, $adminId, $reason = null)
    {
        try {
            // If adminId is null or invalid, just don't set it
            if ($adminId) {
                $this->db->query('UPDATE stadiums SET 
                    approval_status = "rejected",
                    approved_by = :admin_id,
                    approved_at = NOW(),
                    rejection_reason = :reason
                    WHERE id = :stadium_id');

                $this->db->bind(':stadium_id', $stadiumId);
                $this->db->bind(':admin_id', $adminId);
                $this->db->bind(':reason', $reason);
            } else {
                // Don't set approved_by if admin_id is not available
                $this->db->query('UPDATE stadiums SET 
                    approval_status = "rejected",
                    approved_at = NOW(),
                    rejection_reason = :reason
                    WHERE id = :stadium_id');

                $this->db->bind(':stadium_id', $stadiumId);
                $this->db->bind(':reason', $reason);
            }

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in rejectStadium: ' . $e->getMessage());
            throw $e;
        }
    }

    // Get stadium details by ID (for admin view)
    public function getStadiumById($stadiumId)
    {
        $this->db->query('SELECT s.*, 
            u.first_name, u.last_name, u.email as owner_email, u.phone as owner_phone,
            (SELECT image_path FROM stadium_images WHERE stadium_id = s.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM stadiums s
            LEFT JOIN users u ON s.owner_id = u.id
            WHERE s.id = :stadium_id');

        $this->db->bind(':stadium_id', $stadiumId);
        $stadium = $this->db->single();

        if ($stadium) {
            // Get all images
            $this->db->query('SELECT * FROM stadium_images WHERE stadium_id = :stadium_id ORDER BY is_primary DESC, display_order ASC');
            $this->db->bind(':stadium_id', $stadiumId);
            $stadium->images = $this->db->resultSet();

            // Get features
            $this->db->query('SELECT feature_name FROM stadium_features WHERE stadium_id = :stadium_id');
            $this->db->bind(':stadium_id', $stadiumId);
            $features = $this->db->resultSet();
            $stadium->features = array_column($features, 'feature_name');
        }

        return $stadium;
    }

    // Get all bookings with complete details
    public function getAllBookings($limit = null, $offset = 0)
    {
        $query = 'SELECT 
            b.id,
            b.booking_date,
            b.start_time,
            b.end_time,
            b.duration_hours,
            b.total_price,
            b.status,
            b.payment_status,
            s.name as stadium_name,
            s.location,
            u.first_name as customer_first_name,
            u.last_name as customer_last_name,
            u.email as customer_email,
            u.phone as customer_phone
        FROM bookings b
        JOIN stadiums s ON b.stadium_id = s.id
        JOIN users u ON b.customer_id = u.id
        ORDER BY b.booking_date DESC, b.created_at DESC';

        if ($limit !== null) {
            $query .= ' LIMIT :limit OFFSET :offset';
        }

        $this->db->query($query);

        if ($limit !== null) {
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
        }

        return $this->db->resultSet();
    }

    // Get booking count by status
    public function getBookingCountByStatus()
    {
        $this->db->query('SELECT 
            status,
            COUNT(*) as count
        FROM bookings
        GROUP BY status');

        return $this->db->resultSet();
    }

    // Get total bookings count
    public function getTotalBookingsCount()
    {
        $this->db->query('SELECT COUNT(*) as total FROM bookings');
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    // Get total revenue
    public function getTotalRevenue()
    {
        $this->db->query('SELECT SUM(total_price) as revenue FROM bookings WHERE status IN ("confirmed", "completed")');
        $result = $this->db->single();
        return $result ? $result->revenue : 0;
    }

    // ========== REFUND REQUEST MANAGEMENT METHODS ==========

    public function getAllRefundRequests($limit = null, $offset = 0)
    {
        $query = 'SELECT rr.*, 
                         b.booking_date, b.total_price as original_amount,
                         s.name as stadium_name, s.location,
                         u.first_name, u.last_name, u.email, u.phone,
                         admin.first_name as processed_by_first, admin.last_name as processed_by_last
                  FROM refund_requests rr
                  JOIN bookings b ON rr.booking_id = b.id
                  JOIN stadiums s ON b.stadium_id = s.id
                  JOIN users u ON rr.customer_id = u.id
                  LEFT JOIN users admin ON rr.refund_processed_by = admin.id
                  ORDER BY rr.created_at DESC';

        if ($limit !== null) {
            $query .= ' LIMIT :limit OFFSET :offset';
        }

        $this->db->query($query);

        if ($limit !== null) {
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
        }

        return $this->db->resultSet();
    }

    public function getRefundRequestById($refund_id)
    {
        $this->db->query('SELECT rr.*, 
                                 b.booking_date, b.total_price as original_amount,
                                 s.name as stadium_name, s.location,
                                 u.first_name, u.last_name, u.email, u.phone,
                                 admin.first_name as processed_by_first, admin.last_name as processed_by_last
                          FROM refund_requests rr
                          JOIN bookings b ON rr.booking_id = b.id
                          JOIN stadiums s ON b.stadium_id = s.id
                          JOIN users u ON rr.customer_id = u.id
                          LEFT JOIN users admin ON rr.refund_processed_by = admin.id
                          WHERE rr.id = :rid');
        $this->db->bind(':rid', $refund_id);
        return $this->db->single();
    }

    public function getRefundRequestsByStatus($status, $limit = null, $offset = 0)
    {
        $query = 'SELECT rr.*, 
                         b.booking_date, b.total_price as original_amount,
                         s.name as stadium_name,
                         u.first_name, u.last_name, u.email
                  FROM refund_requests rr
                  JOIN bookings b ON rr.booking_id = b.id
                  JOIN stadiums s ON b.stadium_id = s.id
                  JOIN users u ON rr.customer_id = u.id
                  WHERE rr.status = :status
                  ORDER BY rr.created_at DESC';

        if ($limit !== null) {
            $query .= ' LIMIT :limit OFFSET :offset';
        }

        $this->db->query($query);
        $this->db->bind(':status', $status);

        if ($limit !== null) {
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
        }

        return $this->db->resultSet();
    }

    public function updateRefundRequestStatus($refund_id, $status, $admin_id, $notes = null, $payment_slip = null)
    {
        try {
            $this->db->beginTransaction();

            // 1. Update refund request
            $this->db->query('UPDATE refund_requests 
                              SET status = :status,
                                  refund_processed_by = :admin_id,
                                  refund_processed_date = NOW(),
                                  admin_notes = :notes,
                                  payment_slip = :slip,
                                  updated_at = NOW()
                              WHERE id = :rid');
            $this->db->bind(':rid', $refund_id);
            $this->db->bind(':status', $status);
            $this->db->bind(':admin_id', $admin_id);
            $this->db->bind(':notes', $notes);
            $this->db->bind(':slip', $payment_slip);
            $this->db->execute();

            // 2. If status is 'refunded', update the booking payment_status
            if ($status === 'refunded') {
                // Get booking_id first
                $this->db->query('SELECT booking_id FROM refund_requests WHERE id = :rid');
                $this->db->bind(':rid', $refund_id);
                $refund = $this->db->single();

                if ($refund) {
                    $this->db->query('UPDATE bookings SET payment_status = "refunded" WHERE id = :bid');
                    $this->db->bind(':bid', $refund->booking_id);
                    $this->db->execute();
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error in updateRefundRequestStatus: ' . $e->getMessage());
            return false;
        }
    }

    public function getRefundRequestCounts()
    {
        $this->db->query('SELECT 
                            status, 
                            COUNT(*) as count 
                          FROM refund_requests 
                          GROUP BY status');

        $results = $this->db->resultSet();
        $counts = [
            'pending' => 0,
            'refunded' => 0,
            'rejected' => 0,
            'cancelled' => 0,
            'total' => 0
        ];

        foreach ($results as $result) {
            if (isset($counts[$result->status])) {
                $counts[$result->status] = $result->count;
            }
            $counts['total'] += $result->count;
        }

        return $counts;
    }

    public function getTotalRefundAmount()
    {
        $this->db->query('SELECT COALESCE(SUM(refund_amount), 0) as total 
                          FROM refund_requests 
                          WHERE status = "refunded"');
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    public function getPendingRefundsAmount()
    {
        $this->db->query('SELECT COALESCE(SUM(refund_amount), 0) as total 
                          FROM refund_requests 
                          WHERE status = "pending"');
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }
}
