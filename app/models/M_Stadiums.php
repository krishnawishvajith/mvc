<?php
class M_Stadiums {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAllStadiums() {
        $this->db->query('SELECT * FROM stadiums WHERE approval_status = "approved" ORDER BY id ASC');
        $stadiums = $this->db->resultSet();
        
        // Get features for each stadium
        foreach($stadiums as $stadium) {
            $stadium->features = $this->getStadiumFeatures($stadium->id);
            // Calculate how many features are hidden (more than 3)
            $stadium->more_features = count($stadium->features) > 3 ? count($stadium->features) - 3 : 0;
        }
        
        return $stadiums;
    }

    private function getStadiumFeatures($stadium_id) {
        $this->db->query('SELECT feature_name FROM stadium_features WHERE stadium_id = :stadium_id');
        $this->db->bind(':stadium_id', $stadium_id);
        $features = $this->db->resultSet();
        
        // Convert to simple array of feature names
        $featureArray = [];
        foreach($features as $feature) {
            $featureArray[] = $feature->feature_name;
        }
        
        return $featureArray;
    }

    public function getStadiumById($id) {
        $this->db->query('SELECT s.*, 
            COALESCE(s.owner_id, 0) as owner_id,
            COALESCE(s.average_rating, 0) as rating
            FROM stadiums s 
            WHERE s.id = :id AND s.approval_status = "approved"');
        $this->db->bind(':id', $id);
        $stadium = $this->db->single();
        
        if($stadium) {
            $stadium->features = $this->getStadiumFeatures($stadium->id);
            $stadium->more_features = count($stadium->features) > 3 ? count($stadium->features) - 3 : 0;
        }
        
        return $stadium;
    }

    public function getNearbyStadiums($location, $excludeId, $limit = 4) {
        // For now, get stadiums in similar location
        $this->db->query('SELECT * FROM stadiums WHERE location LIKE :location AND id != :exclude_id AND approval_status = "approved" ORDER BY rating DESC LIMIT :limit');
        $this->db->bind(':location', '%' . $location . '%');
        $this->db->bind(':exclude_id', $excludeId);
        $this->db->bind(':limit', $limit);
        
        $stadiums = $this->db->resultSet();
        
        // Get features for each stadium
        foreach($stadiums as $stadium) {
            $stadium->features = $this->getStadiumFeatures($stadium->id);
            $stadium->more_features = count($stadium->features) > 3 ? count($stadium->features) - 3 : 0;
        }
        
        return $stadiums;
    }

    public function getStadiumGallery($stadium_id) {
        try {
            // Get uploaded images from stadium_images table
            $this->db->query('SELECT image_path, is_primary, display_order 
                FROM stadium_images 
                WHERE stadium_id = :stadium_id 
                ORDER BY is_primary DESC, display_order ASC');
            $this->db->bind(':stadium_id', $stadium_id);
            $images = $this->db->resultSet();
            
            // Extract just the image paths
            $imagePaths = [];
            foreach($images as $image) {
                $imagePaths[] = [
                    'path' => $image->image_path,
                    'is_uploaded' => (strpos($image->image_path, 'stadium_') === 0)
                ];
            }
            
            // If no images found, return default
            if (empty($imagePaths)) {
                // Get primary image from stadiums table as fallback
                $this->db->query('SELECT image FROM stadiums WHERE id = :stadium_id');
                $this->db->bind(':stadium_id', $stadium_id);
                $stadium = $this->db->single();
                
                if ($stadium && $stadium->image) {
                    $imagePaths[] = [
                        'path' => $stadium->image,
                        'is_uploaded' => (strpos($stadium->image, 'stadium_') === 0)
                    ];
                } else {
                    $imagePaths[] = [
                        'path' => 'default-stadium.jpg',
                        'is_uploaded' => false
                    ];
                }
            }
            
            return $imagePaths;
        } catch (Exception $e) {
            error_log('Error in getStadiumGallery: ' . $e->getMessage());
            return [[
                'path' => 'default-stadium.jpg',
                'is_uploaded' => false
            ]];
        }
    }


    public function getStadiumReviews($stadium_id, $limit = 5) {
        try {
            // Get actual reviews from database if they exist
            $this->db->query("SELECT r.*, 
                u.first_name, u.last_name
                FROM reviews r
                JOIN users u ON r.customer_id = u.id
                WHERE r.stadium_id = :stadium_id AND r.status = 'approved'
                ORDER BY r.created_at DESC
                LIMIT :limit");
            
            $this->db->bind(':stadium_id', $stadium_id);
            $this->db->bind(':limit', $limit);
            
            $reviews = $this->db->resultSet();
            
            if (count($reviews) > 0) {
                return $reviews;
            }
        } catch (Exception $e) {
            // Reviews table might not exist yet
        }
        
        // Return sample reviews if no reviews in database
        return [
            [
                'id' => 1,
                'first_name' => 'Krishna',
                'last_name' => 'Wishvajith',
                'rating' => 5,
                'comment' => 'Excellent facilities and well-maintained ground. The lighting system is perfect for evening matches.',
                'created_at' => '2025-01-15',
                'verified_booking' => true
            ],
            [
                'id' => 2,
                'first_name' => 'Kulakshi',
                'last_name' => 'Thathsarani',
                'rating' => 4,
                'comment' => 'Great stadium with good parking facilities. Only minor issue was the changing room could be cleaner.',
                'created_at' => '2025-01-10',
                'verified_booking' => true
            ],
            [
                'id' => 3,
                'first_name' => 'Dinesh',
                'last_name' => 'Sulakshana',
                'rating' => 5,
                'comment' => 'Professional quality ground and excellent customer service. Highly recommended!',
                'created_at' => '2025-01-08',
                'verified_booking' => false
            ],
            [
                'id' => 4,
                'first_name' => 'Kalana',
                'last_name' => 'Ekanayake',
                'rating' => 4,
                'comment' => 'Good value for money. The turf quality is excellent and perfect for tournaments.',
                'created_at' => '2025-01-05',
                'verified_booking' => true
            ]
        ];
    }

    public function searchStadiums($filters = []) {
        // Build base query
        $sql = 'SELECT * FROM stadiums WHERE approval_status = "approved"';
        
        // Add filters dynamically
        if(!empty($filters['type'])) {
            $sql .= ' AND type = :type';
        }
        if(!empty($filters['category'])) {
            $sql .= ' AND category = :category';
        }
        if(!empty($filters['location'])) {
            $sql .= ' AND location LIKE :location';
        }
        if(!empty($filters['status'])) {
            $sql .= ' AND status = :status';
        }
        
        $sql .= ' ORDER BY id ASC';
        
        $this->db->query($sql);
        
        // Bind parameters
        if(!empty($filters['type'])) {
            $this->db->bind(':type', $filters['type']);
        }
        if(!empty($filters['category'])) {
            $this->db->bind(':category', $filters['category']);
        }
        if(!empty($filters['location'])) {
            $this->db->bind(':location', '%' . $filters['location'] . '%');
        }
        if(!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        
        $stadiums = $this->db->resultSet();
        
        // Get features for each stadium
        foreach($stadiums as $stadium) {
            $stadium->features = $this->getStadiumFeatures($stadium->id);
            $stadium->more_features = count($stadium->features) > 3 ? count($stadium->features) - 3 : 0;
        }
        
        return $stadiums;
    }
}
?>