<?php defined('SYSPATH') or die('No direct script access.');

class Settings_Controller extends Website_Controller {

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

		$this->template->title = 'FitMaestro Settings';
		$this->template->content = new View('pages/settings');
                

        $settings = new Setting_Model($this->user->id);
        $this->template->content->userSettings = $settings->getSettings();

        $this->template->content->formData = array();
        if(isset($_POST['submit'])){
            $post = new Validation($_POST); 
    /* for the future :)
            $post->add_rules('email', 'required', array('valid','email'));
            $post->add_rules('email', 'required', 'valid::email'); 
            $post->add_rules('email', 'required', 'email'); */

            if($post->validate()){
                $postArray = $post->as_array();
                unset($postArray['submit']);
                $settings->saveSettings($postArray);
                $this->template->content->userSettings = $settings->getSettings();
                
                // clear to prevent repopuplating
                $this->template->content->formData = array();
            }else{
                echo 'Validation errors were found '.'<br />';
                // repopulating
                $this->template->content->formData = $post->as_array();
            }
        }

        $this->template->content->timeZones = timeConvert::getTimezones();


    }

} 
