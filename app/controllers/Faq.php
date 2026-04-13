<?php
class Faq extends Controller {
    private $faqModel;

    public function __construct()
    {
        $this->faqModel = $this->model('M_Faq');
    }

    public function index() {
        // Get all FAQ categories and their questions
        $faqData = $this->faqModel->getAllFAQsByCategory();
        
        $data = [
            'title' => 'Frequently Asked Questions - BookMyGround',
            'faq_data' => $faqData,
            'total_faqs' => $this->faqModel->getTotalFAQs(),
            'categories' => $this->faqModel->getCategories()
        ];

        $this->view('faq/v_faq', $data);
    }
}
?>