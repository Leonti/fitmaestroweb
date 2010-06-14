<?php defined('SYSPATH') or die('No direct script access.');

class Measurements_Controller extends Website_Controller {

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

        $this->template->title = 'Measurements::BodyB site';
        $this->template->content = new View('pages/measurements');

        $measurements = new Measurement_Model($this->user->id);
    }

}