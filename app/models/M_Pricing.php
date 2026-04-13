<?php
class M_Pricing
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getPricingPackages()
    {
        // Try to get packages from database (limit to top 3 active stadium packages)
        $this->db->query('SELECT * FROM stadium_packages WHERE is_active = 1 ORDER BY display_order ASC, id ASC LIMIT 3');
        $dbPackages = $this->db->resultSet();

        // If database has packages, use them
        if ($dbPackages && count($dbPackages) > 0) {
            $packages = [];
            foreach ($dbPackages as $pkg) {
                $packages[] = (object)[
                    'id' => $pkg->id,
                    'name' => $pkg->name,
                    'price' => 0,
                    'setup_fee' => $pkg->setup_fee,
                    'monthly_fee' => 0,
                    'commission' => $pkg->commission_rate,
                    'duration' => 'One-time setup fee',
                    'description' => $pkg->description,
                    'color' => $pkg->color,
                    'icon' => $pkg->icon,
                    'popular' => (bool)$pkg->is_popular,
                    'features' => (object)[
                        'stadium_limit' => $pkg->stadium_limit,
                        'photos_per_property' => $pkg->photos_per_property,
                        'videos_per_property' => $pkg->videos_per_property,
                        'featured_listings' => $pkg->featured_listings,
                        'support' => $pkg->support,
                        'analytics' => $pkg->advanced_analytics ? 'Advanced Analytics' : 'Basic Analytics',
                        'booking_management' => true,
                        'payment_processing' => true,
                        'mobile_app' => true,
                        'priority_support' => (bool)$pkg->priority_support,
                        'marketing_tools' => (bool)$pkg->marketing_tools,
                        'advanced_analytics' => (bool)$pkg->advanced_analytics,
                        'dedicated_manager' => (bool)$pkg->dedicated_manager,
                        'api_access' => (bool)$pkg->api_access
                    ]
                ];
            }
            return $packages;
        }

        // Fallback to hardcoded data if database is empty
        return [
            (object)[
                'id' => 1,
                'name' => 'Basic',
                'price' => 0,
                'setup_fee' => 1380,
                'monthly_fee' => 0,
                'commission' => 8,
                'duration' => 'One-time setup fee',
                'description' => 'Perfect for getting started with stadium rentals',
                'color' => 'basic',
                'icon' => '🌟',
                'popular' => false,
                'features' => [
                    'stadium_limit' => 3,
                    'photos_per_property' => 3,
                    'videos_per_property' => 3,
                    'featured_listings' => 0,
                    'support' => 'Email Support',
                    'analytics' => 'Basic Analytics',
                    'booking_management' => true,
                    'payment_processing' => true,
                    'mobile_app' => true,
                    'priority_support' => false,
                    'marketing_tools' => false,
                    'advanced_analytics' => false
                ]
            ],
            (object)[
                'id' => 2,
                'name' => 'Standard',
                'price' => 0,
                'setup_fee' => 1380,
                'monthly_fee' => 0,
                'commission' => 12,
                'duration' => 'One-time setup fee',
                'description' => 'Ideal for growing stadium businesses',
                'color' => 'standard',
                'icon' => '⚡',
                'popular' => true,
                'features' => [
                    'stadium_limit' => 6,
                    'photos_per_property' => 5,
                    'videos_per_property' => 5,
                    'featured_listings' => 3,
                    'support' => 'Email & Phone Support',
                    'analytics' => 'Advanced Analytics',
                    'booking_management' => true,
                    'payment_processing' => true,
                    'mobile_app' => true,
                    'priority_support' => true,
                    'marketing_tools' => true,
                    'advanced_analytics' => true
                ]
            ],
            (object)[
                'id' => 3,
                'name' => 'Gold',
                'price' => 0,
                'setup_fee' => 1380,
                'monthly_fee' => 0,
                'commission' => 20,
                'duration' => 'One-time setup fee',
                'description' => 'For established stadium owners who want maximum exposure',
                'color' => 'gold',
                'icon' => '👑',
                'popular' => false,
                'features' => [
                    'stadium_limit' => 'unlimited',
                    'photos_per_property' => 10,
                    'videos_per_property' => 5,
                    'featured_listings' => 5,
                    'support' => 'Priority Support 24/7',
                    'analytics' => 'Premium Analytics & Reports',
                    'booking_management' => true,
                    'payment_processing' => true,
                    'mobile_app' => true,
                    'priority_support' => true,
                    'marketing_tools' => true,
                    'advanced_analytics' => true,
                    'dedicated_manager' => true,
                    'api_access' => true
                ]
            ]
        ];
    }

    public function getFeatureComparison()
    {
        return [
            'Stadium Listings' => [
                'basic' => '3 Stadiums',
                'standard' => '6 Stadiums',
                'gold' => 'Unlimited Stadiums'
            ],
            'Commission Rate' => [
                'basic' => '8% per booking',
                'standard' => '12% per booking',
                'gold' => '20% per booking'
            ],
            'Photos per Stadium' => [
                'basic' => '3 Photos',
                'standard' => '5 Photos',
                'gold' => '10 Photos'
            ],
            'Videos per Stadium' => [
                'basic' => '3 Videos',
                'standard' => '5 Videos',
                'gold' => '5 Videos'
            ],
            'Featured Listings' => [
                'basic' => 'None',
                'standard' => '3 Featured',
                'gold' => '5 Featured'
            ],
            'Support' => [
                'basic' => 'Email Only',
                'standard' => 'Email & Phone',
                'gold' => '24/7 Priority Support'
            ],
            'Analytics' => [
                'basic' => 'Basic Reports',
                'standard' => 'Advanced Analytics',
                'gold' => 'Premium Analytics'
            ]
        ];
    }

    public function getPackageById($id)
    {
        $packages = $this->getPricingPackages();

        foreach ($packages as $package) {
            if ($package->id == $id) {
                return $package;
            }
        }

        return false;
    }

    public function getPackageByName($name)
    {
        $packages = $this->getPricingPackages();

        foreach ($packages as $package) {
            if (strtolower($package->name) === strtolower($name)) {
                return $package;
            }
        }

        return false;
    }
}
