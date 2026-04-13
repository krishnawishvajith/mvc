<?php
class M_Advertisement {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAllAdvertisements() {
        try {
            $this->db->query('SELECT * FROM advertisement_requests ORDER BY submitted_at DESC');
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Advertisement Model Error: ' . $e->getMessage());
            return [];
        }
    }

    public function getAdvertisementById($id) {
        try {
            $this->db->query('SELECT * FROM advertisement_requests WHERE id = :id');
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Advertisement Model Error: ' . $e->getMessage());
            return null;
        }
    }

    public function updateStatus($id, $status) {
        try {
            $this->db->query('UPDATE advertisement_requests SET status = :status WHERE id = :id');
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Advertisement Model Error: ' . $e->getMessage());
            return false;
        }
    }
    
    // Approve advertisement and set active dates
    public function approveAd($id) {
        try {
            $this->db->query('UPDATE advertisement_requests SET status = :status, approved_at = NOW(), expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = :id');
            $this->db->bind(':status', 'active');
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Advertisement Model Error: ' . $e->getMessage());
            return false;
        }
    }
    
    // Get active advertisements for homepage display
    public function getActiveAdvertisements() {
        try {
            $this->db->query('SELECT * FROM advertisement_requests 
                              WHERE status = :status 
                              AND is_active = 1 
                              AND (expires_at IS NULL OR expires_at > NOW())
                              ORDER BY approved_at DESC');
            $this->db->bind(':status', 'active');
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Advertisement Model Error: ' . $e->getMessage());
            return [];
        }
    }
}
?>