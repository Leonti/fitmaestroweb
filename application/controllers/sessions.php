<?php defined('SYSPATH') or die('No direct script access.');

class Sessions_Controller extends Website_Controller {

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

    public function index($sessionId = null){

        $this->template->title = 'FitMaestro Sessions';
        $this->template->content = new View('pages/sessions');

        $exercises = new Exercise_Model($this->user->id);
        $groups = new Group_Model($this->user->id);
        $groupList = $groups->getAll();
        $this->template->content->groups = $groupList;
        $exercisesArray = array();
        foreach($groupList as $item){

            $exercisesArray[$item->id] = $exercises->getByGroupId($item->id);
        }

        $this->template->content->exercisesArray = $exercisesArray;

        $session_status = 'INPROGRESS';
        if($sessionId){
            $sessions = new Session_Model($this->user->id);
            $current_session = $sessions->getItem($sessionId);
            $session_status = $current_session[0]->status;
        }

        $this->template->content->session_id = $sessionId ? $sessionId : 0;
        $this->template->content->session_status = $session_status;
/*
        $settings = new Setting_Model($this->user->id);
        $userSettings = $settings->getSettings();
        $this->template->content->timeFormat = $userSettings->time_format;
        $this->template->content->timeZone = $userSettings->time_zone;
*/
    }

} 
