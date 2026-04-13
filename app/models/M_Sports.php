<?php
class M_Sports {
    // Return list of single-player sports (stubbed). In future replace with DB calls.
    public function getSingleSports() {
        return [
            [
                'id' => 1,
                'slug' => 'golf',
                'title' => 'Golf',
                'image' => URLROOT . '/public/images/sports/single/dd.jpg',
                'description' => 'Outdoor precision sport on greens.'
            ],
            [
                'id' => 2,
                'slug' => 'swimming',
                'title' => 'Swimming',
                'image' => URLROOT . '/public/images/sports/single/sw.jpg',
                'description' => 'Laps and timed events in pools.'
            ],
            [
                'id' =>3,
                'slug' => 'athletic',
                'title' => 'Athletic',
                'image' => URLROOT . '/public/images/sports/single/at.jpg',
                'description' => 'running is life'
            ],
   

    

        ];
    }
    public function getDoubleSports(){
        return [
            [
                'id' => 1,
                'slug' => 'badminton',
                'title' => 'Badminton',
                'image' => URLROOT . '/public/images/sports/double/min2.jpg',
                'description' => 'Indoor racquet sport with shuttlecock.'
            ],

            [
                'id' =>2,
                'slug' => 'tennis',
                'title' => 'Tennis',
                'image' => URLROOT . '/public/images/sports/double/tenn.jpg',
                'description' => 'Outdoor racquet sport on courts.'
            ],
        ];

    }
    public function getTeamSports(){
        return [
            [
                'id' => 1,
                'slug' => 'football',
                'title' => 'Football',
                'image' => URLROOT . '/public/images/sports/team/foot4.jpg',
                'description' => 'Outdoor or indoor team sport played with a spherical ball.'
            ],

            [
                'id' =>2,
                'slug' => 'cricket',
                'title' => 'Cricket',
                'image' => URLROOT . '/public/images/sports/team/cri.jpg',
                'description' => 'Outdoor or indoor  team sport played with a bat and ball.'
            ],
        ];

    }

    // Stub for admin to add a sport - implement DB insert later
    public function addSport($sport) {
        // $sport = ['slug'=>..., 'title'=>..., 'image'=>..., 'description'=>...]
        // Return true on success (stub)
        return true;
    }
}
