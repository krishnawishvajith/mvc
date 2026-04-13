<?php
class M_Rental_packages {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    private function createSlug($text) {
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($text)));
        $slug = trim($slug, '-');
        return $slug ?: 'package-' . uniqid();
    }

    public function getPackages() {
        // Try to fetch from database first
        try {
            $this->db->query('SELECT * FROM rental_service_packages WHERE is_active = 1 ORDER BY display_order ASC');
            $dbPackages = $this->db->resultSet();
            
            if (count($dbPackages) > 0) {
                $packages = [];
                foreach ($dbPackages as $pkg) {
                    $key = $this->createSlug($pkg->name);
                    if (isset($packages[$key])) {
                        $key .= '-' . $pkg->id;
                    }
                    $features = [];
                    
                    // Build features list from package attributes
                    $features[] = $pkg->shop_listings . ' Rental Shop Listings';
                    $features[] = $pkg->images_per_listing . ' Images per Listing';
                    if ($pkg->phone_contact) $features[] = 'Phone Contact';
                    if ($pkg->email_contact) $features[] = 'Email Contact';
                    if ($pkg->amenities_display) $features[] = 'Amenities Display';
                    if ($pkg->priority_placement) $features[] = 'Priority Placement';
                    if ($pkg->email_phone_support) $features[] = 'Email & Phone Support';
                    
                    $packages[$key] = [
                        'id' => $pkg->id,
                        'slug' => $key,
                        'name' => $pkg->name,
                        'price' => $pkg->price,
                        'duration' => $pkg->duration_text,
                        'listings' => $pkg->shop_listings,
                        'images_per_listing' => $pkg->images_per_listing,
                        'color' => $pkg->color,
                        'icon' => $pkg->icon,
                        'popular' => (bool)$pkg->is_popular,
                        'features' => $features
                    ];
                }
                return $packages;
            }
        } catch (Exception $e) {
            error_log('M_Rental_packages: Database error: ' . $e->getMessage());
        }
        
        // Fallback to hardcoded data if database not available
        return [
            'sport-equipment-rental-service-owner-package' => [
                'id' => 1,
                'slug' => 'sport-equipment-rental-service-owner-package',
                'name' => 'Sport Equipment Rental Service Owner Package',
                'price' => 12300,
                'duration' => 'Listings Valid For 3 Months',
                'listings' => 5,
                'images_per_listing' => 5,
                'color' => 'standard',
                'icon' => '⚡',
                'popular' => true,
                'features' => [
                    '5 Rental Shop Listings',
                    '5 Images per Listing',
                    'Phone + Email Contact',
                    'Amenities Display',
                    'Email & Phone Support',
                    'Priority Placement'
                ]
            ]
        ];
    }

    public function getPackageById($id) {
        try {
            $this->db->query('SELECT * FROM rental_service_packages WHERE id = :id AND is_active = 1');
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('M_Rental_packages: Database error: ' . $e->getMessage());
            return null;
        }
    }
}
?>