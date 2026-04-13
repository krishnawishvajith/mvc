<?php
class Stadiums extends Controller {
    private $stadiumsModel;

    public function __construct()
    {
        $this->stadiumsModel = $this->model('M_Stadiums');
    }

    public function index() {
        // Get all stadiums
        $stadiums = $this->stadiumsModel->getAllStadiums();

        $data = [
            'title' => 'Find Your Perfect Stadium',
            'stadiums' => $stadiums
        ];

        $this->view('stadiums/v_stadiums', $data);
    }

    public function single($id) {
        // Get single stadium details
        $stadium = $this->stadiumsModel->getStadiumById($id);
        
        if(!$stadium) {
            // Redirect to 404 or stadiums list
            header('Location: ' . URLROOT . '/stadiums');
            exit;
        }

        // Get nearby stadiums (exclude current stadium)
        $nearbyStadiums = $this->stadiumsModel->getNearbyStadiums($stadium->location, $id, 4);

        // Get stadium gallery images
        $galleryImages = $this->stadiumsModel->getStadiumGallery($id);

        // Get stadium reviews
        $reviews = $this->stadiumsModel->getStadiumReviews($id, 5);

        $data = [
            'title' => $stadium->name,
            'stadium' => $stadium,
            'nearby_stadiums' => $nearbyStadiums,
            'gallery_images' => $galleryImages,
            'reviews' => $reviews
        ];

        $this->view('stadiums/v_single_stadium', $data);
    }
}
?>