<?php defined('SYSPATH') or die('No direct script access.');

class Home_Controller extends Website_Controller {

        function __construct(){
        parent::__construct();
        
        $authentic = new Auth;
        $this->user = $authentic->get_user(); //now you have access to user information stored in the database

    }

    public function index(){

            $this->template->title = 'FitMaestro Home';

            if($this->user){
                $this->template->content = new View('pages/home');
            }else{
                $this->template->content = new View('pages/guest_home');
            }
    }

}
