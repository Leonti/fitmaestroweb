<?php defined('SYSPATH') or die('No direct script access.');

class Days_Controller extends Website_Controller {

	public function index(){

		$this->template->title = 'Exercises::BodyB site';
		$this->template->content = new View('pages/days');

		$exercises = new Exercise_Model();
		$groups = new Group_Model();
		$sets = new Set_Model();
		$groupList = $groups->getAll();
		$this->template->content->groups = $groupList;
		$this->template->content->sets = $sets->getAll();
		
		$exercisesArray = array();
		foreach($groupList as $item){

			$exercisesArray[$item->id] = $exercises->getByGroupId($item->id);
		}

		$this->template->content->exercisesArray = $exercisesArray;
	}

} 
