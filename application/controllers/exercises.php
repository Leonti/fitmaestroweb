<?php defined('SYSPATH') or die('No direct script access.');

class Exercises_Controller extends Website_Controller {

	public function index(){

		$this->template->title = 'Exercises::BodyB site';
		$this->template->content = new View('pages/exercises');

		$exercises = new Exercise_Model();
		$groups = new Group_Model();
		$this->template->content->exercises = $exercises->getAll();
		$this->template->content->groups = $groups->getAll();
	}

}
