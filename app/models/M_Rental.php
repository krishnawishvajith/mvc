<?php
class M_Rental {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAllRentals() {
        // Fetch only approved rental shops from database with primary image
        $this->db->query('SELECT rs.*, 
            (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id AND is_primary = 1 LIMIT 1) as uploaded_image
            FROM rental_shops rs 
            WHERE rs.status = "approved"
            ORDER BY rating DESC');
        $rentals = $this->db->resultSet();
        
        // Get equipment types and features for each rental shop
        foreach($rentals as $rental) {
            $rental->equipment_types = $this->getRentalEquipmentTypes($rental->id);
            $rental->features = $this->getRentalFeatures($rental->id);
            
            // Set correct image path - prefer uploaded image over old image column
            if (!empty($rental->uploaded_image)) {
                $rental->display_image = $rental->uploaded_image;
            } else {
                $rental->display_image = $rental->image;
            }
        }
        
        return $rentals;
    }

    private function getRentalEquipmentTypes($rental_id) {
        $this->db->query('SELECT equipment_type FROM rental_equipment_types WHERE rental_id = :rental_id');
        $this->db->bind(':rental_id', $rental_id);
        $types = $this->db->resultSet();
        
        // Convert to simple array of equipment types
        $typesArray = [];
        foreach($types as $type) {
            $typesArray[] = $type->equipment_type;
        }
        
        return $typesArray;
    }

    private function getRentalFeatures($rental_id) {
        $this->db->query('SELECT feature_name FROM rental_features WHERE rental_id = :rental_id');
        $this->db->bind(':rental_id', $rental_id);
        $features = $this->db->resultSet();
        
        // Convert to simple array of feature names
        $featuresArray = [];
        foreach($features as $feature) {
            $featuresArray[] = $feature->feature_name;
        }
        
        return $featuresArray;
    }

    public function getRentalById($id) {
        // Fetch single approved rental shop from database with primary image
        $this->db->query('SELECT rs.*, 
            (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id AND is_primary = 1 LIMIT 1) as uploaded_image
            FROM rental_shops rs 
            WHERE rs.id = :id AND rs.status = "approved"');
        $this->db->bind(':id', $id);
        $rental = $this->db->single();
        
        if($rental) {
            $rental->equipment_types = $this->getRentalEquipmentTypes($rental->id);
            $rental->features = $this->getRentalFeatures($rental->id);
            
            // Set correct image path
            if (!empty($rental->uploaded_image)) {
                $rental->display_image = $rental->uploaded_image;
            } else {
                $rental->display_image = $rental->image;
            }
        }
        
        return $rental;
    }

    public function searchRentals($filters = []) {
        // Build base query
        $sql = 'SELECT * FROM rental_shops WHERE 1=1';
        
        // Add filters dynamically
        if(!empty($filters['category'])) {
            $sql .= ' AND category = :category';
        }
        if(!empty($filters['location'])) {
            $sql .= ' AND location LIKE :location';
        }
        if(!empty($filters['status'])) {
            $sql .= ' AND status = :status';
        }
        if(!empty($filters['delivery'])) {
            $sql .= ' AND delivery = :delivery';
        }
        if(!empty($filters['rating'])) {
            $sql .= ' AND rating >= :rating';
        }
        
        $sql .= ' ORDER BY rating DESC';
        
        $this->db->query($sql);
        
        // Bind parameters
        if(!empty($filters['category'])) {
            $this->db->bind(':category', $filters['category']);
        }
        if(!empty($filters['location'])) {
            $this->db->bind(':location', '%' . $filters['location'] . '%');
        }
        if(!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        if(!empty($filters['delivery'])) {
            $this->db->bind(':delivery', $filters['delivery']);
        }
        if(!empty($filters['rating'])) {
            $this->db->bind(':rating', $filters['rating']);
        }
        
        $rentals = $this->db->resultSet();
        
        // Get equipment types and features for each rental shop
        foreach($rentals as $rental) {
            $rental->equipment_types = $this->getRentalEquipmentTypes($rental->id);
            $rental->features = $this->getRentalFeatures($rental->id);
        }
        
        return $rentals;
    }
}
?>