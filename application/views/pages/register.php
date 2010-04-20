<h2>Register</h2>

<?php

    echo form::open(null, array('id' => 'register_form'));
    echo form::input('username') . '<br />';
    echo form::password('password') . '<br /><br />';
    echo form::submit('submit', 'Register');

    echo '
        <script type = "text/javascript">
            $("#register_form").populate(' . json_encode($formData) . ')
        </script>
    ';
?>
<?php
    echo html::script(array
          (
          'media/js/jquery.populate.pack.js',
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
 
