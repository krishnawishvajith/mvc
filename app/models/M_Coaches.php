<?php
class M_Coaches {
    private $db;
    public function __construct()
    {
        $this->db=new Database();
    }
    public function getcoaches($user_id){
        
        $this->db->query('SELECT u.id AS user_id, u.first_name, u.last_name, u.email, u.phone, cp.specialization, cp.experience, cp.certification, cp.coaching_type, cp.district, cp.availability, ccd.bio AS card_bio, ccd.training_style AS card_training_style, ccd.languages AS card_languages, ccd.achievements AS card_achievements, ccd.availability_text AS card_availability_text, ccd.hourly_rate AS card_hourly_rate
        FROM users u
        LEFT JOIN coach_profiles cp ON u.id = cp.user_id
        LEFT JOIN coach_card_details ccd ON u.id = ccd.user_id
        WHERE u.id = :id');
        $this->db->bind(':id', $user_id);

        $row=$this->db->single();

        if (!$row) {
            return null;
        }

        $fullName = trim(($row->first_name  ?? ''). ' ' .($row->last_name ?? ''));

        $specializations = [];
        if (!empty($row->specialization)){
            if(strpos($row->specialization,',') !== false){
                $specializations = array_map('trim', explode(',', $row->specialization));

            } else{
                $specializations = [trim($row->specialization)];
            }
        }
        // derive a primary sport from specializations if available
        $primarySport = '';
        if (!empty($specializations)) {
            $primarySport = ucwords(strtolower($specializations[0]));
        }
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
            'image' =>  $coach_image->image_url ?? '',
            // `availability` should represent the profile availability (full_time/part_time/weekends)
            // while `availability_text` holds the human-readable current status (available/unavailable/flexible)
            'availability' => $row->availability ?? '',
            'rating' => '',
            'location' => $row->district ?? '',
            'sport' => $primarySport,
            'featured' => false,
            'mobile' => $row->phone ?? '',
            'bio' => $row->card_bio ?? '',
            'rate' => '',
            'experience' => $row->experience ?? '',
            'certification' => $row->certification ?? '',
            'specialization' => $specializations,
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
     * Map availability status for display (available, unavailable, flexible)
     * @param string $status Current status value
     * @return string Normalized status
     */
    private function mapAvailabilityStatus($status) {
        $normalized = strtolower(trim($status));
        if (in_array($normalized, ['available', 'unavailable', 'flexibility'])) {
            return $normalized;
        }
        return 'unavailable';
    }

    public function selectbysport(){
        return [
            [
                'id'=>1,
                'title'=>'FootBall',
                'image'=> URLROOT . '/public/images/coaches/foot1.jpg',
            ],
            [
                'id'=>2,
                'title'=>'Cricket',
                'image'=> URLROOT . '/public/images/coaches/cricket.jpg',
            ],
            [
                'id'=>3,
                'title'=>'Tennis',
                'image'=> URLROOT . '/public/images/coaches/tenis.jpg',
            ],
            [
                'id'=>4,
                'title'=>'Badminton',
                'image'=> URLROOT . '/public/images/coaches/badminn.jpg',
            ],
            [
                'id'=>5,
                'title'=>'Swimming',
                'image'=> URLROOT . '/public/images/coaches/swim.jpg',
            ]
        ];
    }

    public function getFeatured(){
        $all = $this->getAll();
        $out = [];
        foreach ($all as $c) {
            if (!empty($c['featured'])) {
                $out[] = $c;
            }
        }
        return $out;
    }

    public function getBySport($sport){
        return $this->getCoachesBySport($sport);
    }











    public function getCoachesBySport($sport){
        $sport = strtolower(trim($sport));
        
        // Query to get all coaches with their profile data
        $this->db->query('SELECT u.id, u.first_name, u.last_name, u.email, u.phone, 
                         cp.specialization, cp.experience, cp.certification, cp.coaching_type, cp.district, cp.availability
                         FROM users u
                         LEFT JOIN coach_profiles cp ON u.id = cp.user_id
                         WHERE u.role = :role
                         ORDER BY u.id');
        $this->db->bind(':role', 'coach');
        $coachUsers = $this->db->resultset();
        
        if (!$coachUsers) {
            // No coaches in database, return empty array (don't use dummy data)
            return [];
        }
        
        $coaches = [];
        foreach ($coachUsers as $user) {
            // Check if coach has specialization matching the requested sport
            $specialization = strtolower(trim($user->specialization ?? ''));
            
            if (!empty($specialization) && stripos($specialization, $sport) !== false) {
                // Use getcoaches() to get complete coach data with card details
                $coach = $this->getcoaches($user->id);
                if ($coach) {
                    $coach['sport'] = ucwords($sport);
                    $coaches[] = $coach;
                }
            }
        }
        
        // Return database coaches only (no fallback to dummy data)
        return $coaches;
    }







    private function getCoachesBySpotFromDummy($sport){
        $sport = strtolower(trim($sport));
        $all = $this->getAll();
        $out = [];
        foreach ($all as $c) {
            if (strtolower($c['sport']) === $sport) {
                $out[] = $c;
            }
        }
        return $out;
    }










    public function getAll(){
        return [
            [
                'id' => 1,
                'name' => 'Thimira Jayasingha',
                'image' => URLROOT . '/public/images/coaches/feature/badmin2.jpg',
                'gender' => 'male',
                'availability' => 'available',
                'rating' => '4.9',
                'location' => 'Colombo 10',
                'sport' => 'Badminton',
                'featured' => true,
                'mobile' => '+94711234567',
                'bio' => 'Professional badminton coach with 8+ years of experience. Former national level player specializing in technique improvement and competitive training.',
                'rate' => '2,500',
                'experience' => '8 years',
                'certification' => 'Level 3 Badminton Coach',
                'specialization' => ['Technique Training', 'Competitive Coaching', 'Fitness Conditioning'],
                'free_slots' => [
                    ['day' => 'Monday', 'time' => '4:00 PM - 5:00 PM', 'type' => 'Group Session'],
                    ['day' => 'Wednesday', 'time' => '5:00 PM - 6:00 PM', 'type' => 'Beginner Class'],
                    ['day' => 'Saturday', 'time' => '9:00 AM - 10:00 AM', 'type' => 'Free Trial']
                ],
                'achievements' => ['National Championship 2019', 'Best Coach Award 2021'],
                'languages' => ['Sinhala', 'English']
            ],
            [
                'id' => 2,
                'name' => 'Dawn Staly',
                'image' => URLROOT . '/public/images/coaches/feature/swimm1.jpg',
                'gender' => 'female',
                'availability' => 'unavailable',
                'rating' => '2.9',
                'location' => 'Colombo 10',
                'sport' => 'Swimming',
                'featured' => true,
                'mobile' => '+94712234567',
                'bio' => 'Swimming instructor specializing in beginner and intermediate levels. Focus on water safety and proper technique.',
                'rate' => '3,000',
                'experience' => '6 years',
                'certification' => 'ASCA Level 2',
                'specialization' => ['Beginner Lessons', 'Water Safety', 'Stroke Technique'],
                'free_slots' => [
                    ['day' => 'Tuesday', 'time' => '3:00 PM - 4:00 PM', 'type' => 'Kids Session'],
                    ['day' => 'Friday', 'time' => '4:00 PM - 5:00 PM', 'type' => 'Adult Beginners']
                ],
                'achievements' => ['Swim Safety Instructor 2020'],
                'languages' => ['Sinhala', 'English', 'Tamil']
            ],
            [
                'id' => 3,
                'name' => 'Roomy Hassan',
                'image' => URLROOT . '/public/images/coaches/feature/roomy.jpg',
                'gender' => 'male',
                'availability' => 'available',
                'rating' => '3.5',
                'location' => 'Colombo 6',
                'sport' => 'Football',
                'featured' => true,
                'mobile' => '+94713234567',
                'bio' => 'Football coach with extensive experience in youth development and team strategy. Former professional player.',
                'rate' => '2,000',
                'experience' => '10 years',
                'certification' => 'AFC B License',
                'specialization' => ['Youth Development', 'Team Strategy', 'Fitness Training'],
                'free_slots' => [
                    ['day' => 'Thursday', 'time' => '4:30 PM - 5:30 PM', 'type' => 'Youth Training'],
                    ['day' => 'Sunday', 'time' => '8:00 AM - 9:00 AM', 'type' => 'Free Workshop']
                ],
                'achievements' => ['Regional Champions 2018', 'Youth Development Award 2022'],
                'languages' => ['Sinhala', 'English']
            ],
            [
                'id' => 4,
                'name' => 'Jony Rukshan',
                'image' => URLROOT . '/public/images/coaches/feature/foot3.jpg',
                'gender' => 'male',
                'availability' => 'available',
                'rating' => '4.0',
                'location' => 'Colombo 3',
                'sport' => 'Football',
                'featured' => true,
                'mobile' => '+94714234567',
                'bio' => 'Passionate football coach focusing on technical skills and game intelligence for all age groups.',
                'rate' => '1,800',
                'experience' => '5 years',
                'certification' => 'National Coaching Certificate',
                'specialization' => ['Technical Skills', 'Game Strategy', 'Physical Conditioning'],
                'free_slots' => [
                    ['day' => 'Monday', 'time' => '5:00 PM - 6:00 PM', 'type' => 'Skills Clinic'],
                    ['day' => 'Saturday', 'time' => '10:00 AM - 11:00 AM', 'type' => 'Free Trial']
                ],
                'achievements' => ['Community Coach Award 2021'],
                'languages' => ['Sinhala', 'English']
            ],
            [
                'id' => 5,
                'name' => 'Jonathan Carls',
                'image' => URLROOT . '/public/images/coaches/feature/tbadminton.jpg',
                'gender' => 'male',
                'availability' => 'available',
                'rating' => '3.8',
                'location' => 'Colombo 7',
                'sport' => 'Badminton',
                'featured' => true,
                'mobile' => '+94715234567',
                'bio' => 'International badminton coach with experience training national level players and beginners alike.',
                'rate' => '3,500',
                'experience' => '12 years',
                'certification' => 'BWF Level 2 Coach',
                'specialization' => ['Advanced Techniques', 'Tournament Preparation', 'Mental Training'],
                'free_slots' => [
                    ['day' => 'Wednesday', 'time' => '6:00 PM - 7:00 PM', 'type' => 'Advanced Session'],
                    ['day' => 'Sunday', 'time' => '3:00 PM - 4:00 PM', 'type' => 'Strategy Workshop']
                ],
                'achievements' => ['International Coach Certification', 'National Team Coach 2020'],
                'languages' => ['English', 'Sinhala', 'Hindi']
            ],
            [
                'id' => 6,
                'name' => 'Thenuka Ranavira',
                'image' => '',
                'gender' => 'male',
                'availability' => 'available',
                'rating' => '4.2',
                'location' => 'Colombo 5',
                'sport' => 'Tennis',
                'featured' => false,
                'mobile' => '+94716234567',
                'bio' => 'Tennis professional with focus on individual technique development and match play strategies.',
                'rate' => '2,800',
                'experience' => '7 years',
                'certification' => 'USPTA Certified',
                'specialization' => ['Individual Training', 'Match Strategy', 'Footwork Drills'],
                'free_slots' => [
                    ['day' => 'Friday', 'time' => '4:00 PM - 5:00 PM', 'type' => 'Beginner Class'],
                    ['day' => 'Saturday', 'time' => '2:00 PM - 3:00 PM', 'type' => 'Free Assessment']
                ],
                'achievements' => ['Regional Tournament Winner 2019'],
                'languages' => ['Sinhala', 'English']
            ],
            [
                'id' => 7,
                'name' => 'Dinesh Gamage',
                'image' => '',
                'gender' => 'male',
                'availability' => 'unavailable',
                'rating' => '3.9',
                'location' => 'Colombo 10',
                'sport' => 'Football',
                'featured' => false,
                'mobile' => '+94717234567',
                'bio' => 'Dedicated football coach with expertise in fitness training and team coordination.',
                'rate' => '2,200',
                'experience' => '4 years',
                'certification' => 'National Coaching Diploma',
                'specialization' => ['Fitness Training', 'Team Coordination', 'Defensive Strategies'],
                'free_slots' => [
                    ['day' => 'Tuesday', 'time' => '5:00 PM - 6:00 PM', 'type' => 'Fitness Session']
                ],
                'achievements' => ['Best Defensive Coach 2021'],
                'languages' => ['Sinhala']
            ],
            [
                'id' => 8,
                'name' => 'Risiru Perera',
                'image' => '',
                'gender' => 'male',
                'availability' => 'available',
                'rating' => '4.6',
                'location' => 'Colombo 10',
                'sport' => 'Swimming',
                'featured' => false,
                'mobile' => '+94718234567',
                'bio' => 'Expert swimming coach specializing in competitive swimming and advanced techniques.',
                'rate' => '3,200',
                'experience' => '9 years',
                'certification' => 'ASCA Level 3',
                'specialization' => ['Competitive Swimming', 'Advanced Techniques', 'Endurance Training'],
                'free_slots' => [
                    ['day' => 'Monday', 'time' => '3:00 PM - 4:00 PM', 'type' => 'Technique Workshop'],
                    ['day' => 'Thursday', 'time' => '5:00 PM - 6:00 PM', 'type' => 'Free Assessment'],
                    ['day' => 'Sunday', 'time' => '10:00 AM - 11:00 AM', 'type' => 'Beginner Session']
                ],
                'achievements' => ['National Swimming Coach 2022', 'Olympic Training Program'],
                'languages' => ['Sinhala', 'English']
            ]
        ];
    }
}