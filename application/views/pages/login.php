<h2>Login</h2>
<div class = "form">
<?php

    echo form::open(null, array('id' => 'login_form'));
    echo '<span class = "label" >Email: </span>' . form::input('username') . '<br /><br />';
    echo '<span class = "label" >Password: </span>' . form::password('password') . '<br /><br />';
    echo form::submit('submit', 'Login');

?>
</div>
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
 
