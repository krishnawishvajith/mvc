<?php
class Register extends Controller {
    private $registerModel;

    public function __construct()
    {
        // Ensure session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        try {
            $this->registerModel = $this->model('M_Register');
            error_log('Register Controller: Model loaded successfully');
        } catch (Exception $e) {
            error_log('Register Controller Constructor Error: ' . $e->getMessage());
            die('Error loading registration model: ' . $e->getMessage());
        }
    }

    public function index() {
        // Default signup page - show role selection
        $data = [
            'title' => 'Join BookMyGround - Choose Your Role',
            'page_type' => 'role_selection'
        ];

        $this->view('signup/v_signup', $data);
    }

    public function customer() {
        try {
            // Customer registration form
            $data = [
                'title' => 'Sign Up as Customer - BookMyGround',
                'role' => 'customer',
                'role_title' => 'Sports Player',
                'role_description' => 'Book venues, join sessions, and connect with coaches',
                'error' => '',
                'success' => '',
                'cities' => $this->registerModel->getCities(),
                'sports' => $this->registerModel->getSportsSpecializations(),
                'age_groups' => $this->registerModel->getAgeGroups(),
                'skill_levels' => $this->registerModel->getSkillLevels()
            ];

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = $this->processRegistration($data, 'customer');
            }

            $this->view('signup/v_signupcustomer', $data);
        } catch (Exception $e) {
            error_log('Customer Registration Error: ' . $e->getMessage());
            die('Customer registration error: ' . $e->getMessage());
        }
    }

    public function stadium_owner() {
        try {
            // Stadium Owner registration form
            $data = [
                'title' => 'Sign Up as Stadium Owner - BookMyGround',
                'role' => 'stadium_owner',
                'role_title' => 'Stadium Owner',
                'role_description' => 'List your facilities and manage bookings efficiently',
                'error' => '',
                'success' => '',
                'cities' => $this->registerModel->getCities(),
                'venue_types' => $this->registerModel->getVenueTypes(),
                'business_types' => $this->registerModel->getBusinessTypes()
            ];

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                error_log('Stadium Owner POST data: ' . print_r($_POST, true));
                $data = $this->processRegistration($data, 'stadium_owner');
            }

            $this->view('signup/v_signupowner', $data);
        } catch (Exception $e) {
            error_log('Stadium Owner Registration Error: ' . $e->getMessage());
            die('Stadium owner registration error: ' . $e->getMessage());
        }
    }

    public function coach() {
        try {
            // Coach registration form
            $data = [
                'title' => 'Sign Up as Coach - BookMyGround',
                'role' => 'coach',
                'role_title' => 'Sports Coach',
                'role_description' => 'Share your expertise and grow your client base',
                'error' => '',
                'success' => '',
                'cities' => $this->registerModel->getCities(),
                'sports' => $this->registerModel->getSportsSpecializations(),
                'experience_levels' => $this->registerModel->getExperienceLevels(),
                'certification_levels' => $this->registerModel->getCertificationLevels(),
                'coaching_types' => $this->registerModel->getCoachingTypes(),
                'availability_options' => $this->registerModel->getAvailabilityOptions()
            ];

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                error_log('Coach registration POST received');
                $data = $this->processRegistration($data, 'coach');
            }

            $this->view('signup/v_signupcoach', $data);
        } catch (Exception $e) {
            error_log('Coach Registration Error: ' . $e->getMessage());
            die('Coach registration error: ' . $e->getMessage());
        }
    }

    public function rental_owner() {
        try {
            // Rental Owner registration form
            $data = [
                'title' => 'Sign Up as Equipment Rental Owner - BookMyGround',
                'role' => 'rental_owner',
                'role_title' => 'Equipment Rental Service',
                'role_description' => 'Offer sports gear and expand your rental business',
                'error' => '',
                'success' => '',
                'cities' => $this->registerModel->getCities(),
                'business_types' => $this->registerModel->getBusinessTypes(),
                'equipment_types' => $this->registerModel->getEquipmentTypes(),
                'delivery_options' => $this->registerModel->getDeliveryOptions()
            ];

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = $this->processRegistration($data, 'rental_owner');
            }

            $this->view('signup/v_signuprental', $data);
        } catch (Exception $e) {
            error_log('Rental Owner Registration Error: ' . $e->getMessage());
            die('Rental owner registration error: ' . $e->getMessage());
        }
    }

    private function processRegistration($data, $role) {
        try {
            error_log('Processing registration for role: ' . $role);
            error_log('POST data received: ' . print_r($_POST, true));
            
            // Get form data based on role
            $formData = [
                'role' => $role
            ];

            // Get basic fields differently for each role
            switch($role) {
                case 'customer':
                    $formData['first_name'] = trim($_POST['first_name'] ?? $_POST['first-name'] ?? '');
                    $formData['last_name'] = trim($_POST['last_name'] ?? $_POST['last-name'] ?? '');
                    break;
                    
                case 'stadium_owner':
                    // For stadium owner, we get owner_name and split it into first_name/last_name
                    $ownerName = trim($_POST['owner_name'] ?? $_POST['owner-name'] ?? '');
                    $nameParts = explode(' ', $ownerName, 2);
                    $formData['first_name'] = $nameParts[0] ?? '';
                    // FIXED: Ensure last_name is never empty
                    $formData['last_name'] = !empty($nameParts[1]) ? $nameParts[1] : $nameParts[0];
                    $formData['owner_name'] = $ownerName; // Keep for profile creation
                    break;
                    
                case 'coach':
                    $formData['first_name'] = trim($_POST['first_name'] ?? $_POST['first-name'] ?? '');
                    $formData['last_name'] = trim($_POST['last_name'] ?? $_POST['last-name'] ?? '');
                    break;
                    
                case 'rental_owner':
                    // For rental owner, we get owner_name and split it into first_name/last_name
                    $ownerName = trim($_POST['owner_name'] ?? $_POST['owner-name'] ?? '');
                    $nameParts = explode(' ', $ownerName, 2);
                    $formData['first_name'] = $nameParts[0] ?? '';
                    // FIXED: Ensure last_name is never empty
                    $formData['last_name'] = !empty($nameParts[1]) ? $nameParts[1] : $nameParts[0];
                    $formData['owner_name'] = $ownerName; // Keep for profile creation
                    break;
            }

            // Common fields for all roles
            $formData['email'] = trim($_POST['email'] ?? '');
            $formData['phone'] = trim($_POST['phone'] ?? '');
            $formData['password'] = $_POST['password'] ?? '';
            $formData['confirm_password'] = $_POST['confirm_password'] ?? $_POST['confirm-password'] ?? '';

            // Add role-specific fields
            switch($role) {
                case 'customer':
                    $formData['district'] = trim($_POST['district'] ?? '');
                    $formData['sports'] = $_POST['sports'] ?? '';
                    $formData['age_group'] = $_POST['age_group'] ?? $_POST['age-group'] ?? '';
                    $formData['skill_level'] = $_POST['skill_level'] ?? $_POST['skill-level'] ?? '';
                    break;

                case 'stadium_owner':
                    $formData['business_name'] = trim($_POST['business_name'] ?? $_POST['business-name'] ?? '');
                    $formData['district'] = trim($_POST['district'] ?? '');
                    $formData['venue_type'] = $_POST['venue_type'] ?? $_POST['venue-type'] ?? '';
                    $formData['business_reg'] = trim($_POST['business_reg'] ?? $_POST['business-reg'] ?? '');
                    break;

                case 'coach':
                    $formData['specialization'] = $_POST['specialization'] ?? '';
                    $formData['experience'] = $_POST['experience'] ?? '';
                    $formData['certification'] = $_POST['certification'] ?? '';
                    $formData['coaching_type'] = $_POST['coaching_type'] ?? $_POST['coaching-type'] ?? '';
                    $formData['district'] = trim($_POST['district'] ?? '');
                    $formData['availability'] = $_POST['availability'] ?? '';
                    break;

                case 'rental_owner':
                    $formData['business_name'] = trim($_POST['business_name'] ?? $_POST['business-name'] ?? '');
                    $formData['district'] = trim($_POST['district'] ?? '');
                    $formData['business_type'] = $_POST['business_type'] ?? $_POST['business-type'] ?? '';
                    $formData['equipment_categories'] = $_POST['equipment_categories'] ?? $_POST['equipment-categories'] ?? '';
                    $formData['delivery_service'] = $_POST['delivery_service'] ?? $_POST['delivery-service'] ?? '';
                    break;
            }

            error_log('Form data prepared: ' . print_r($formData, true));

            // Validate form data
            $validation = $this->validateForm($formData, $role);
            
            if (!empty($validation['errors'])) {
                $data['error'] = implode('<br>', $validation['errors']);
                $data['form_data'] = $formData; // Keep form data to repopulate
                return $data;
            }

            // Check if email already exists
            if ($this->registerModel->emailExists($formData['email'])) {
                $data['error'] = 'An account with this email already exists.';
                $data['form_data'] = $formData;
                return $data;
            }

            // Check if phone already exists
            if ($this->registerModel->phoneExists($formData['phone'])) {
                $data['error'] = 'An account with this phone number already exists.';
                $data['form_data'] = $formData;
                return $data;
            }

            // Create user account
            error_log('Creating user account...');
            $userId = $this->registerModel->createUser($formData);
            error_log('User created with ID: ' . $userId);
            
            if ($userId) {
                // Create role-specific profile
                $profileCreated = false;
                
                switch($role) {
                    case 'customer':
                        error_log('Creating customer profile...');
                        $profileCreated = $this->registerModel->createCustomerProfile($userId, $formData);
                        break;
                    case 'stadium_owner':
                        error_log('Creating stadium owner profile...');
                        $profileCreated = $this->registerModel->createStadiumOwnerProfile($userId, $formData);
                        break;
                    case 'coach':
                        error_log('Creating coach profile...');
                        $profileCreated = $this->registerModel->createCoachProfile($userId, $formData);
                        break;
                    case 'rental_owner':
                        error_log('Creating rental owner profile...');
                        $profileCreated = $this->registerModel->createRentalOwnerProfile($userId, $formData);
                        break;
                }

                error_log('Profile created: ' . ($profileCreated ? 'Yes' : 'No'));

                if ($profileCreated) {
                    // Send welcome email (optional)
                    $this->registerModel->sendWelcomeEmail($formData['email'], $formData['first_name'], $role);
                    
                    // Check for redirect_to parameter (e.g., from checkout)
                    $redirectTo = trim($_POST['redirect_to'] ?? '');
                    if (!empty($redirectTo) && strpos($redirectTo, URLROOT) === 0) {
                        // Auto-login the user and redirect to checkout
                        $_SESSION['user_logged_in'] = true;
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_email'] = $formData['email'];
                        $_SESSION['user_name'] = $formData['first_name'] . ' ' . $formData['last_name'];
                        $_SESSION['user_role'] = $role;
                        $_SESSION['user_first_name'] = $formData['first_name'];
                        $_SESSION['user_last_name'] = $formData['last_name'];
                        $_SESSION['user_status'] = 'active';
                        
                        header('Location: ' . $redirectTo);
                        exit;
                    }
                    
                    // Redirect to success page
                    header('Location: ' . URLROOT . '/register/success');
                    exit;
                } else {
                    // If profile creation failed, delete the user to maintain consistency
                    error_log('Profile creation failed, rolling back user creation');
                    $this->registerModel->deleteUser($userId);
                    $data['error'] = 'Registration failed. Please check all required fields and try again.';
                }
            } else {
                $data['error'] = 'Registration failed. Please try again.';
            }
            
            $data['form_data'] = $formData;
            return $data;

        } catch (Exception $e) {
            error_log('ProcessRegistration Error: ' . $e->getMessage());
            error_log('Error trace: ' . $e->getTraceAsString());
            $data['error'] = 'Registration failed due to a technical error. Please check the error logs for details.';
            $data['form_data'] = $_POST; // Keep original POST data
            return $data;
        }
    }

    private function validateForm($data, $role) {
        try {
            $errors = [];

            // Common validations
            if (empty($data['first_name'])) {
                $errors[] = 'First name is required';
            }
            if (empty($data['last_name'])) {
                $errors[] = 'Last name is required';
            }

            if (empty($data['email'])) {
                $errors[] = 'Email is required';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            }

            if (empty($data['phone'])) {
                $errors[] = 'Phone number is required';
            } elseif (!preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $data['phone'])) {
                $errors[] = 'Please enter a valid phone number';
            }

            if (empty($data['password'])) {
                $errors[] = 'Password is required';
            } elseif (strlen($data['password']) < 6) {
                $errors[] = 'Password must be at least 6 characters long';
            }

            if ($data['password'] !== $data['confirm_password']) {
                $errors[] = 'Passwords do not match';
            }

            // Role-specific validations
            switch($role) {
                case 'customer':
                    if (empty($data['district'])) {
                        $errors[] = 'District is required';
                    }
                    if (empty($data['sports'])) {
                        $errors[] = 'Preferred sport is required';
                    }
                    if (empty($data['age_group'])) {
                        $errors[] = 'Age group is required';
                    }
                    if (empty($data['skill_level'])) {
                        $errors[] = 'Skill level is required';
                    }
                    break;

                case 'stadium_owner':
                    if (empty($data['owner_name'])) {
                        $errors[] = 'Owner name is required';
                    }
                    if (empty($data['business_name'])) {
                        $errors[] = 'Business name is required';
                    }
                    if (empty($data['district'])) {
                        $errors[] = 'District is required';
                    }
                    if (empty($data['venue_type'])) {
                        $errors[] = 'Venue type is required';
                    }
                    if (empty($data['business_reg'])) {
                        $errors[] = 'Business registration number is required';
                    }
                    break;

                case 'coach':
                    if (empty($data['specialization'])) {
                        $errors[] = 'Sports specialization is required';
                    }
                    if (empty($data['experience'])) {
                        $errors[] = 'Years of experience is required';
                    }
                    if (empty($data['certification'])) {
                        $errors[] = 'Certification level is required';
                    }
                    if (empty($data['coaching_type'])) {
                        $errors[] = 'Coaching type is required';
                    }
                    if (empty($data['district'])) {
                        $errors[] = 'District is required';
                    }
                    if (empty($data['availability'])) {
                        $errors[] = 'Availability is required';
                    }
                    break;

                case 'rental_owner':
                    if (empty($data['owner_name'])) {
                        $errors[] = 'Owner name is required';
                    }
                    if (empty($data['business_name'])) {
                        $errors[] = 'Business name is required';
                    }
                    if (empty($data['district'])) {
                        $errors[] = 'District is required';
                    }
                    if (empty($data['business_type'])) {
                        $errors[] = 'Business type is required';
                    }
                    if (empty($data['equipment_categories'])) {
                        $errors[] = 'Equipment category is required';
                    }
                    if (empty($data['delivery_service'])) {
                        $errors[] = 'Delivery service option is required';
                    }
                    break;
            }

            return ['errors' => $errors];
        } catch (Exception $e) {
            error_log('Validation Error: ' . $e->getMessage());
            return ['errors' => ['Validation failed due to technical error']];
        }
    }

    public function success() {
        // Success page after registration
        $data = [
            'title' => 'Registration Successful - BookMyGround'
        ];

        $this->view('signup/v_success', $data);
    }

    public function ajax() {
        // AJAX registration endpoint for modal signup
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        try {
            // Collect form data
            $formData = [
                'role' => 'customer',  // Modal registration is always for customer/player
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];

            // Validate required fields
            if (empty($formData['first_name'])) {
                echo json_encode(['success' => false, 'message' => 'First name is required']);
                exit;
            }
            if (empty($formData['last_name'])) {
                echo json_encode(['success' => false, 'message' => 'Last name is required']);
                exit;
            }
            if (empty($formData['email'])) {
                echo json_encode(['success' => false, 'message' => 'Email is required']);
                exit;
            }
            if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
                exit;
            }
            if (empty($formData['phone'])) {
                echo json_encode(['success' => false, 'message' => 'Phone number is required']);
                exit;
            }
            if (!preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $formData['phone'])) {
                echo json_encode(['success' => false, 'message' => 'Please enter a valid phone number']);
                exit;
            }
            if (empty($formData['password'])) {
                echo json_encode(['success' => false, 'message' => 'Password is required']);
                exit;
            }
            if (strlen($formData['password']) < 8) {
                echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
                exit;
            }
            if ($formData['password'] !== $formData['confirm_password']) {
                echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
                exit;
            }

            // Check if email already exists
            if ($this->registerModel->emailExists($formData['email'])) {
                echo json_encode(['success' => false, 'message' => 'An account with this email already exists']);
                exit;
            }

            // Check if phone already exists
            if ($this->registerModel->phoneExists($formData['phone'])) {
                echo json_encode(['success' => false, 'message' => 'An account with this phone number already exists']);
                exit;
            }

            // Create user account
            $userId = $this->registerModel->createUser($formData);

            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again']);
                exit;
            }

            // Create customer profile
            $profileCreated = $this->registerModel->createCustomerProfile($userId, $formData);

            if (!$profileCreated) {
                // Delete user if profile creation failed
                $this->registerModel->deleteUser($userId);
                echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again']);
                exit;
            }

            // Auto-login the user
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $formData['email'];
            $_SESSION['user_name'] = $formData['first_name'] . ' ' . $formData['last_name'];
            $_SESSION['user_role'] = 'customer';
            $_SESSION['user_first_name'] = $formData['first_name'];
            $_SESSION['user_last_name'] = $formData['last_name'];
            $_SESSION['user_status'] = 'active';

            // Send welcome email (optional, non-blocking)
            $this->registerModel->sendWelcomeEmail($formData['email'], $formData['first_name'], 'customer');

            echo json_encode(['success' => true, 'message' => 'Registration successful']);
            exit;

        } catch (Exception $e) {
            error_log('AJAX Registration Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Registration failed due to a technical error']);
            exit;
        }
    }
}