<?php
class M_Coachdash {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }



/*/////////////////////////////////Coach Profile edit ////////////////////////////////////////////////////////
    /**
     * Get coach profile data by user id(read)
     * Returns an associative array shaped for the coach dashboard view
     * in here u.id means user table id column, cp means coach_profiles table alias, ccd means coach_card_details table alias
     */
    public function getCoachByUserId($user_id)
    {
    $this->db->query('SELECT u.id AS user_id, u.first_name, u.last_name, u.email, u.phone, cp.specialization, cp.experience, cp.certification, cp.coaching_type, cp.district, cp.availability,
              ccd.bio AS card_bio, ccd.training_style AS card_training_style, ccd.languages AS card_languages, ccd.achievements AS card_achievements, ccd.availability_text AS card_availability_text, ccd.hourly_rate AS card_hourly_rate
              FROM users u
              LEFT JOIN coach_profiles cp ON u.id = cp.user_id
              LEFT JOIN coach_card_details ccd ON u.id = ccd.user_id
              WHERE u.id = :id');
        $this->db->bind(':id', $user_id);
        $row = $this->db->single();

        if (!$row) {
            return null;
        }

        $fullName = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: null;



        $languages = [];
        if (!empty($row->card_languages)) {
            $languages = array_map('trim', explode(',', $row->card_languages));
        }
 
        $achievements = [];
        if (!empty($row->card_achievements)) {
            $achievements = array_filter(array_map('trim', explode('|', $row->card_achievements)));
        }
        //fetch coach image
        $this->db->query('SELECT id,image_url FROM coach_profile_pictures WHERE user_id = :id');
        $this->db->bind(':id',$user_id);
        $coach_image = $this->db->single();
        
        // fetch free slots
        $this->db->query('SELECT id, day, time_slot, session_type FROM coach_free_training WHERE user_id = :id');
        $this->db->bind(':id', $user_id);
        $slots = $this->db->resultset();
        
        
        return [
            'id' => $row->user_id,
            'name' => $fullName,
            'first_name' => $row->first_name ?? '',
            'last_name' => $row->last_name ?? '',
            'image' => $coach_image->image_url ?? '',
            'availability' => $row->availability ?? '',
            'rating' => '',
            'location' => $row->district ?? '',
            'sport' => '',
            'featured' => false,
            'mobile' => $row->phone ?? '',
            'bio' => $row->card_bio ?? '',
            'rate' => '',
            'experience' => $row->experience ?? '',
            'certification' => $row->certification ?? '',
            'specialization' => $row->specialization,
            'free_slots' => $slots ?: [],
            'achievements' => $achievements,
            'languages' => $languages,
            'availability_text' => $row->card_availability_text ?? '',
            'hourly_rate' => $row->card_hourly_rate ?? 0.00,
            'email' => $row->email ?? '',
            'address' => '',
            'qualifications' => [],
            'training_style' => $row->card_training_style ?? '',
            'coaching_type' => $row->coaching_type ?? ''
        ];
    }




















    /**
     * Update coach profile by user id(update)
     * $data may include first_name, last_name, email, phone, district, certification, experience, coaching_type, bio, specialization
     */
    public function updateCoachByUserId($user_id, $data)
    {

        
        if(array_key_exists('image', $data)){
            $this->db->query('SELECT id FROM coach_profile_pictures WHERE user_id = :u_id');
            $this->db->bind(':u_id', $user_id);
            $existsImg = $this->db->single();

            if ($existsImg) {
                if (empty($data['image'])) {
                    $this->db->query('DELETE FROM coach_profile_pictures WHERE user_id = :u_id');
                    $this->db->bind(':u_id', $user_id);
                    $this->db->execute();
                } else {
                    $this->db->query('UPDATE coach_profile_pictures SET image_url = :img WHERE user_id = :u_id');
                    $this->db->bind(':img', $data['image']);
                    $this->db->bind(':u_id', $user_id);
                    $this->db->execute();
                }
            } else {
                if (!empty($data['image'])) {
                    $this->db->query('INSERT INTO coach_profile_pictures (user_id, image_url) VALUES (:u_id, :img)');
                    $this->db->bind(':u_id', $user_id);
                    $this->db->bind(':img', $data['image']);
                    $this->db->execute();
                }
            }



        }





        //------- Update users table------
        $this->db->query('UPDATE users SET first_name = :first, last_name = :last, email = :email, phone = :phone WHERE id = :id');
        $this->db->bind(':first', $data['first_name'] ?? '');
        $this->db->bind(':last', $data['last_name'] ?? '');
        $this->db->bind(':email', $data['email'] ?? '');
        $this->db->bind(':phone', $data['phone'] ?? '');
        $this->db->bind(':id', $user_id);
        $this->db->execute();

        // Ensure coach_profiles exists for this user
        $this->db->query('SELECT id FROM coach_profiles WHERE user_id = :id');
        $this->db->bind(':id', $user_id);
        $exists = $this->db->single();


        $spec = '';
        $spec = trim($data['specialization'] ?? '');
        //---update coach profile-------------
        if ($exists) {
            

            $this->db->query('UPDATE coach_profiles SET specialization = :spec, experience = :exp, certification = :cert, coaching_type = :ctype, district = :district, availability = :avail WHERE user_id = :id');
            
            $this->db->bind(':spec', $spec);
            $this->db->bind(':exp', $data['experience'] ?? '');
            $this->db->bind(':cert', $data['certification'] ?? '');
            $this->db->bind(':ctype', $data['coaching_type'] ?? '');
            $this->db->bind(':district', $data['district'] ?? '');
            // preserve coach_profiles.availability (registration value) - only update if 'availability' provided
            $this->db->bind(':avail', $data['availability'] ?? ($data['current_status'] ?? 'available'));
            $this->db->bind(':id', $user_id);
            $res = $this->db->execute();
            // persist extended card details and free slots
            $this->upsertCoachCardDetails($user_id, $data);
            return $res;

        } else {
            // Build INSERT query: only include image column if a new image was provided

            
            $this->db->query('INSERT INTO coach_profiles (user_id, specialization, experience, certification, coaching_type, district, availability) VALUES (:id, :spec, :exp, :cert, :ctype, :district, :avail)');
            
            $this->db->bind(':id', $user_id);
            $this->db->bind(':spec', $spec);
            $this->db->bind(':exp', $data['experience'] ?? '');
            $this->db->bind(':cert', $data['certification'] ?? '');
            $this->db->bind(':ctype', $data['coaching_type'] ?? '');
            $this->db->bind(':district', $data['district'] ?? '');
            // preserve coach_profiles.availability (registration value) - only update if 'availability' provided
            $this->db->bind(':avail', $data['availability'] ?? ($data['current_status'] ?? 'available'));
            $res = $this->db->execute();
            $this->upsertCoachCardDetails($user_id, $data);
            return $res;
        }
    }




















    /**
     * Persist extended coach card details and free slots
     */
    public function upsertCoachCardDetails($user_id, $data)
    {
        // Upsert coach_card_details
        $this->db->query('SELECT id FROM coach_card_details WHERE user_id = :id');
        $this->db->bind(':id', $user_id);
        $exists = $this->db->single();

        $langs = '';
        if (!empty($data['languages'])) {
            if (is_array($data['languages'])) {
                $langs = implode(', ', array_map('trim', $data['languages']));
            } else {
                $langs = trim($data['languages']);
            }
        }

        if ($exists) {
            $this->db->query('UPDATE coach_card_details SET bio = :bio, training_style = :ts, languages = :langs, achievements = :ach, awards = :aw, availability_text = :avail_text, hourly_rate = :hr WHERE user_id = :id');
            $this->db->bind(':id', $user_id);
            $this->db->bind(':bio', $data['bio'] ?? '');
            $this->db->bind(':ts', $data['training_style'] ?? '');
            $this->db->bind(':langs', $langs);
            $this->db->bind(':ach', is_array($data['achievements']) ? implode('|', $data['achievements']) : ($data['achievements'] ?? ''));
            $this->db->bind(':aw', '');
            // availability_text prefers current_status if provided (edit-time status), otherwise primary_availability
            $this->db->bind(':avail_text', $data['current_status'] ?? ($data['primary_availability'] ?? ($data['availability'] ?? '')));
            $this->db->bind(':hr', $data['hourly_rate'] ?? 0.00);
            $this->db->execute();
        } else {
            $this->db->query('INSERT INTO coach_card_details (user_id, bio, training_style, languages, achievements, awards, availability_text, hourly_rate) VALUES (:id, :bio, :ts, :langs, :ach, :aw, :avail_text, :hr)');
            $this->db->bind(':id', $user_id);
            $this->db->bind(':bio', $data['bio'] ?? '');
            $this->db->bind(':ts', $data['training_style'] ?? '');
            $this->db->bind(':langs', $langs);
            $this->db->bind(':ach', is_array($data['achievements']) ? implode('|', $data['achievements']) : ($data['achievements'] ?? ''));
            $this->db->bind(':aw', '');
            $this->db->bind(':avail_text', $data['current_status'] ?? ($data['primary_availability'] ?? ($data['availability'] ?? '')));
            $this->db->bind(':hr', $data['hourly_rate'] ?? 0.00);
            $this->db->execute();
        }

 
        //////// coach free training sessions////////////////////
        if(!empty($data['deleted_slots_id']) && is_array($data['deleted_slots_id'])){
            foreach($data['deleted_slots_id'] as $delete_id){
                $this->db->query('DELETE FROM coach_free_training WHERE id =:id AND user_id = :uid');
                $this->db->bind(':id',$delete_id);
                $this->db->bind(':uid',$user_id);
                $this->db->execute();


            }


        }
        if (!empty($data['free_slots']) && is_array($data['free_slots'])) {


            foreach ($data['free_slots'] as $slot) {



                $id = $slot['id'] ?? '';
                $day = $slot['day'] ?? '';
                $time = $slot['time'] ?? '';
                $type = $slot['type'] ?? '';

                if ($id) {

                    // UPDATE existing slot
                    $this->db->query('UPDATE coach_free_training 
                        SET day = :day, time_slot = :time, session_type = :type
                        WHERE id = :slot_id');

                    $this->db->bind(':slot_id', $id);
                    $this->db->bind(':day', $day);
                    $this->db->bind(':time', $time);
                    $this->db->bind(':type', $type);

                    $this->db->execute();

                } else {


                    // INSERT new slot
                    $this->db->query('INSERT INTO coach_free_training (user_id, day, time_slot, session_type)
                              VALUES (:user_id, :day, :time, :type)');

                    $this->db->bind(':user_id', $user_id);
                    $this->db->bind(':day', $day);
                    $this->db->bind(':time', $time);
                    $this->db->bind(':type', $type);

                    $this->db->execute();
                }
            }
        }
    }

    /* ============================================
       ADVERTISEMENT METHODS
    ============================================ */

    public function getAdvertisements($user_id)
    {
        $this->db->query('SELECT * FROM advertisement_requests 
                          WHERE user_id = :uid AND is_active = 1 
                          ORDER BY submitted_at DESC');
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }

    public function createAdvertisement($user_id, $data)
    {
        $this->db->query('INSERT INTO advertisement_requests 
                          (user_id, company_name, contact_name, email, phone, package, website, message, file_path, status, submitted_at) 
                          VALUES (:user_id, :company_name, :contact_name, :email, :phone, :package, :website, :message, :file_path, :status, :submitted_at)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':contact_name', $data['contact_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':package', $data['package']);
        $this->db->bind(':website', $data['website'] ?? null);
        $this->db->bind(':message', $data['message'] ?? null);
        $this->db->bind(':file_path', $data['file_path'] ?? null);
        $this->db->bind(':status', 'pending');
        $this->db->bind(':submitted_at', date('Y-m-d H:i:s'));
        return $this->db->execute();
    }

    public function deleteAdvertisement($id, $user_id)
    {
        $this->db->query('UPDATE advertisement_requests 
                          SET is_active = 0 
                          WHERE id = :id AND user_id = :uid');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }

    public function getAdvertisementById($id, $user_id)
    {
        $this->db->query('SELECT * FROM advertisement_requests 
                          WHERE id = :id AND user_id = :uid AND is_active = 1');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);
        return $this->db->single();
    }

    public function updateAdvertisement($id, $user_id, $data)
    {
        $sql = 'UPDATE advertisement_requests 
                SET company_name = :company_name, 
                    website = :website, 
                    message = :message, 
                    status = :status,
                    submitted_at = :submitted_at';
        
        if (!empty($data['file_path'])) {
            $sql .= ', file_path = :file_path';
        }
        
        $sql .= ' WHERE id = :id AND user_id = :uid';
        
        $this->db->query($sql);
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':website', $data['website'] ?? null);
        $this->db->bind(':message', $data['message'] ?? null);
        $this->db->bind(':status', 'pending');
        $this->db->bind(':submitted_at', date('Y-m-d H:i:s'));
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);
        
        if (!empty($data['file_path'])) {
            $this->db->bind(':file_path', $data['file_path']);
        }
        
        return $this->db->execute();
    }

    public function getAdvertisementPackages()
    {
        return [
            'basic' => ['name' => 'Basic', 'price' => 5000, 'duration' => '7 days'],
            'professional' => ['name' => 'Professional', 'price' => 12000, 'duration' => '30 days'],
            'premium' => ['name' => 'Premium', 'price' => 30000, 'duration' => '90 days']
        ];
    }

































/*/////////////////////////////////Blog Post Creation ////////////////////////////////////////////////////////
    /**
     * Create a new blog post (used by coach dashboard)
     * $data expected keys: title, slug, excerpt, content, author_id, author, category, featured_image, tags, status, published, created_at, updated_at
     */
    public function createPost($data)
    {
        // Insert only columns that exist in the posts table according to schema in dev/sql.txt
        $this->db->query('INSERT INTO posts (title, slug, excerpt, content, author_id, category, tags, featured_image, status, views, created_at, updated_at)
                          VALUES (:title, :slug, :excerpt, :content, :author_id, :category, :tags, :featured_image, :status, :views, :created_at, :updated_at)');

        $this->db->bind(':title', $data['title'] ?? '');
        $this->db->bind(':slug', $data['slug'] ?? '');
        $this->db->bind(':excerpt', $data['excerpt'] ?? '');
        $this->db->bind(':content', $data['content'] ?? '');
        $this->db->bind(':author_id', $data['author_id'] ?? null);
        $this->db->bind(':category', $data['category'] ?? 'General');
        $this->db->bind(':tags', trim($data['tags'] ?? ''));
        $this->db->bind(':featured_image', $data['featured_image'] ?? null);
        $this->db->bind(':status', $data['status'] ?? 'draft');
        $this->db->bind(':views', $data['views'] ?? 0, PDO::PARAM_INT);
        $this->db->bind(':created_at', $data['created_at'] ?? date('Y-m-d H:i:s'));
        $this->db->bind(':updated_at', $data['updated_at'] ?? date('Y-m-d H:i:s'));

        $res = $this->db->execute();
        if ($res) {
            return $this->db->lastInsertId();
        }
        return false;
    }













    
    /**
     * Get posts authored by a coach (author_id)
     * Returns array of stdClass rows with post columns plus author_name
     */
    public function getPostsByAuthor($user_id)
    {
        $this->db->query('SELECT p.*, CONCAT_WS(" ", u.first_name, u.last_name) AS author_name FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.author_id = :id  AND p.is_deleted = 0 ORDER BY p.created_at DESC');
        $this->db->bind(':id', $user_id);
        return $this->db->resultSet();
    }













    

    /**
     * Get single post by id
     */
    public function getPostById($id)
    {
        $this->db->query('SELECT p.*, CONCAT_WS(" ", u.first_name, u.last_name) AS author_name FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.id = :id LIMIT 1');
        $this->db->bind(':id', $id);
        $post = $this->db->single();

        if (!$post) {
            return false;
        }

        if (empty($post->featured_image)) {
            $post->featured_image = rtrim(URLROOT, '/') . '/images/blog/default/images.png';
        } elseif (strpos($post->featured_image, 'http') !== 0 && strpos($post->featured_image, '//') !== 0 && strpos($post->featured_image, '/') !== 0) {
            $post->featured_image = rtrim(URLROOT, '/') . '/images/blog/' . basename($post->featured_image);
        }

        return $post;
    }


    /**
     * Update post by id
     */
    public function updatePost($id, $data)
    {
        // Build query, include featured_image only if provided
        // Note: posts table uses status ENUM (draft/published/archived), not a separate 'published' column
        if (!empty($data['featured_image'])) {
            $this->db->query('UPDATE posts SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, category = :category, tags = :tags, featured_image = :featured_image, status = :status, updated_at = :updated_at WHERE id = :id');
            $this->db->bind(':featured_image', $data['featured_image']);
        } else {
            $this->db->query('UPDATE posts SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, category = :category, tags = :tags, status = :status, updated_at = :updated_at WHERE id = :id');
        }

        $this->db->bind(':title', $data['title'] ?? '');
        $this->db->bind(':slug', $data['slug'] ?? '');
        $this->db->bind(':excerpt', $data['excerpt'] ?? '');
        $this->db->bind(':content', $data['content'] ?? '');
        $this->db->bind(':category', $data['category'] ?? 'General');
        $this->db->bind(':tags', trim($data['tags'] ?? ''));
        $this->db->bind(':status', $data['status'] ?? 'draft');
        $this->db->bind(':updated_at', $data['updated_at'] ?? date('Y-m-d H:i:s'));
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function softDeletePost($id)
    {

    $this->db->query('UPDATE posts 
                      SET is_deleted = 1, deleted_at = NOW() 
                      WHERE id = :id');
    $this->db->bind(':id', $id);
    return $this->db->execute();
    }




























    
    ////////////////////////////////////////Verify Coach//////////////////////////////////////////////////

    public function verification($user_id)
    {
        $this->db->query('SELECT * FROM verification WHERE user_id=:u_id');
        $this->db->bind(':u_id',$user_id);
        $row= $this->db->single();
        if($row){
            return $row;
        }
        else{
            return false;
        }

    }

    public function verification_submission($user_id,$data)
    {
        $this->db->query('SELECT id FROM verification WHERE user_id=:u_id');
        $this->db->bind(':u_id',$user_id);
        $row=$this->db->single();

        if($row){
            $this->db->query('UPDATE verification SET certification=:certi,file_path=:path,status=:status WHERE user_id=:u_id');

        }
        else{
            $this->db->query('INSERT INTO verification (user_id,certification,file_path,status) VALUES (:u_id,:certi,:path,:status)');

        }
        // Bind values
        $this->db->bind(':u_id', $user_id);
        $this->db->bind(':certi', $data['certification']);
        $this->db->bind(':path', $data['file_path'] ?? null);
        $this->db->bind(':status', $data['status']);


        return $this->db->execute();
    }

    public function Change_verification_status($user_id,$status){
        $this->db->query('UPDATE verification SET status=:status WHERE user_id=:u_id');
        $this->db->bind(':u_id',$user_id);
        $this->db->bind(':status',$status);
        
        
        return $this->db->execute();  


    }


}





























