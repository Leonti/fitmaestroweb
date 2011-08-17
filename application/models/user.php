<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_Model extends Auth_User_Model {

    const FB_NEW_LOGGEDIN = 1;
    const FB_OLD_LOGGEDIN = 2;
	// This class can be replaced or extended

    //redefines login method to allow short passwords
	/**
	 * Validates login information from an array, and optionally redirects
	 * after a successful login.
	 *
	 * @param  array    values to check
	 * @param  string   URI or URL to redirect to
	 * @return boolean
	 */
	public function login(array & $array, $redirect = FALSE)
	{
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('username', 'required', 'length[4,127]')
			->add_rules('password', 'required', 'length[1,42]');

		// Login starts out invalid
		$status = FALSE;

		if ($array->validate())
		{
			// Attempt to load the user
			$this->find($array['username']);

			if ($this->loaded AND Auth::instance()->login($this, $array['password']))
			{
				if (is_string($redirect))
				{
					// Redirect after a successful login
					url::redirect($redirect);
				}

				// Login is successful
				$status = TRUE;
			}
			else
			{
				$array->add_error('username', 'invalid');
			}
		}

		return $status;
	}
        
        public function fb_login($fb_id, $email) {

            // Login starts out invalid
            $status = 0;
            $new_user = false;
            $this->where('fb_id', $fb_id)->find();
            
            // this fb id is not in database - check if email is
            if (!$this->loaded) {
                $this->find($email);
                if ($this->loaded) {
                    $this->fb_id = $fb_id;
                    $this->save();
                } else {
                    // create new user entry 
                    $this->username = $email;
                    $this->email = $email;
                 //   $this->password = 'somepassword';
                    $this->fb_id = $fb_id;
                    $this->add(ORM::factory('role', 'login'));
                    $this->save();
                    $new_user = true;
                }
            }
                      
            if ($this->loaded) {
                Auth::instance()->force_login($this);
            }
            
            if (Auth::instance()->logged_in('login')) {
                if ($new_user) {
                    $status = self::FB_NEW_LOGGEDIN;
                } else {
                    $status = self::FB_OLD_LOGGEDIN;
                }                
            } 
            return $status;
        }
} // End User Model