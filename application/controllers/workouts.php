<?php defined('SYSPATH') or die('No direct script access.');

class Workouts_Controller extends Website_Controller {

    function __construct(){
        parent::__construct();
        $this->session= Session::instance();
        $authentic = new Auth;
        if (!$authentic->logged_in()){
            $this->session->set("requested_url","/".url::current()); // this will redirect from the login page back to this page
            url::redirect('/user/login');
        }else{
            $this->user = $authentic->get_user(); //now you have access to user information stored in the database
        }
    }

	public function index(){

		$this->template->title = 'Exercises::BodyB site';
		$this->template->content = new View('pages/workouts');

		$exercises = new Exercise_Model($this->user->id);
		$groups = new Group_Model($this->user->id);
		$sets = new Set_Model($this->user->id);

		$this->template->content->sets = $sets->getFreeSets();

        // get data for exercises selector
        $groupList = $groups->getAll();
        $this->template->content->groups = $groupList;
		
		$exercisesArray = array();
		foreach($groupList as $item){

			$exercisesArray[$item->id] = $exercises->getByGroupId($item->id);
		}

		$this->template->content->exercisesArray = $exercisesArray;
	}

} 
