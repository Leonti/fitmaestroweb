<h2>Settings page</h2>

<?php

    $ampm = false;
    $twentyFour = false;
    if($timeFormat == "ampm"){
        $ampm = true;
    }else{
        $twentyFour = true;
    }
    echo '
        Time format:<br />';
    echo form::open(null, array('id' => 'settings_form'));
    echo form::radio('time_format', 'ampm', $ampm) . 'AM/PM<br />';
    echo form::radio('time_format', '24', $twentyFour) . '24 Hour<br /><br />';
    echo '
        Time zone:<br />';
    echo form::dropdown('time_zone', $timeZones, $timeZone) . '<br />';
    echo form::submit('submit', 'Save');

    echo '
        <script type = "text/javascript">
            $("#settings_form").populate(' . json_encode($formData) . ')
        </script>
    ';
?>
<?php
    echo html::script(array
          (
          'media/js/settings.js',
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
 
