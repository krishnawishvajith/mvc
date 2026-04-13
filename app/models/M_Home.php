<?php
class M_Home {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getFeaturedStadiums($limit = 6) {
        // Fetch only featured stadiums from database
        $this->db->query('SELECT * FROM stadiums WHERE is_featured = TRUE ORDER BY rating DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
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

    public function getHeroData() {
        // Backward compatible hero data for older templates
        $this->db->query('SELECT COUNT(*) as total FROM stadiums');
        $stadiumCount = $this->db->single()->total;

        $hero = $this->getHeroContent();

        return [
            'title' => $hero['hero_title_prefix'] . ' ' . $hero['hero_title_highlight'] . ' ' . $hero['hero_title_suffix'],
            'subtitle' => $hero['hero_description'],
            'stats' => [
                'stadiums' => $stadiumCount,
                'bookings' => 5000,
                'cities' => 15,
                'users' => 10000
            ]
        ];
    }

    // Get home hero title + description (admin-editable)
    public function getHeroContent() {
        try {
            $this->db->query("
                SELECT 
                    hero_title_prefix,
                    hero_title_highlight,
                    hero_title_suffix,
                    hero_description
                FROM home_page_content
                WHERE id = 1
                LIMIT 1
            ");
            $row = $this->db->single();

            if ($row) {
                return [
                    'hero_title_prefix' => $row->hero_title_prefix ?? 'BOOK',
                    'hero_title_highlight' => $row->hero_title_highlight ?? 'YOUR',
                    'hero_title_suffix' => $row->hero_title_suffix ?? 'SPORT GROUND',
                    'hero_description' => $row->hero_description ?? 'Your All-in-One Solution for Finding and Booking Indoor & Outdoor Stadiums, Rent Sport Equipments, Attend Practise Sessions, Book Individual Coaching Sessions & Publish Your Advertisements'
                ];
            }
        } catch (Exception $e) {
            error_log('M_Home getHeroContent error: ' . $e->getMessage());
        }

        // Defaults (if table missing/uninitialized)
        return [
            'hero_title_prefix' => 'BOOK',
            'hero_title_highlight' => 'YOUR',
            'hero_title_suffix' => 'SPORT GROUND',
            'hero_description' => 'Your All-in-One Solution for Finding and Booking Indoor & Outdoor Stadiums, Rent Sport Equipments, Attend Practise Sessions, Book Individual Coaching Sessions & Publish Your Advertisements'
        ];
    }

    // Save home hero title + description
    public function updateHeroContent($data) {
        try {
            $prefix = trim((string)($data['hero_title_prefix'] ?? 'BOOK'));
            $highlight = trim((string)($data['hero_title_highlight'] ?? 'YOUR'));
            $suffix = trim((string)($data['hero_title_suffix'] ?? 'SPORT GROUND'));
            $description = trim((string)($data['hero_description'] ?? ''));

            if ($prefix === '' || $highlight === '' || $suffix === '' || $description === '') {
                return false;
            }

            $this->db->query("
                INSERT INTO home_page_content 
                    (id, hero_title_prefix, hero_title_highlight, hero_title_suffix, hero_description)
                VALUES 
                    (1, :hero_title_prefix, :hero_title_highlight, :hero_title_suffix, :hero_description)
                ON DUPLICATE KEY UPDATE
                    hero_title_prefix = VALUES(hero_title_prefix),
                    hero_title_highlight = VALUES(hero_title_highlight),
                    hero_title_suffix = VALUES(hero_title_suffix),
                    hero_description = VALUES(hero_description)
            ");

            $this->db->bind(':hero_title_prefix', $prefix);
            $this->db->bind(':hero_title_highlight', $highlight);
            $this->db->bind(':hero_title_suffix', $suffix);
            $this->db->bind(':hero_description', $description);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('M_Home updateHeroContent error: ' . $e->getMessage());
            return false;
        }
    }

    // ---------- Site settings (footer + social) ----------
    public function getSiteSettings() {
        $defaults = [
            'footer_company_name' => 'BOOKMYGROUND',
            'footer_tagline' => 'Defend, take care of the ball, rebound, and play hard.',
            'footer_address' => '4200 Reid Avenue, Colombo 07',
            'footer_phone' => '(071) 111 1111',
            'footer_email' => 'support@bookmyground.lk',
            'social_facebook' => '#',
            'social_instagram' => '#',
            'social_linkedin' => '#',
            'social_twitter' => '#',
            'social_youtube' => '#'
        ];

        try {
            $keys = array_keys($defaults);
            $in = "'" . implode("','", $keys) . "'";
            $this->db->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ($in)");
            $rows = $this->db->resultSet();

            foreach ($rows as $row) {
                if (isset($defaults[$row->setting_key])) {
                    $defaults[$row->setting_key] = $row->setting_value;
                }
            }
        } catch (Exception $e) {
            error_log('M_Home getSiteSettings error: ' . $e->getMessage());
        }

        return $defaults;
    }

    public function updateSiteSettings($settings) {
        try {
            if (!is_array($settings)) return false;

            $allowed = [
                'footer_company_name',
                'footer_tagline',
                'footer_address',
                'footer_phone',
                'footer_email',
                'social_facebook',
                'social_instagram',
                'social_linkedin',
                'social_twitter',
                'social_youtube'
            ];

            foreach ($allowed as $key) {
                if (!array_key_exists($key, $settings)) continue;

                $val = trim((string)$settings[$key]);

                $this->db->query("
                    INSERT INTO site_settings (setting_key, setting_value)
                    VALUES (:k, :v)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
                ");
                $this->db->bind(':k', $key);
                $this->db->bind(':v', $val);
                $this->db->execute();
            }

            return true;
        } catch (Exception $e) {
            error_log('M_Home updateSiteSettings error: ' . $e->getMessage());
            return false;
        }
    }

    // ---------- Navigation (header menu) ----------
    public function getNavigationItems() {
        $defaults = [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Stadiums', 'url' => '/stadiums'],
            ['label' => 'Coaches', 'url' => '/coach'],
            ['label' => 'Sports', 'url' => '/sports'],
            ['label' => 'Rental Services', 'url' => '/rental'],
        ];

        try {
            $this->db->query("
                SELECT label, url
                FROM site_navigation
                WHERE is_active = 1
                ORDER BY sort_order ASC, id ASC
            ");
            $rows = $this->db->resultSet();
            if (!empty($rows)) {
                $out = [];
                foreach ($rows as $r) {
                    $out[] = ['label' => $r->label, 'url' => $r->url];
                }
                return $out;
            }
        } catch (Exception $e) {
            error_log('M_Home getNavigationItems error: ' . $e->getMessage());
        }

        return $defaults;
    }

    public function updateNavigationItems($items) {
        try {
            if (!is_array($items)) return false;

            // If table doesn't exist, this will throw and we return false.
            $this->db->query("DELETE FROM site_navigation");
            $this->db->execute();

            $sort = 10;
            foreach ($items as $item) {
                $label = trim((string)($item['label'] ?? ''));
                $url = trim((string)($item['url'] ?? ''));
                $isActive = !empty($item['is_active']) ? 1 : 0;

                if ($label === '' || $url === '') continue;
                if (mb_strlen($label) > 60) $label = mb_substr($label, 0, 60);
                if (mb_strlen($url) > 255) $url = mb_substr($url, 0, 255);

                // Normalize url: allow absolute http(s), else store as /path
                if (!preg_match('#^https?://#i', $url)) {
                    if ($url[0] !== '/') $url = '/' . $url;
                }

                $this->db->query("
                    INSERT INTO site_navigation (label, url, sort_order, is_active)
                    VALUES (:label, :url, :sort_order, :is_active)
                ");
                $this->db->bind(':label', $label);
                $this->db->bind(':url', $url);
                $this->db->bind(':sort_order', $sort);
                $this->db->bind(':is_active', $isActive);
                $this->db->execute();

                $sort += 10;
            }

            return true;
        } catch (Exception $e) {
            error_log('M_Home updateNavigationItems error: ' . $e->getMessage());
            return false;
        }
    }
}
?>