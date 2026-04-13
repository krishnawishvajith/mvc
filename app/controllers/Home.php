<?php
class Home extends Controller
{
    private $homeModel;
    private $adModel;
    private $rentalModel;

    public function __construct()
    {
        $this->homeModel = $this->model('M_Home');
        $this->adModel = $this->model('M_Advertisement');
        $this->rentalModel = $this->model('M_Rental');
    }

    public function index()
    {
        // Get featured stadiums (limit to 6 for the homepage)
        $featuredStadiums = $this->homeModel->getFeaturedStadiums(6);

        // Get featured rental services (limit to top 4)
        $featuredRentals = array_slice($this->rentalModel->getAllRentals(), 0, 4);

        // Get admin-editable hero content
        $hero = $this->homeModel->getHeroContent();

        // Get active advertisements for homepage banner
        $activeAds = $this->adModel->getActiveAdvertisements();

        $data = [
            'title' => 'BookMyGround - Your Sports Booking Platform',
            'featured_stadiums' => $featuredStadiums,
            'featured_rentals' => $featuredRentals,
            'active_ads' => $activeAds,
            'hero' => $hero
        ];

        $this->view('v_home', $data);
    }
}
