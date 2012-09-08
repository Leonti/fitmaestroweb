<?php defined('SYSPATH') OR die('No direct access allowed.');

class remoteUser_Core{

    public static function checkUserByKey($authKey){

        $result = null;
        $user = ORM::factory('user')->where(array('auth_key' => $authKey))->find();

        if($user->id){
            return $user->id;
        }
    }

    public static function loginUser($email, $password){

        $result = 'INVALID';
        $authKey = '';

        $user = ORM::factory('user')->where(array('email' => $email))->find();
        Auth::instance()->login($user->username, $password);

        // if credentials are correct - generate new authKey
        if(Auth::instance()->logged_in('login')){

            $result = 'LOGGEDIN';
            $user->auth_key = $authKey = self::generateAuthKey($email);
            $user->save();
        }

        return array('result' => $result, 'authkey' => $authKey);
    }

    public static function createUser($email, $password){


        $result = 'FAILED';
        $authKey = '';

        // instantiate User_Model and set attributes to the $_POST data
        $user = ORM::factory('user');
        $user->username = $email;
        $user->email = $email;
        $user->password = $password;
        $user->auth_key = self::generateAuthKey($email);

        if($user->where('email', $email)->count_all()){
            $result = 'EXISTS';
        }elseif($user->add(ORM::factory('role', 'login')) && $user->save()){


            $settings = new Setting_Model($user->id);
            $settings->addItem(array(
                                    'time_format' => 'ampm',
                                    'time_zone' => 'Europe/Warsaw',
                                        ));
            $authKey = $user->auth_key;
            $result = 'CREATED';

        }

        return array('result' => $result, 'authkey' => $authKey);

    }

    public static function generateAuthKey($email){
        return $authKey = encryptStr(randStr(10) . $email);
    }

}

    function encryptStr($string){

        $salt = 'asdfsdfsdfa';
        $string = md5($salt . $string);
        return $string;
    }

    function randStr($length){

        $randstr = "";
        for($i=0; $i<$length; $i++){
                $randnum = mt_rand(0,61);
                if($randnum < 10){
                $randstr .= chr($randnum+48);
                }else if($randnum < 36){
                $randstr .= chr($randnum+55);
                }else{
                $randstr .= chr($randnum+61);
                }
        }
        return $randstr;
    }