<?php defined('SYSPATH') or die('No direct script access.');

class User_Controller extends Website_Controller {

    public function register(){
        $this->template->title = 'Register::BodyB site';
        $this->template->content = new View('pages/register');

        $this->template->content->formData = array();

        if(isset($_POST['submit'])){
            $post = new Validation($_POST);

            $post->add_rules('username', 'required', array('valid','email'));
            $post->add_callbacks('username', array($this, '_unique_email'));
            $post->add_rules('password', 'required');

            if($post->validate()){
                echo 'No validation errors found ';
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
                echo 'Validation errors were found '.'<br />';
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

        $this->template->title = 'Login::BodyB site';
        $this->template->content = new View('pages/login');

        $this->template->content->formData = array();


        //Attempt login if form was submitted
        if ($post = $this->input->post()) {
            if (ORM::factory('user')->login($post)) {

                url::redirect($this->session->get('requested_url'));
            } else {
                $this->template->content->username = $post['username']; //Redisplay username (but not password) when form is redisplayed.
                $this->template->content->message = in_array('required', $post->errors()) ? 'Username and password are required.' : 'Invalid username and/or password.';
            }
        }
    }


    public function logout()
    {
        Auth::instance()->logout();
        url::redirect('home');
    }

	public function index(){
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