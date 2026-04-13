<?php
class Sports extends Controller {
    // Default method used by router
    public function index() {
        // Prepare data for view
        $data = [
            'title' => 'Choose Your Sport - BookMyGround',
            'description' => 'Select a sport category to find and book your perfect playing ground'
        ];
        
        // Load view using base Controller helper
        $this->view('sports/v_select_category', $data);
    }
    
    public function single(){
        // Load model and get single sports
        $sportsModel = $this->model('M_Sports');
        $sports = $sportsModel->getSingleSports();

        $data = [
            'title' => 'Single Sports - BookMyGround',
            'sports' => $sports
        ];

        $this->view('sports/single/single_sport', $data);
    }

    public function double(){
        $sportsModel = $this->model('M_Sports');
        $sports = $sportsModel->getDoubleSports();
        $data = [
            'title' => 'Double Sports - BookMyGround',
            'sports' => $sports
        ];
        $this->view('sports/double/double_sport', $data);
    }

    public function team(){
        $sportsModel = $this->model('M_Sports');
        $sports = $sportsModel->getTeamSports();
        $data = [
            'title' => 'Team Sports - BookMyGround',
            'sports' => $sports
        ];
        $this->view('sports/team/team_sport', $data);
    }
}

