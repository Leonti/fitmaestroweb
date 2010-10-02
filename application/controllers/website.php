<?php defined('SYSPATH') or die('No direct script access.');

class Website_Controller extends Template_Controller {

	public function __construct(){

		parent::__construct();

		$this->template->links = array
		(
		'Home' => 'home',
		'Exercises' => 'exercises',
                'Programs' => array(
                            'My Programs' => 'programs',
                            'Public Programs' => 'programs/publicPrograms',
                                    ),
		'Workouts' => 'workouts',
                'Sessions' => 'sessions',
                'Measurements' => 'measurements',
                'Statistics' => 'statistics',
                'Settings' => 'settings',
		);

	$this->db = Database::instance();
        $this->session= Session::instance();

        $authentic = new Auth;

        $user = $authentic->get_user(); //now you have access to user information stored in the database
        $this->template->user = $user;

        if($user){
            $settings = new Setting_Model($user->id);
            $userSettings = $settings->getSettings();
            $this->template->timeFormat = $userSettings->time_format;
            $this->template->timeZone = $userSettings->time_zone;
            $this->template->weightUnits = $userSettings->weight_units;
            $this->template->multiplicator = $userSettings->multiplicator;
        }

	}

} 
