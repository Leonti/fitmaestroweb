<?php defined('SYSPATH') or die('No direct script access.');

class Sessions_Controller extends Website_Controller {

    public function index(){

        $this->template->title = 'Sessions::BodyB site';
        $this->template->content = new View('pages/sessions');

        $sessions = new Session_Model();
        //$sessions->addSession(24);
    }

} 
