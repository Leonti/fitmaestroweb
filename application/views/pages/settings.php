<h2>Settings page</h2>

<?php

    $ampm = false;
    $twentyFour = false;
    if($timeFormat == "ampm"){
        $ampm = true;
    }else{
        $twentyFour = true;
    }

    $kg = false;
    $lb = false;
    if($weightUnits == "kg"){
        $kg = true;
    }else{
        $lb = true;
    }

    $multiplicators = array(
                        array('0', 'Round to full numbers'),
                        array('0.5', '0.5 units'),
                        array('0.25', '0.25 units'),
                        array('0.1', '0.1 units'),
                        );



    echo '
        Time format:<br />';
    echo form::open(null, array('id' => 'settings_form'));
    echo form::radio('time_format', 'ampm', $ampm) . 'AM/PM<br />';
    echo form::radio('time_format', '24', $twentyFour) . '24 Hour<br /><br />';
    echo '
        Time zone:<br />';
    echo form::dropdown('time_zone', $timeZones, $timeZone) . '<br /><br />';

    echo '
        Weight units:<br />';
    echo form::open(null, array('id' => 'settings_form'));
    echo form::radio('weight_units', 'kg', $kg) . 'kg<br />';
    echo form::radio('weight_units', 'lb', $lb) . 'lb<br /><br />';

    echo '
        Weight calculation multiplicator:<br />';
    echo form::open(null, array('id' => 'settings_form'));
    foreach($multiplicators as $single_multiplicator){
        $checked = $single_multiplicator[0] == $multiplicator ? true : false;
        echo form::radio('multiplicator', $single_multiplicator[0], $checked) . $single_multiplicator[1]. '<br />';
    }
    echo '<br />';

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
 
