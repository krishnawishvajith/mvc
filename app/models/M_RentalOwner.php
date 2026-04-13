<?php
class M_RentalOwner {
    private $db;

    public function __construct(){
        try {
            $this->db = new Database();
        } catch (Exception $e) {
            error_log('Database connection error in M_RentalOwner: ' . $e->getMessage());
        }
    }

    // Get rental owner dashboard stats
    public function getOwnerStats($owner_id) {
        try {
            if (!$this->db) {
                return $this->getDefaultStats();
            }

            // Get shop counts by status
            $this->db->query('SELECT 
                COUNT(*) as total_shops,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_shops,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_shops,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_shops
                FROM rental_shops 
                WHERE owner_id = :owner_id');
            $this->db->bind(':owner_id', $owner_id);
            $shopStats = $this->db->single();

            $stats = [
                'total_shops' => $shopStats->total_shops ?? 0,
                'pending_shops' => $shopStats->pending_shops ?? 0,
                'approved_shops' => $shopStats->approved_shops ?? 0,
                'rejected_shops' => $shopStats->rejected_shops ?? 0,
                'active_rentals' => 0, // TODO: Implement when rentals table exists
                'monthly_revenue' => 0, // TODO: Implement when bookings exist
                'total_customers' => 0, // TODO: Implement when bookings exist
                'equipment_items' => 0,
                'average_rating' => 0
            ];

            return $stats;

        } catch (Exception $e) {
            error_log('Error in getOwnerStats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    private function getDefaultStats() {
        return [
            'total_shops' => 0,
            'active_rentals' => 0,
            'monthly_revenue' => 0,
            'total_customers' => 0,
            'equipment_items' => 0,
            'average_rating' => 0.0
        ];
    }

    // Get recent rentals for owner
    public function getRecentRentals($owner_id, $limit = 5) {
        try {
            // For now return sample data
            return [
                [
                    'id' => 'RT001',
                    'customer' => 'Krishna Wishvajith',
                    'equipment' => 'Cricket Bat Set',
                    'shop' => 'Pro Sports Gear',
                    'date' => '2025-01-25',
                    'duration' => '3 days',
                    'amount' => 1500,
                    'status' => 'Active',
                    'return_date' => '2025-01-28'
                ],
                [
                    'id' => 'RT002',
                    'customer' => 'Kulakshi Thathsarani',
                    'equipment' => 'Football Kit',
                    'shop' => 'Football Gear Hub',
                    'date' => '2025-01-26',
                    'duration' => '1 day',
                    'amount' => 800,
                    'status' => 'Returned',
                    'return_date' => '2025-01-27'
                ]
            ];
        } catch (Exception $e) {
            error_log('Error in getRecentRentals: ' . $e->getMessage());
            return [];
        }
    }

    // Get upcoming rental schedules
    public function getUpcomingSchedules($owner_id) {
        try {
            return [
                [
                    'equipment' => 'Tennis Racket Set',
                    'customer' => 'Dinesh Sulakshana',
                    'date' => '27',
                    'month' => 'JAN',
                    'time' => 'Pickup: 10:00 AM',
                    'status' => 'Confirmed',
                    'shop' => 'Tennis Pro Rentals'
                ],
                [
                    'equipment' => 'Basketball Kit',
                    'customer' => 'Kalana Ekanayake',
                    'date' => '28',
                    'month' => 'JAN', 
                    'time' => 'Return: 5:00 PM',
                    'status' => 'Return Due',
                    'shop' => 'Basketball Gear Store'
                ]
            ];
        } catch (Exception $e) {
            error_log('Error in getUpcomingSchedules: ' . $e->getMessage());
            return [];
        }
    }

    // Get revenue overview
    public function getRevenueOverview($owner_id) {
        try {
            return [
                'this_month' => 25000,
                'last_month' => 22000,
                'growth_percentage' => 13.6,
                'pending_payments' => 3500,
                'next_payout_date' => '2025-02-01'
            ];
        } catch (Exception $e) {
            error_log('Error in getRevenueOverview: ' . $e->getMessage());
            return [];
        }
    }

    // Get shop summary
    public function getShopSummary($owner_id) {
        try {
            // Get package limits
            $limits = $this->getPackageLimits($owner_id);
            
            // Get shop count by status
            $this->db->query('SELECT 
                COUNT(*) as total_shops,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_shops
                FROM rental_shops 
                WHERE owner_id = :owner_id');
            $this->db->bind(':owner_id', $owner_id);
            $shopCount = $this->db->single();
            
            return [
                'total_shops' => $shopCount->total_shops ?? 0,
                'approved_shops' => $shopCount->approved_shops ?? 0,
                'total_equipment' => 0, // TODO: Sum from equipment count
                'package_type' => 'Standard', // TODO: Get from package
                'shops_limit' => $limits['shops_limit'] ?? 5,
                'current_shops' => $limits['current_shops'] ?? 0,
                'can_add_more' => $limits['can_add_more'] ?? false
            ];
        } catch (Exception $e) {
            error_log('Error in getShopSummary: ' . $e->getMessage());
            return [];
        }
    }

    // Get package information
    public function getPackageInfo($owner_id) {
        try {
            // Get active package purchase details
            $this->db->query('SELECT rpp.*, rsp.name as package_name, rsp.shop_listings, rsp.images_per_listing, rsp.price, rsp.description
                FROM rental_package_purchases rpp
                JOIN rental_service_packages rsp ON rpp.package_id = rsp.id
                WHERE rpp.user_id = :owner_id AND rpp.package_status = "active"
                ORDER BY rpp.purchased_at DESC LIMIT 1');
            
            $this->db->bind(':owner_id', $owner_id);
            $package = $this->db->single();
            
            if ($package) {
                // Calculate days since purchase
                $purchaseDate = new DateTime($package->purchased_at);
                $today = new DateTime();
                $daysSincePurchase = $purchaseDate->diff($today)->days;
                
                // Calculate expiry (90 days from purchase - 3 months validity)
                $expiryDate = clone $purchaseDate;
                $expiryDate->modify('+90 days');
                $daysUntilExpiry = $today->diff($expiryDate)->days;
                $isExpiringSoon = $daysUntilExpiry <= 14 && $expiryDate > $today; // Warning 2 weeks before expiry
                $hasExpired = $today > $expiryDate;
                
                return [
                    'package_id' => $package->package_id ?? null,
                    'purchase_id' => $package->id ?? null,
                    'package_name' => $package->package_name ?? 'No Package',
                    'package_status' => $package->package_status ?? 'inactive',
                    'shops_limit' => $package->shop_listings ?? 0,
                    'photos_limit' => $package->images_per_listing ?? 0,
                    'monthly_price' => $package->price ?? 0,
                    'amount_paid' => $package->payment_amount ?? $package->price ?? 0,
                    'purchased_at' => $package->purchased_at ?? null,
                    'purchased_date_formatted' => $purchaseDate->format('M d, Y'),
                    'expiry_date' => $expiryDate->format('Y-m-d H:i:s'),
                    'expiry_date_formatted' => $expiryDate->format('M d, Y'),
                    'days_since_purchase' => $daysSincePurchase,
                    'days_until_expiry' => $hasExpired ? 0 : $daysUntilExpiry,
                    'is_expiring_soon' => $isExpiringSoon,
                    'has_expired' => $hasExpired,
                    'stripe_payment_id' => $package->stripe_payment_intent_id ?? null,
                    'support_type' => 'Email & Phone Support',
                    'description' => $package->description ?? ''
                ];
            }
            
            return [
                'package_name' => 'No Active Package',
                'package_status' => 'inactive',
                'shops_limit' => 0,
                'photos_limit' => 0,
                'monthly_price' => 0,
                'support_type' => 'N/A'
            ];
        } catch (Exception $e) {
            error_log('Error in getPackageInfo: ' . $e->getMessage());
            return [
                'package_name' => 'Error Loading Package',
                'package_status' => 'error',
                'shops_limit' => 0,
                'photos_limit' => 0
            ];
        }
    }

    // Get all shops for owner
    public function getAllShops($owner_id) {
        try {
            // Fetch from database using existing column names
            $this->db->query('SELECT rs.*, 
                COALESCE(
                    (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id AND is_primary = 1 LIMIT 1),
                    (SELECT image_path FROM rental_shop_images WHERE shop_id = rs.id LIMIT 1)
                ) as primary_image,
                (SELECT COUNT(*) FROM rental_shop_images WHERE shop_id = rs.id) as image_count
                FROM rental_shops rs
                WHERE rs.owner_id = :owner_id
                ORDER BY rs.created_at DESC');
            
            $this->db->bind(':owner_id', $owner_id);
            $shops = $this->db->resultSet();
            
            // If no shops found, return empty array
            if (empty($shops)) {
                return [];
            }
            
            // Enrich each shop with equipment types and amenities
            foreach ($shops as $shop) {
                // Map old column names to what the view expects
                $shop->shop_name = $shop->store_name ?? '';
                $shop->address = $shop->address ?? '';
                $shop->contact_email = $shop->email ?? '';
                $shop->contact_phone = $shop->phone ?? '';
                $shop->operating_hours = $shop->hours ?? '';
                $shop->daily_rate = 0; // Not in old structure
                $shop->equipment_count = 0;
                $shop->description = $shop->description ?? '';
                
                // Get equipment types from rental_equipment_types
                $this->db->query('SELECT equipment_type FROM rental_equipment_types WHERE rental_id = :shop_id');
                $this->db->bind(':shop_id', $shop->id);
                $types = $this->db->resultSet();
                $shop->equipment_types = array_column($types, 'equipment_type');
                
                // Get features from rental_features
                $this->db->query('SELECT feature_name FROM rental_features WHERE rental_id = :shop_id');
                $this->db->bind(':shop_id', $shop->id);
                $features = $this->db->resultSet();
                $shop->features = array_column($features, 'feature_name');
                
                // Set image path - prefer new rental_shop_images table, fallback to old image column
                if (!empty($shop->primary_image)) {
                    // Use image from rental_shop_images table (already full relative path)
                    $shop->image = $shop->primary_image;
                } else if (!empty($shop->image) && $shop->image !== 'placeholder.jpg') {
                    // Use old image column - check if it already has path prefix
                    if (strpos($shop->image, 'uploads/') === false && strpos($shop->image, '/') === false) {
                        // Bare filename - prepend the correct folder path
                        $shop->image = 'uploads/rental_shops/' . $shop->image;
                    }
                    // else: already has path prefix, keep as-is
                } else {
                    $shop->image = null; // Will use local fallback in view
                }
                
                $shop->rentals_count = 0;
            }
            
            return $shops;
            
            /*// OLD DUMMY DATA - REMOVED
            return [
                (object)[
                    'id' => 1,
                    'shop_name' => 'Pro Sports Gear Rentals',
                    'address' => '123 Galle Road, Colombo 03',
                    'description' => 'Complete sports equipment rental service with premium quality gear for all sports including cricket, football, and tennis.',
                    'daily_rate' => 1500,
                    'contact_email' => 'rentals@prosportsgear.lk',
                    'contact_phone' => '+94 71 234 5678',
                    'operating_hours' => 'Mon-Sun: 8:00 AM - 8:00 PM',
                    'image' => 'equ1.jpg',
                    'status' => 'active',
                    'equipment_count' => 85,
                    'rentals_count' => 120,
                    'category' => 'Multi-Sport',
                    'equipment_types' => ['Cricket', 'Football', 'Tennis', 'Basketball'],
                    'features' => ['Home Delivery', 'Quality Guarantee', 'Online Booking']
                ],
                (object)[
                    'id' => 2,
                    'shop_name' => 'Cricket Zone Equipment',
                    'address' => '456 Duplication Road, Colombo 07',
                    'description' => 'Specialized cricket equipment rental with professional grade gear including bats, pads, gloves, and protective equipment.',
                    'daily_rate' => 800,
                    'contact_email' => 'info@cricketzone.lk',
                    'contact_phone' => '+94 77 345 6789',
                    'operating_hours' => 'Mon-Sat: 9:00 AM - 7:00 PM',
                    'image' => 'equ1.jpg',
                    'status' => 'active',
                    'equipment_count' => 45,
                    'rentals_count' => 65,
                    'category' => 'Cricket',
                    'equipment_types' => ['Cricket'],
                    'features' => ['Expert Advice', 'Equipment Maintenance', 'Bulk Discounts']
                ],
                (object)[
                    'id' => 3,
                    'shop_name' => 'Football Gear Hub',
                    'address' => '789 Galle Road, Dehiwala',
                    'description' => 'Premium football equipment rental for players and teams including balls, shoes, goalkeeper gear, and training equipment.',
                    'daily_rate' => 1200,
                    'contact_email' => 'hello@footballgearhub.lk',
                    'contact_phone' => '+94 70 456 7890',
                    'operating_hours' => 'Mon-Sun: 7:00 AM - 9:00 PM',
                    'image' => 'equ1.jpg',
                    'status' => 'active',
                    'equipment_count' => 60,
                    'rentals_count' => 95,
                    'category' => 'Football',
                    'equipment_types' => ['Football'],
                    'features' => ['Team Packages', 'Goalkeeper Gear', 'Size Fitting']
                ],
                (object)[
                    'id' => 4,
                    'shop_name' => 'Tennis Pro Rentals',
                    'address' => '321 Hotel Road, Mount Lavinia',
                    'description' => 'High-quality tennis equipment rental with expert guidance including rackets, balls, nets, and court equipment.',
                    'daily_rate' => 1000,
                    'contact_email' => 'rentals@tennispro.lk',
                    'contact_phone' => '+94 76 567 8901',
                    'operating_hours' => 'Tue-Sun: 8:00 AM - 6:00 PM',
                    'image' => 'equ1.jpg',
                    'status' => 'active',
                    'equipment_count' => 35,
                    'rentals_count' => 78,
                    'category' => 'Tennis',
                    'equipment_types' => ['Tennis'],
                    'features' => ['Racket Stringing', 'Professional Advice', 'Tournament Gear']
                ]
            ];*/
        } catch (Exception $e) {
            error_log('Error in getAllShops: ' . $e->getMessage());
            return [];
        }
    }

    // Add new shop
    public function addShop($owner_id, $shopData, $images = []) {
        try {
            if (!$this->db) {
                return false;
            }

            // Set default image from uploaded images or placeholder
            // Store full relative path so display works even without rental_shop_images table
            $defaultImage = !empty($images) ? $images[0] : 'placeholder.jpg';
            
            // Insert main shop data using existing table structure
            $this->db->query('INSERT INTO rental_shops (
                owner_id, store_name, category, location, phone, whatsapp, email, 
                address, description, hours, delivery, status, owner, image, rating, experience
            ) VALUES (
                :owner_id, :store_name, :category, :location, :phone, :whatsapp, :email,
                :address, :description, :hours, :delivery, :status, :owner, :image, :rating, :experience
            )');
            
            $this->db->bind(':owner_id', $owner_id);
            $this->db->bind(':store_name', $shopData['shop_name']);
            $this->db->bind(':category', $shopData['category']);
            $this->db->bind(':location', $shopData['district'] ?? 'Not specified');
            $this->db->bind(':phone', $shopData['contact_phone']);
            $this->db->bind(':whatsapp', $shopData['contact_phone']); // Use same as phone
            $this->db->bind(':email', $shopData['contact_email']);
            $this->db->bind(':address', $shopData['address']);
            $this->db->bind(':description', $shopData['description']);
            $this->db->bind(':hours', $shopData['operating_hours']);
            $this->db->bind(':delivery', !empty($shopData['amenities']) && in_array('Home Delivery', $shopData['amenities']) ? 1 : 0);
            $this->db->bind(':status', $shopData['status'] ?? 'pending'); // Use status from controller (pending for approval)
            $this->db->bind(':owner', $_SESSION['user_name'] ?? 'Owner');
            $this->db->bind(':image', $defaultImage);
            $this->db->bind(':rating', 0.0);
            $this->db->bind(':experience', '0+ years');
            
            if (!$this->db->execute()) {
                return false;
            }
            
            $shopId = $this->db->lastInsertId();
            
            // Add equipment types using existing table name
            if (!empty($shopData['equipment_types'])) {
                foreach ($shopData['equipment_types'] as $type) {
                    $this->db->query('INSERT INTO rental_equipment_types (rental_id, equipment_type) VALUES (:rental_id, :type)');
                    $this->db->bind(':rental_id', $shopId);
                    $this->db->bind(':type', $type);
                    $this->db->execute();
                }
            }
            
            // Add amenities/features using existing table name
            if (!empty($shopData['amenities'])) {
                foreach ($shopData['amenities'] as $amenity) {
                    $this->db->query('INSERT INTO rental_features (rental_id, feature_name) VALUES (:rental_id, :feature)');
                    $this->db->bind(':rental_id', $shopId);
                    $this->db->bind(':feature', $amenity);
                    $this->db->execute();
                }
            }
            
            // Add images
            if (!empty($images)) {
                $order = 0;
                foreach ($images as $imagePath) {
                    $isPrimary = ($order === 0) ? 1 : 0;
                    $this->db->query('INSERT INTO rental_shop_images (shop_id, image_path, is_primary, display_order) VALUES (:shop_id, :image_path, :is_primary, :display_order)');
                    $this->db->bind(':shop_id', $shopId);
                    $this->db->bind(':image_path', $imagePath);
                    $this->db->bind(':is_primary', $isPrimary);
                    $this->db->bind(':display_order', $order);
                    $this->db->execute();
                    $order++;
                }
            }
            
            return $shopId;
        } catch (Exception $e) {
            error_log('Error in addShop: ' . $e->getMessage());
            return false;
        }
    }

    // Update shop
    public function updateShop($owner_id, $shop_id, $shopData, $newImages = []) {
        try {
            if (!$this->db) {
                return false;
            }

            // Verify ownership
            $this->db->query('SELECT id FROM rental_shops WHERE id = :shop_id AND owner_id = :owner_id');
            $this->db->bind(':shop_id', $shop_id);
            $this->db->bind(':owner_id', $owner_id);
            $shop = $this->db->single();
            
            if (!$shop) {
                error_log('Shop not found or user does not own this shop');
                return false;
            }

            // Update rental_shops table (adapting to user's existing column names)
            $this->db->query('UPDATE rental_shops SET 
                store_name = :shop_name,
                address = :address,
                location = :district,
                description = :description,
                category = :category,
                email = :contact_email,
                phone = :contact_phone,
                hours = :operating_hours,
                price = :daily_rate,
                experience = :equipment_count,
                delivery = :home_delivery,
                status = :status
                WHERE id = :shop_id AND owner_id = :owner_id');
            
            $this->db->bind(':shop_id', $shop_id);
            $this->db->bind(':owner_id', $owner_id);
            $this->db->bind(':shop_name', $shopData['shop_name']);
            $this->db->bind(':address', $shopData['address']);
            $this->db->bind(':district', $shopData['district']);
            $this->db->bind(':description', $shopData['description']);
            $this->db->bind(':category', $shopData['category']);
            $this->db->bind(':contact_email', $shopData['contact_email']);
            $this->db->bind(':contact_phone', $shopData['contact_phone']);
            $this->db->bind(':operating_hours', $shopData['operating_hours']);
            $this->db->bind(':daily_rate', $shopData['daily_rate']);
            $this->db->bind(':equipment_count', $shopData['equipment_count']);
            $this->db->bind(':home_delivery', in_array('Home Delivery', $shopData['amenities']) ? 1 : 0);
            $this->db->bind(':status', $shopData['status']);
            
            $this->db->execute();
            
            // Update equipment types
            if (isset($shopData['equipment_types'])) {
                // Delete old equipment types
                $this->db->query('DELETE FROM rental_equipment_types WHERE rental_id = :shop_id');
                $this->db->bind(':shop_id', $shop_id);
                $this->db->execute();
                
                // Insert new equipment types
                foreach ($shopData['equipment_types'] as $type) {
                    $this->db->query('INSERT INTO rental_equipment_types (rental_id, equipment_type) VALUES (:shop_id, :type)');
                    $this->db->bind(':shop_id', $shop_id);
                    $this->db->bind(':type', $type);
                    $this->db->execute();
                }
            }
            
            // Update amenities/features
            if (isset($shopData['amenities'])) {
                // Delete old features
                $this->db->query('DELETE FROM rental_features WHERE rental_id = :shop_id');
                $this->db->bind(':shop_id', $shop_id);
                $this->db->execute();
                
                // Insert new features
                foreach ($shopData['amenities'] as $amenity) {
                    $this->db->query('INSERT INTO rental_features (rental_id, feature_name) VALUES (:shop_id, :feature)');
                    $this->db->bind(':shop_id', $shop_id);
                    $this->db->bind(':feature', $amenity);
                    $this->db->execute();
                }
            }
            
            // Add new images if provided
            if (!empty($newImages)) {
                foreach ($newImages as $imagePath) {
                    $this->db->query('INSERT INTO rental_shop_images (shop_id, image_path, is_primary) VALUES (:shop_id, :image_path, 0)');
                    $this->db->bind(':shop_id', $shop_id);
                    $this->db->bind(':image_path', $imagePath);
                    $this->db->execute();
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log('Error in updateShop: ' . $e->getMessage());
            return false;
        }
    }

    // Get user's package info and check limits
    public function getPackageLimits($owner_id) {
        try {
            // Get user's active package purchase
            $this->db->query('SELECT rpp.*, rsp.shop_listings, rsp.images_per_listing 
                FROM rental_package_purchases rpp
                JOIN rental_service_packages rsp ON rpp.package_id = rsp.id
                WHERE rpp.user_id = :owner_id AND rpp.package_status = "active"
                ORDER BY rpp.purchased_at DESC LIMIT 1');
            
            $this->db->bind(':owner_id', $owner_id);
            $package = $this->db->single();
            
            if ($package) {
                // Get current shop count using owner_id column
                $this->db->query('SELECT COUNT(*) as count FROM rental_shops WHERE owner_id = :owner_id AND owner_id IS NOT NULL');
                $this->db->bind(':owner_id', $owner_id);
                $result = $this->db->single();
                $currentShops = $result ? $result->count : 0;
                
                return [
                    'shops_limit' => $package->shop_listings,
                    'current_shops' => $currentShops,
                    'can_add_more' => $currentShops < $package->shop_listings,
                    'images_per_shop' => $package->images_per_listing
                ];
            }
            
            // Default limits if no package found
            return [
                'shops_limit' => 5,
                'current_shops' => 0,
                'can_add_more' => true,
                'images_per_shop' => 5
            ];
        } catch (Exception $e) {
            error_log('Error in getPackageLimits: ' . $e->getMessage());
            // Return default limits on error
            return [
                'shops_limit' => 5,
                'current_shops' => 0,
                'can_add_more' => true,
                'images_per_shop' => 5
            ];
        }
    }

    // Get single shop
    public function getShop($owner_id, $shop_id) {
        try {
            // Fetch from database
            $this->db->query('SELECT rs.* FROM rental_shops rs WHERE rs.id = :shop_id AND rs.owner_id = :owner_id');
            $this->db->bind(':shop_id', $shop_id);
            $this->db->bind(':owner_id', $owner_id);
            $shop = $this->db->single();
            
            if (!$shop) {
                return null;
            }
            
            // Map old column names to what the view expects
            $shop->shop_name = $shop->store_name ?? '';
            $shop->contact_email = $shop->email ?? '';
            $shop->contact_phone = $shop->phone ?? '';
            $shop->operating_hours = $shop->hours ?? '';
            $shop->district = $shop->location ?? '';
            $shop->daily_rate = $shop->price ?? 0;
            $shop->equipment_count = $shop->experience ?? 0;
            
            // Get equipment types from rental_equipment_types table
            $this->db->query('SELECT equipment_type FROM rental_equipment_types WHERE rental_id = :shop_id');
            $this->db->bind(':shop_id', $shop_id);
            $types = $this->db->resultSet();
            $shop->equipment_types = array_column($types, 'equipment_type');
            
            // Get amenities/features from rental_features table
            $this->db->query('SELECT feature_name FROM rental_features WHERE rental_id = :shop_id');
            $this->db->bind(':shop_id', $shop_id);
            $features = $this->db->resultSet();
            $shop->features = array_column($features, 'feature_name');
            
            // Get images from rental_shop_images table
            $this->db->query('SELECT * FROM rental_shop_images WHERE shop_id = :shop_id ORDER BY is_primary DESC, id ASC');
            $this->db->bind(':shop_id', $shop_id);
            $shop->images = $this->db->resultSet();
            
            return $shop;
            
            /*// OLD DUMMY DATA - REMOVED
            $sampleShops = [
                1 => (object)[
                    'id' => 1,
                    'shop_name' => 'Pro Sports Gear Rentals',
                    'address' => '123 Galle Road, Colombo 03',
                    'description' => 'Complete sports equipment rental service with premium quality gear for all sports including cricket, football, and tennis.',
                    'equipment_count' => 85,
                    'daily_rate' => 1500,
                    'contact_email' => 'rentals@prosportsgear.lk',
                    'contact_phone' => '+94 71 234 5678',
                    'operating_hours' => 'Mon-Sun: 8:00 AM - 8:00 PM',
                    'category' => 'Multi-Sport'
                ],
                2 => (object)[
                    'id' => 2,
                    'shop_name' => 'Cricket Zone Equipment',
                    'address' => '456 Duplication Road, Colombo 07',
                    'description' => 'Specialized cricket equipment rental with professional grade gear including bats, pads, gloves, and protective equipment.',
                    'equipment_count' => 45,
                    'daily_rate' => 800,
                    'contact_email' => 'info@cricketzone.lk',
                    'contact_phone' => '+94 77 345 6789',
                    'operating_hours' => 'Mon-Sat: 9:00 AM - 7:00 PM',
                    'category' => 'Cricket'
                ],
                3 => (object)[
                    'id' => 3,
                    'shop_name' => 'Football Gear Hub',
                    'address' => '789 Galle Road, Dehiwala',
                    'description' => 'Premium football equipment rental for players and teams including balls, shoes, goalkeeper gear, and training equipment.',
                    'equipment_count' => 60,
                    'daily_rate' => 1200,
                    'contact_email' => 'hello@footballgearhub.lk',
                    'contact_phone' => '+94 70 456 7890',
                    'operating_hours' => 'Mon-Sun: 7:00 AM - 9:00 PM',
                    'category' => 'Football'
                ],
                4 => (object)[
                    'id' => 4,
                    'shop_name' => 'Tennis Pro Rentals',
                    'address' => '321 Hotel Road, Mount Lavinia',
                    'description' => 'High-quality tennis equipment rental with expert guidance including rackets, balls, nets, and court equipment.',
                    'equipment_count' => 35,
                    'daily_rate' => 1000,
                    'contact_email' => 'rentals@tennispro.lk',
                    'contact_phone' => '+94 76 567 8901',
                    'operating_hours' => 'Tue-Sun: 8:00 AM - 6:00 PM',
                    'category' => 'Tennis'
                ]
            ];

            return isset($sampleShops[$shop_id]) ? $sampleShops[$shop_id] : null;*/
        } catch (Exception $e) {
            error_log('Error in getShop: ' . $e->getMessage());
            return null;
        }
    }

    // Get messages for owner
    public function getMessages($owner_id) {
        try {
            return [
                [
                    'id' => 1,
                    'from' => 'Krishna Wishvajith',
                    'subject' => 'Equipment Rental Inquiry',
                    'message' => 'Hi, I would like to rent cricket equipment for tomorrow...',
                    'date' => '2025-01-20',
                    'status' => 'unread'
                ],
                [
                    'id' => 2,
                    'from' => 'Kulakshi Thathsarani',
                    'subject' => 'Equipment Return Issue',
                    'message' => 'I had an issue returning the football yesterday...',
                    'date' => '2025-01-19',
                    'status' => 'read'
                ]
            ];
        } catch (Exception $e) {
            error_log('Error in getMessages: ' . $e->getMessage());
            return [];
        }
    }

    // Get unread message count
    public function getUnreadMessageCount($owner_id) {
        try {
            return 3; // Sample count
        } catch (Exception $e) {
            error_log('Error in getUnreadMessageCount: ' . $e->getMessage());
            return 0;
        }
    }

    // Get profile data
    public function getProfileData($owner_id) {
        try {
            if (!$this->db) {
                return $this->getDefaultProfileData();
            }

            $this->db->query('SELECT u.*, rop.* FROM users u
                LEFT JOIN rental_owner_profiles rop ON u.id = rop.user_id
                WHERE u.id = :id AND u.role = "rental_owner"');
            $this->db->bind(':id', $owner_id);
            
            $profile = $this->db->single();
            
            if ($profile) {
                return [
                    'owner_name' => $profile->owner_name ?? 'Rental Owner',
                    'business_name' => $profile->business_name ?? 'Equipment Rental Service',
                    'email' => $profile->email ?? 'owner@example.com',
                    'phone' => $profile->phone ?? 'Not set',
                    'address' => $profile->address ?? 'Not set',
                    'business_type' => $profile->business_type ?? 'Not specified',
                    'equipment_categories' => $profile->equipment_categories ?? 'Not specified',
                    'delivery_service' => $profile->delivery_service ?? 'Not specified',
                    'district' => $profile->district ?? 'Not set',
                    'package_type' => 'Standard', // This would come from a separate package tracking
                    'total_shops' => 4,
                    'total_revenue' => 125000,
                    'rating' => '4.6',
                    'member_since' => isset($profile->created_at) ? date('F Y', strtotime($profile->created_at)) : 'January 2024'
                ];
            }
            
            return $this->getDefaultProfileData();
            
        } catch (Exception $e) {
            error_log('Error in getProfileData: ' . $e->getMessage());
            return $this->getDefaultProfileData();
        }
    }

    private function getDefaultProfileData() {
        return [
            'owner_name' => 'Rental Owner',
            'business_name' => 'Equipment Rental Service',
            'email' => 'owner@example.com',
            'phone' => 'Not set',
            'address' => 'Not set',
            'business_type' => 'Not specified',
            'equipment_categories' => 'Not specified',
            'delivery_service' => 'Not specified',
            'district' => 'Not set',
            'package_type' => 'Standard',
            'total_shops' => 0,
            'total_revenue' => 0,
            'rating' => '0.0',
            'member_since' => 'January 2025'
        ];
    }

    // Update profile
    public function updateProfile($owner_id, $profileData) {
        try {
            if (!$this->db) {
                return false;
            }

            // Update main user data
            $this->db->query('UPDATE users SET
                phone = :phone,
                updated_at = NOW()
                WHERE id = :id');
            
            $this->db->bind(':phone', $profileData['phone']);
            $this->db->bind(':id', $owner_id);
            
            $result1 = $this->db->execute();

            // Update rental owner profile data
            $this->db->query('UPDATE rental_owner_profiles SET
                owner_name = :owner_name,
                business_name = :business_name,
                updated_at = NOW()
                WHERE user_id = :id');
            
            $this->db->bind(':owner_name', $profileData['owner_name']);
            $this->db->bind(':business_name', $profileData['business_name']);
            $this->db->bind(':id', $owner_id);
            
            $result2 = $this->db->execute();
            
            return $result1 && $result2;
        } catch (Exception $e) {
            error_log('Error in updateProfile: ' . $e->getMessage());
            return false;
        }
    }

    /* ============================================
       ADVERTISEMENT METHODS
    ============================================ */

    public function getAdvertisements($user_id)
    {
        try {
            $this->db->query('SELECT * FROM advertisement_requests 
                              WHERE user_id = :uid AND is_active = 1 
                              ORDER BY submitted_at DESC');
            $this->db->bind(':uid', $user_id);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in getAdvertisements: ' . $e->getMessage());
            return [];
        }
    }

    public function createAdvertisement($user_id, $data)
    {
        try {
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
        } catch (Exception $e) {
            error_log('Error in createAdvertisement: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteAdvertisement($id, $user_id)
    {
        try {
            $this->db->query('UPDATE advertisement_requests 
                              SET is_active = 0 
                              WHERE id = :id AND user_id = :uid');
            $this->db->bind(':id', $id);
            $this->db->bind(':uid', $user_id);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in deleteAdvertisement: ' . $e->getMessage());
            return false;
        }
    }

    public function getAdvertisementById($id, $user_id)
    {
        try {
            $this->db->query('SELECT * FROM advertisement_requests 
                              WHERE id = :id AND user_id = :uid AND is_active = 1');
            $this->db->bind(':id', $id);
            $this->db->bind(':uid', $user_id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Error in getAdvertisementById: ' . $e->getMessage());
            return null;
        }
    }

    public function updateAdvertisement($id, $user_id, $data)
    {
        try {
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
        } catch (Exception $e) {
            error_log('Error in updateAdvertisement: ' . $e->getMessage());
            return false;
        }
    }

    public function getAdvertisementPackages()
    {
        return [
            'basic' => ['name' => 'Basic', 'price' => 5000, 'duration' => '7 days'],
            'professional' => ['name' => 'Professional', 'price' => 12000, 'duration' => '30 days'],
            'premium' => ['name' => 'Premium', 'price' => 30000, 'duration' => '90 days']
        ];
    }
    
    // Get the number of images for a specific shop
    public function getShopImageCount($shop_id) {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM rental_shop_images WHERE shop_id = :shop_id');
            $this->db->bind(':shop_id', $shop_id);
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            error_log('Error in getShopImageCount: ' . $e->getMessage());
            return 0;
        }
    }
    
    // Delete a shop (only if user owns it)
    public function deleteShop($owner_id, $shop_id) {
        try {
            if (!$this->db) {
                return false;
            }

            // Verify ownership first
            $this->db->query('SELECT id FROM rental_shops WHERE id = :shop_id AND owner_id = :owner_id');
            $this->db->bind(':shop_id', $shop_id);
            $this->db->bind(':owner_id', $owner_id);
            $shop = $this->db->single();
            
            if (!$shop) {
                error_log('Shop not found or user does not own this shop');
                return false;
            }

            // Delete shop (related data will cascade delete due to foreign keys)
            $this->db->query('DELETE FROM rental_shops WHERE id = :shop_id AND owner_id = :owner_id');
            $this->db->bind(':shop_id', $shop_id);
            $this->db->bind(':owner_id', $owner_id);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in deleteShop: ' . $e->getMessage());
            return false;
        }
    }
}
?>