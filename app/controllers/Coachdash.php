<?php
class Coachdash extends Controller {
//////////////////THIS IS WHEN USER CLICK THE COACH DASHBOARD BUTTON IN THE NAVBAR///////////////////
    public function index() {
        // Get coach data (in real scenario, this would come from database/session)
        $coach_data = $this->getCoachData();
        // Load sports options so the dashboard can display human-friendly sport labels
        $registerModel = $this->model('M_Register');
        $sports = [];
        if (method_exists($registerModel, 'getSportsSpecializations')) {
            $sports = $registerModel->getSportsSpecializations();
        }

        $data = [
            'title' => 'Edit Profile',
            'coach' => $coach_data,
            'sports' => $sports
        ];

        $this->view('coachdash/v_coach_dashboard', $data);
    }




    private function getCoachData() {
        // Use the model M_Coachdash to fetch coach data
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'] ?? 1; // fallback to 1 for development

        $model = $this->model('M_Coachdash');
        $coach = $model->getCoachByUserId($user_id);

        if ($coach) {
            return $coach;
        }

        // default fallback structure
        return [
            'id' => $user_id,
            'name' => '',
            'image' => '',
            'availability' => '',
            'rating' => '1',
            'location' => '',
            'sport' => '',
            'featured' => false,
            'mobile' => '',
            'bio' => '',
            'rate' => '',
            'experience' => '',
            'certification' => '',
            'specialization' => '',
            'free_slots' => [],
            'achievements' => [],
            'languages' => [],
            'email' => '',
            'address' => '',
            'qualifications' => [],
            'training_style' => ''
        ];
    }














    




    
////////////////////////////THIS IS WHEN USER CLICK THE EDIT PROFILE BUTTON IN THE COACH DASHBOARD PAGE//////////////////////////////
    public function edit() {
        // show editable profile view
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'] ?? 1;

        $model = $this->model('M_Coachdash');
        $coach = $model->getCoachByUserId($user_id);

        // Load sports options from registration model so edit uses same choices as signup
        $registerModel = $this->model('M_Register');
        $sports = [];
        if (method_exists($registerModel, 'getSportsSpecializations')) {
            $sports = $registerModel->getSportsSpecializations();
        }
        // Load cities/districts from registration model so edit page shows full list
        $cities = [];
        if (method_exists($registerModel, 'getCities')) {
            $cities = $registerModel->getCities();
        }

        $data = [
            'title' => 'Edit Profile',
            'coach' => $coach ?: ['id' => $user_id],
            'sports' => $sports,
            'cities' => $cities
        ];

        $this->view('coachdash/v_coach_dashboard_edit', $data);
    }




























///////////////////THIS IS AFTER USER CLICK SAVE CHANGES IN THE EDIT PROFILE PAGE/////////////////////////////////////////
    public function updateProfile() {
        // Handle profile update (in real scenario, this would process form data)
        if ($_POST) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? $_POST['user_id'] ?? 1;

            // collect fields
            $first = trim($_POST['first_name'] ?? '');
            $last = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');



            $phone = trim($_POST['mobile'] ?? '');


            $district = trim($_POST['district'] ?? '');
            $certification = trim($_POST['certification'] ?? '');
            $experience = trim($_POST['experience'] ?? '');
            $coaching_type = trim($_POST['coaching_type'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $training_style = trim($_POST['training_style'] ?? '');
            $languages = $_POST['languages'] ?? [];
            $achievements = $_POST['achievements'] ?? [];
            // current_status is the coach's current availability state (available/unavailable/flexibility)
            $current_status = trim($_POST['current_status'] ?? '');
            // availability is the coach's primary work window (weekdays/weekends/flexible)
            $primary_availability = trim($_POST['availability'] ?? '');
            $hourly_rate = trim($_POST['hourly_rate'] ?? '');
            // free_slots may be submitted as an array of arrays
            $free_slots = $_POST['free_slots'] ?? [];
            $deleted_slots = $_POST['deleted_slots'] ?? [];
            $specialization = trim($_POST['specialization'] ?? '');

          











            // Handle profile photo upload if file is provided
            $imageUrl = null;
            // get current image from DB first
            $model = $this->model('M_Coachdash');
            $currentCoach = $model->getCoachByUserId($user_id);

            if($currentCoach){


                $existingImage = $currentCoach->image;
            }

            $imageUrl = $existingImage; // DEFAULT = old image
            if (!empty($_POST['remove_photo'])) {
                $imageUrl = null; // remove image
            }


            if (!empty($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_photo'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if ($file['size'] <= $maxSize) {
                    // Validate MIME type
                    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                    // Use object-oriented finfo to avoid deprecated procedural API
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($file['tmp_name']);

                    if (isset($allowed[$mime])) {
                        $ext = $allowed[$mime];
                        $projectRoot = dirname(__DIR__, 2);
                        $targetDir = $projectRoot . '/public/images/coaches/registerd_coach';
                        if (!is_dir($targetDir)) {
                            @mkdir($targetDir, 0755, true);
                        }

                        $filename = 'coach_' . $user_id . '_' . time() . '.' . $ext;
                        $destPath = $targetDir . '/' . $filename;

                        if (move_uploaded_file($file['tmp_name'], $destPath)) {
                            $imageUrl ='public/images/coaches/registerd_coach/' . $filename;
                        }
                    }
                }
            }



            
            $model = $this->model('M_Coachdash');
            $update = $model->updateCoachByUserId($user_id, [
                'first_name' => $first,
                'last_name' => $last,
                'email' => $email,
                'phone' => $phone,
                'district' => $district,
                'certification' => $certification,
                'experience' => $experience,
                'coaching_type' => $coaching_type,
                'bio' => $bio,
                'specialization' => $specialization,
                'languages' => $languages,
                'achievements' => $achievements,
                'training_style' => $training_style,
                'free_slots' => $free_slots,
                'deleted_slots_id' =>$deleted_slots,
                // persist current status to coach_card_details and update coach_profiles.availability with primary_availability
                'current_status' => $current_status,
                'primary_availability' => $primary_availability,
                // ensure coach_profiles.availability gets updated from the edit form's availability field
                'availability' => $primary_availability,
                'hourly_rate' => $hourly_rate,
                'image' => $imageUrl
            ]);

            // Redirect back to profile page
            header('Location: ' . URLROOT . '/coachdash');
            exit;
        }
    }















    public function verification(){
        $user_id = $_SESSION['user_id'] ?? 1;
        $model=$this->model('M_Coachdash');
        $result = $model->verification($user_id);
        $coach_details =$model->getCoachByUserId($user_id);
        if(!$result){
            $status = "not_submitted";
            $verificationData =NULL;
        }
        else{
            $status = $result->status;
            $verificationData = $result;

        }
        $data = [
            'title' => 'verification form',
            'status' => $status,
            'verification' => $verificationData,
            'Coach_details' => $coach_details

        ];
        $this->view('coachdash/v_coach_verification',$data);
    }








    public function SubmitVerification(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){


            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


            if (session_status() === PHP_SESSION_NONE) {

                session_start();
            }

            $user_id = $_SESSION['user_id'] ?? $_POST['user_id'] ?? 1;

            $certification = trim($_POST['certification'] ?? '');
            $status = 'pending';

            $file_path = null;

            // ================= FILE UPLOAD =================
            if (!empty($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {

                $file = $_FILES['certificate_file'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if ($file['size'] <= $maxSize) {


                    // Validate MIME type (SAFE like profile upload)
                    $allowed = [

                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp'
                    ];

                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($file['tmp_name']);

                    if (isset($allowed[$mime])) {


                        $ext = $allowed[$mime];

                        // Folder path
                        $projectRoot = dirname(__DIR__, 2);
                        $targetDir = $projectRoot . '/public/images/coaches/certificates';

                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }

                        // UNIQUE SAFE NAME (IMPORTANT)
                        $filename = 'cert_' . $user_id . '_' . time() . '.' . $ext;
                        $destPath = $targetDir . '/' . $filename;

                        // Move file
                        if (move_uploaded_file($file['tmp_name'], $destPath)) {
                            $file_path = 'public/images/coaches/certificates/' . $filename;
                        }
                    }
                }   
            }

            // ================= KEEP OLD FILE IF NOT UPLOADED =================
            if($file_path === null){
                $model = $this->model('M_Coachdash');
                $existing = $model->verification($user_id);

                if($existing && !empty($existing->file_path)){
                    $file_path = $existing->file_path;
                }
            }

            // ================= SAVE =================
            $model = $this->model('M_Coachdash');

            $update = $model->verification_submission($user_id, [
                'certification' => $certification,
                'file_path' => $file_path,
                'status' => $status
            ]);

            header('Location: ' . URLROOT . '/coachdash/verification');
            exit;
        }
    }
    public function editverification(){

        $user_id = $_SESSION['user_id'];
        $model =$this->model('M_Coachdash');
        $update = $model->Change_verification_status($user_id,"not_submitted");

        header('Location: ' . URLROOT . '/coachdash/verification');
        exit;

    }







































    public function messages(){
        $data = [
            'title' => 'Messages',
        ];
        $this->view('coachdash/v_messages', $data);
    }

    public function advertisment(){
        $userId = Auth::getUserId();
        $model = $this->model('M_Coachdash');
        
        $data = [
            'title' => 'My Advertisements',
            'advertisements' => $model->getAdvertisements($userId),
            'ad_packages' => $model->getAdvertisementPackages()
        ];
        $this->view('coachdash/v_advertisment', $data);
    }

    public function submitAdvertisement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/coachdash/advertisment');
            exit;
        }

        $userId = Auth::getUserId();
        $model = $this->model('M_Coachdash');
        $coachData = $model->getCoachByUserId($userId);

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'contact_name' => trim((Auth::getUserFirstName() ?? '') . ' ' . (Auth::getUserLastName() ?? '')),
            'email' => Auth::getUserEmail() ?? '',
            'phone' => $coachData->phone ?? '',
            'package' => $_POST['package'] ?? 'basic',
            'website' => trim($_POST['website'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'Business/Service name is required.';
            header('Location: ' . URLROOT . '/coachdash/advertisment');
            exit;
        }

        // Handle file upload
        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['ad_image'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024;

            if (!in_array($file['type'], $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Use JPG, PNG, GIF or WEBP.';
                header('Location: ' . URLROOT . '/coachdash/advertisment');
                exit;
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['error'] = 'File too large. Max 5MB allowed.';
                header('Location: ' . URLROOT . '/coachdash/advertisment');
                exit;
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'ad_' . $userId . '_' . time() . '.' . $extension;
            $uploadDir = APPROOT . '/../public/images/advertisements/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $data['file_path'] = $filename;
            }
        }

        if ($model->createAdvertisement($userId, $data)) {
            $_SESSION['success'] = 'Advertisement submitted! We will review and contact you soon.';
        } else {
            $_SESSION['error'] = 'Failed to submit advertisement.';
        }

        header('Location: ' . URLROOT . '/coachdash/advertisment');
        exit;
    }

    public function deleteAdvertisement($id) {
        $userId = Auth::getUserId();
        $model = $this->model('M_Coachdash');

        if ($model->deleteAdvertisement($id, $userId)) {
            $_SESSION['success'] = 'Advertisement removed.';
        } else {
            $_SESSION['error'] = 'Failed to remove advertisement.';
        }

        header('Location: ' . URLROOT . '/coachdash/advertisment');
        exit;
    }

    public function editAdvertisement($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/coachdash/advertisment');
            exit;
        }

        $userId = Auth::getUserId();
        $model = $this->model('M_Coachdash');
        
        $existingAd = $model->getAdvertisementById($id, $userId);
        if (!$existingAd) {
            $_SESSION['error'] = 'Advertisement not found.';
            header('Location: ' . URLROOT . '/coachdash/advertisment');
            exit;
        }

        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'Business/Service name is required.';
            header('Location: ' . URLROOT . '/coachdash/advertisment');
            exit;
        }

        // Handle new file upload
        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['ad_image'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024;

            if (in_array($file['type'], $allowed) && $file['size'] <= $maxSize) {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'ad_' . $userId . '_' . time() . '.' . $extension;
                $uploadDir = APPROOT . '/../public/images/advertisements/';

                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $data['file_path'] = $filename;
                }
            }
        }

