<?php
class M_Faq
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all FAQs organized by category from database
    public function getAllFAQsByCategory()
    {
        $this->db->query('
            SELECT f.id, f.question, f.answer, f.status, c.slug as category
            FROM faqs f
            JOIN faq_categories c ON f.category_id = c.id
            WHERE f.status = "published" AND c.status = "active"
            ORDER BY c.sort_order, f.sort_order
        ');

        $results = $this->db->resultSet();

        // Organize by category
        $faqsByCategory = [];
        foreach ($results as $faq) {
            $category = $faq->category;
            if (!isset($faqsByCategory[$category])) {
                $faqsByCategory[$category] = [];
            }
            $faqsByCategory[$category][] = [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'category' => $faq->category,
                'status' => $faq->status
            ];
        }

        return $faqsByCategory;
    }

    // Get total number of published FAQs
    public function getTotalFAQs()
    {
        $this->db->query('
            SELECT COUNT(*) as total 
            FROM faqs f
            JOIN faq_categories c ON f.category_id = c.id
            WHERE f.status = "published" AND c.status = "active"
        ');

        $result = $this->db->single();
        return $result->total;
    }

    // Get all categories with question counts from database
    public function getCategories()
    {
        $this->db->query('
            SELECT 
                c.slug,
                c.name,
                c.description,
                c.icon,
                COUNT(f.id) as count
            FROM faq_categories c
            LEFT JOIN faqs f ON c.id = f.category_id AND f.status = "published"
            WHERE c.status = "active"
            GROUP BY c.id
            ORDER BY c.sort_order
        ');

        $results = $this->db->resultSet();

        // Format as associative array
        $categories = [];
        foreach ($results as $cat) {
            $categories[$cat->slug] = [
                'name' => $cat->name,
                'description' => $cat->description,
                'icon' => $cat->icon,
                'count' => (int)$cat->count
            ];
        }

        return $categories;
    }

    // Get all FAQs for admin management
    public function getAllFaqsAdmin()
    {
        $this->db->query('
            SELECT f.id, f.question, f.answer, f.status, f.updated_at, c.slug AS category_slug, c.name AS category_name
            FROM faqs f
            JOIN faq_categories c ON f.category_id = c.id
            ORDER BY c.sort_order, f.sort_order, f.updated_at DESC
        ');

        $results = $this->db->resultSet();
        $faqs = [];

        foreach ($results as $faq) {
            $faqs[] = [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'status' => $faq->status,
                'updated_at' => $faq->updated_at,
                'category_slug' => $faq->category_slug,
                'category_name' => $faq->category_name
            ];
        }

        return $faqs;
    }

    public function getFaqById($id)
    {
        $this->db->query('SELECT f.id, f.question, f.answer, f.status, c.slug AS category_slug, c.name AS category_name
            FROM faqs f
            JOIN faq_categories c ON f.category_id = c.id
            WHERE f.id = :id
            LIMIT 1');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getCategoryIdBySlug($slug)
    {
        $this->db->query('SELECT id FROM faq_categories WHERE slug = :slug LIMIT 1');
        $this->db->bind(':slug', $slug);
        $result = $this->db->single();

        if ($result && isset($result->id)) {
            return $result->id;
        }

        return 1;
    }

    public function createFaq($data)
    {
        $this->db->query('INSERT INTO faqs (category_id, question, answer, status) VALUES (:category_id, :question, :answer, :status)');
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function updateFaq($id, $data)
    {
        $this->db->query('UPDATE faqs SET category_id = :category_id, question = :question, answer = :answer, status = :status WHERE id = :id');
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':question', $data['question']);
        $this->db->bind(':answer', $data['answer']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function setFaqStatus($id, $status)
    {
        $this->db->query('UPDATE faqs SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteFaq($id)
    {
        $this->db->query('DELETE FROM faqs WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
