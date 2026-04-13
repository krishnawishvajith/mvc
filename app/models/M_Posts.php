<?php
class M_Posts {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Get all published posts
    public function getAllposts() {
        $this->db->query('SELECT p.*, CONCAT_WS(" ", u.first_name, u.last_name) AS author FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.status = :status AND p.is_deleted = 0 ORDER BY p.created_at DESC');
        $this->db->bind(':status','published');
        return $this->db->resultSet();
    }

    // Get single post by ID
    public function getPostById($id) {
        $this->db->query('SELECT p.*, CONCAT_WS(" ", u.first_name, u.last_name) AS author FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.id = :id AND p.status = :status AND p.is_deleted = 0');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', 'published');

        return $this->db->single();
    }

    // Get recent posts (limit)
    public function getRecentPosts($limit = 5) {
        $this->db->query('SELECT p.*, CONCAT_WS(" ", u.first_name, u.last_name) AS author FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.status = :status AND p.is_deleted = 0 ORDER BY p.created_at DESC LIMIT :limit');
        $this->db->bind(':status', 'published');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Get posts by category
    public function getPostsByCategory($category) {
        $this->db->query('SELECT * FROM posts WHERE category = :category AND status = :status AND is_deleted = 0 ORDER BY created_at DESC');
        $this->db->bind(':category', $category);
        $this->db->bind(':status', 'published');
        
        return $this->db->resultSet();
    }

    // Search posts
    public function searchPosts($searchTerm) {
        $this->db->query('SELECT * FROM posts WHERE (title LIKE :search OR content LIKE :search) AND status = :status AND is_deleted = 0 ORDER BY created_at DESC');
        $this->db->bind(':search', '%' . $searchTerm . '%');
        $this->db->bind(':status', 'published');
        
        return $this->db->resultSet();
    }

    // Increment post views
    public function incrementViews($id) {
        $this->db->query('UPDATE posts SET views = views + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }





    
    // Update post status (publish/unpublish)
    public function updateStatus($id, $status) {
        $this->db->query('UPDATE posts SET status = :status, updated_at = :updated_at WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':updated_at', date('Y-m-d H:i:s'));
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>
