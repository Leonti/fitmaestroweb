<?php defined('SYSPATH') or die('No direct script access.');

class Probo_Controller extends Controller {

public function index(){
 //   echo "dyg";

    print_r(remoteUser::createUser("ddhg@ssss.com", "proverko"));
    print_r(remoteUser::loginUser("ddhg@ssss.com", "proverko"));
print_r(RemoteUser::checkUserByKey('49e14975c0cc383057cc6552090e8ac3'));
}


} 


