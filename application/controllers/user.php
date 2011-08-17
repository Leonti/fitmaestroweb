<?php defined('SYSPATH') or die('No direct script access.');

class User_Controller extends Website_Controller {


    private $register_errors_mapping = array(
        'username' => array(
                'email' => 'Invalid email format.',
                'required' => 'Email is required.',
            ),
            'password' => array(
                'required' => 'Password is required.',
            ),
            'repeat_password' => array(
                'matches' => 'Passwords do not match.',
            ),
        );

        private $login_errors_mapping = array(
            'username' => array(
                'required' => 'Email is required.',
                'length' => 'Invalid email format.',
                'invalid' => 'Invalid username and/or password.',
            ),
            'password' => array(
                'required' => 'Password is required.',
                'length' => 'Invalid username and/or password.', // if password is too short - just display message that it is invalid
            ),
        );

    public function register(){
        $this->template->title = 'FitMaestro Register';
        $this->template->content = new View('pages/register');

        $this->template->content->formData = array();
        $this->template->content->errors = array();

        if(isset($_POST['submit'])){
            $post = new Validation($_POST);

            $post->add_rules('username', 'required', array('valid','email'));
            $post->add_callbacks('username', array($this, '_unique_email'));
            $post->add_rules('password', 'required');
            $post->add_rules('repeat_password', 'matches[password]');

            if($post->validate()){
                $username = $this->input->post('username');
                $password = $this->input->post('password');

                // instantiate User_Model and set attributes to the $_POST data
                $user = ORM::factory('user');
                $user->username = $username;
                $user->email = $username;
                $user->password = $password;

                // if the user was successfully created...
                if ($user->add(ORM::factory('role', 'login')) AND $user->save()) {

                    // login using the collected data
                    Auth::instance()->login($username, $password);

                    $settings = new Setting_Model(Auth::instance()->get_user()->id);
                    $settings->addItem(array(
                                            'time_format' => 'ampm',
                                            'time_zone' => 'Europe/Warsaw',
                                             ));
                    // redirect to somewhere
                    url::redirect('settings');
                }

            }else{

                $this->template->content->errors_mapping = $this->register_errors_mapping;
                $this->template->content->errors = $post->errors();

                // repopulating
                $this->template->content->formData = $post->as_array();
            }
        }
    }

    public function login(){
        
        
        
        
        //Check if already logged in
        if (Auth::instance()->logged_in('login')) {
            url::redirect('exercises');
        } else if (Auth::instance()->logged_in()) {
            url::redirect('accessdenied'); //User hasn't confirmed account yet
        }

        // User is not logged in - let's see if he is logged in Facebook and we are connected
        require_once('fbsdk/src/facebook.php');
        $config = Kohana::config('config');
        $facebook = new Facebook(array(
          'appId' => $config['facebook']['appId'],
          'secret' => $config['facebook']['secret'],
        ));

        $fb_user = $facebook->getUser();
        if ($fb_user) {
            try {
            // Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $fb_user = null;
            }
        }

        if (isset($user_profile)) {

            $user_model = ORM::factory('user');
            $fb_status = $user_model->fb_login($user_profile['id'], $user_profile['email']);

            if (Auth::instance()->logged_in('login')) {
                if ($fb_status == $user_model::FB_NEW_LOGGEDIN) {
                    $settings = new Setting_Model(Auth::instance()->get_user()->id);
                    $settings->addItem(array(
                                            'time_format' => 'ampm',
                                            'time_zone' => 'Europe/Warsaw',
                                             ));
                    // redirect to somewhere
                    url::redirect('settings');                    
                } else {
                    url::redirect($this->session->get('requested_url'));                    
                }
            }
        }       
        
        $this->template->title = 'FitMaestro Login';
        $this->template->content = new View('pages/login');

        $this->template->content->formData = array();
        $this->template->content->errors = array();


        //Attempt login if form was submitted
        if ($post = $this->input->post()) {

            if (ORM::factory('user')->login($post)) {

                url::redirect($this->session->get('requested_url'));
            } else {
 
                $this->template->content->username = $post['username']; //Redisplay username (but not password) when form is redisplayed.
                $this->template->content->errors = $post->errors();
                $this->template->content->errors_mapping = $this->login_errors_mapping;
            }
            
        }
    }


    public function logout()
    {
        Auth::instance()->logout();
        url::redirect('home');
    }

    public function index() {
        url::redirect('user/login');
    }

    public function _unique_email(Validation $array, $field)
    {
    // check the database for existing records
    $email_exists = (bool) ORM::factory('user')->where('email', $array[$field])->count_all();

    if ($email_exists)
    {
        // add error to validation object
        $array->add_error($field, 'email_exists');
    }
    }

} 
