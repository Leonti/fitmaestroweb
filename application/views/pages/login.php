<h2>Login</h2>

<?php

    echo form::open(null, array('id' => 'login_form'));
    echo form::input('username') . '<br />';
    echo form::password('password') . '<br /><br />';
    echo form::submit('submit', 'Login');

?>
<?php
    echo html::script(array
          (
          //'media/js/jquery.populate.pack.js',
          ), FALSE);
/*
    echo View::factory('popups/reps-session-popup');
    $selector = new View('popups/selector-popup'); 
    $selector->exercisesArray = $exercisesArray;
    $selector->groups = $groups;
    $selector->render(TRUE);
    echo View::factory('popups/session-popup');
*/
?>
 
