<?php defined('SYSPATH') or die('No direct script access.');

class Exercises_Controller extends Website_Controller {

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
		$this->template->content = new View('pages/exercises');

		$exercises = new Exercise_Model($this->user->id);
		$groups = new Group_Model($this->user->id);
		$this->template->content->exercises = $exercises->getAll();
		$this->template->content->groups = $groups->getAll();
	}

}
