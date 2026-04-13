<?php
class Rental extends Controller {
    private $rentalModel;

    public function __construct()
    {
        $this->rentalModel = $this->model('M_Rental');
    }

    public function index() {
        // Get all rental services
        $rentals = $this->rentalModel->getAllRentals();

        $data = [
            'title' => 'Sports Equipment Rental',
            'rentals' => $rentals
        ];

        $this->view('rental/v_rental', $data);
    }

    public function single($id) {
        // Get single rental service details
        $rental = $this->rentalModel->getRentalById($id);
        
        if(!$rental) {
            // Redirect to 404 or rentals list
            header('Location: ' . URLROOT . '/rental');
            exit;
        }

        $data = [
            'title' => $rental->store_name,
            'rental' => $rental
        ];

        $this->view('rental/v_single_rental', $data);
    }
}
?>