        if ($model->updateAdvertisement($id, $userId, $data)) {
            $_SESSION['success'] = '✏️ Advertisement updated! It will be reviewed again for approval.';
        } else {
            $_SESSION['error'] = 'Failed to update advertisement.';
        }

        header('Location: ' . URLROOT . '/coachdash/advertisment');
        exit;
    }
























































/*/////////////////////////////////Blog Post Creation FROM COACH DASHBOARD////////////////////////////////////////////////////////*/
    // Handle blog post creation from coach dashboard
    public function createPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/coachdash/blog');
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $author_id = $_SESSION['user_id'] ?? 1;
        $author_name = $_SESSION['user_name'] ?? 'Coach';

        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'General');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $tags = trim($_POST['tags'] ?? '');
        $status = trim($_POST['status'] ?? 'draft');

        // slug generation
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($title));
            $slug = trim($slug, '-');
        }

        // publish datetime based on status
        $published = null;
        if ($status === 'published') {
            $published = date('Y-m-d H:i:s');
        } elseif ($status === 'scheduled') {
            $pd = $_POST['publish_date'] ?? '';
            $published = $pd ? date('Y-m-d H:i:s', strtotime($pd)) : null;
        }

        // handle featured image upload
        $featuredImageUrl = null;
        if (!empty(!$_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK){
            $file = $_FILES['featured_image'];
            /*
            $file = [


                'name'     => 'photo.jpg',
                'type'     => 'image/jpeg',
                'tmp_name' => 'C:\xampp\tmp\phpA3F.tmp',
                'error'    => 0,
                'size'     => 245678
            ];
            */

            $maxSize = 5 * 1024 * 1024;
            if ($file['size'] <= $maxSize){
                $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png','image/webp' => 'webp'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($file['tmp_name']);
                if(isset($allowed[$mime])){
                    $ext = $allowed[$mime];
                    $projectRoot = dirname(__DIR__,2);
                    $targetDir = $projectRoot . '/public/images/blog';
                    if (!is_dir($targetDir)){
                        @mkdir($targetDir, 0755, true);

                    }
                    $filename = 'post' . time() . '_' . rand(1000,9999) . '.' . $ext;
                    $destPath = $targetDir . '/' . $filename;
                    if(move_uploaded_file($file['tmp_name'],$destPath)){
                        $featuredImageUrl = rtrim(URLROOT,'/') . '/images/blog/' . $filename;
                        
                    } 


                }
            }

        }
        if (empty($featuredImageUrl)) {
            $featuredImageUrl = rtrim(URLROOT, '/') . '/images/blog/default/images.png';
        }

        $now = date('Y-m-d H:i:s');

        $postData = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'author_id' => $author_id,
            'author' => $author_name,
            'category' => $category,
            'featured_image' => $featuredImageUrl,
            'tags' => $tags,
            'status' => $status,
            'published' => $published,
            'views' => 0,
            'created_at' => $now,
            'updated_at' => $now
        ];

        $model = $this->model('M_Coachdash');
        $insertId = $model->createPost($postData);

        header('Location: ' . URLROOT . '/coachdash/blog');
        exit;
    }













    

    // Toggle publish/unpublish from coach dashboard (AJAX)
    public function togglePostStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode([

                'success' => false, 
                'message' => 'Invalid request method'
            ]);
            exit;
        }

        // Read JSON body (sent by fetch)
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        $action = isset($input['action']) ? $input['action'] : '';

        if (!$id || !in_array($action, ['publish', 'unpublish'])) {
            header('Content-Type: application/json');
            echo json_encode([

                'success' => false,
                'message' => 'Invalid parameters'
            ]);
            exit;
        }

        $status = $action === 'publish' ? 'published' : 'draft';

        $postsModel = $this->model('M_Posts');
        $ok = $postsModel->updateStatus($id, $status);

        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Post status updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        exit;
    }




    

    // Return single post data as JSON for edit modal
    public function getPost() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid id']);
            exit;
        }
        $model = $this->model('M_Coachdash');
        $post = $model->getPostById($id);
        if (!$post) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Post not found']);
            exit;
        }

        // normalize for client (use property_exists to avoid notices)
        $out = [
            'id' => property_exists($post, 'id') ? $post->id : null,
            'title' => property_exists($post, 'title') ? $post->title : '',
            'slug' => property_exists($post, 'slug') ? $post->slug : '',
            'excerpt' => property_exists($post, 'excerpt') ? $post->excerpt : '',
            'content' => property_exists($post, 'content') ? $post->content : '',
            'category' => property_exists($post, 'category') ? $post->category : '',
            'tags' => property_exists($post, 'tags') ? $post->tags : '',
            'status' => property_exists($post, 'status') ? $post->status : 'draft',
            'featured_image' => property_exists($post, 'featured_image') ? $post->featured_image : null,
            'created_at' => property_exists($post, 'created_at') ? $post->created_at : null,
            'author' => property_exists($post, 'author_name') ? $post->author_name : ($_SESSION['user_name'] ?? 'Coach')
        ];

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'post' => $out]);
        exit;
    }




















    // Handle update of existing post
    public function updatePost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/coachdash/blog');
            exit;
        }

        $id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        if (!$id) {
            header('Location: ' . URLROOT . '/coachdash/blog');
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'General');
        $tags = trim($_POST['tags'] ?? '');
        $status = trim($_POST['status'] ?? 'draft');

        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($title));
            $slug = trim($slug, '-');
        }

        // handle featured image upload (optional)
        $featuredImageUrl = null;
        if (!empty($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['featured_image'];
            $maxSize = 5 * 1024 * 1024;
            if ($file['size'] <= $maxSize) {
                $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($file['tmp_name']);
                if (isset($allowed[$mime])) {
                    $ext = $allowed[$mime];
                    $projectRoot = dirname(__DIR__, 2);
                    $targetDir = $projectRoot . '/public/images/blog';
                    if (!is_dir($targetDir)) {
                        @mkdir($targetDir, 0755, true);
                    }
                    $filename = 'post_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                    $destPath = $targetDir . '/' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $destPath)) {
                        $featuredImageUrl = rtrim(URLROOT, '/') . '/images/blog/' . $filename;
                    }
                }
            }
        }

        $model = $this->model('M_Coachdash');
        $updateData = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'category' => $category,
            'tags' => $tags,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        if ($featuredImageUrl !== null) {
            $updateData['featured_image'] = $featuredImageUrl;
        }
        $model->updatePost($id, $updateData);

        header('Location: ' . URLROOT . '/coachdash/blog');
        exit;
    }

    public function deletePost() {


        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;

        if (!$id) {
            echo json_encode(['success' => false]);
            return;
        }

        $model = $this->model('M_Coachdash');
        $ok = $model->softDeletePost($id);

        echo json_encode(['success' => $ok]);
    }

























    
    public function blog() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Load posts from DB authored by current coach
        $user_id = $_SESSION['user_id'] ?? 1;
        $model = $this->model('M_Coachdash');
        $rows = $model->getPostsByAuthor($user_id);

        // Load available sports/categories from registration model
        $registerModel = $this->model('M_Register');
        $sports = [];
        if (method_exists($registerModel, 'getSportsSpecializations')) {
            $sports = $registerModel->getSportsSpecializations();
        }
        // ensure 'general' option exists
        $sports = array_merge(['general' => 'General'], $sports);

        $posts = [];
        foreach ($rows as $p) {
            $posts[] = [
                'id' => $p->id,
                'title' => $p->title,
                'author' => $p->author_name ? $p->author_name : ($_SESSION['user_name'] ?? 'Coach'),
                'category' => $p->category ?? 'General',
                'status' => isset($p->status) ? ucfirst($p->status) : 'Draft',
                // Use created_at from posts table as the published date (if available)
                'published' => (isset($p->created_at) && $p->created_at) ? date('Y-m-d', strtotime($p->created_at)) : '',
                'views' => $p->views ?? 0,
                'featured_image' => $p->featured_image ?? null
            ];
        }

        $data = [
            'title' => 'Blog Management',
            'posts' => $posts,
            'categories' => $sports
        ];

        $this->view('coachdash/v_blog', $data);
    }
}