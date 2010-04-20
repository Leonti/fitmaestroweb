<?php defined('SYSPATH') or die('No direct script access.');

class Home_Controller extends Website_Controller {

	public function index(){

		$this->template->title = 'Home::BodyB site';
		$this->template->content = new View('pages/home');
	}

}
