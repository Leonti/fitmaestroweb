<div class ="details-container">

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
        <p>
            <span class="emp">Time format:</span><br />';
    echo form::open(null, array('id' => 'settings_form'));
    echo form::radio('time_format', 'ampm', $ampm) . 'AM/PM<br />';
    echo form::radio('time_format', '24', $twentyFour) . '24 Hour</p>';
    echo '
        <p>
            <span class="emp">Time zone:</span><br />';
    echo form::dropdown('time_zone', $timeZones, $timeZone) . '</p>';

    echo '
        <p>
        <span class="emp">Weight units:</span><br />';
    echo form::open(null, array('id' => 'settings_form'));
    echo form::radio('weight_units', 'kg', $kg) . 'kg<br />';
    echo form::radio('weight_units', 'lb', $lb) . 'lb</p>';

    echo '
        <p>
        <span class="emp">Weight calculation multiplicator:</span><br />';
    echo form::open(null, array('id' => 'settings_form'));
    foreach($multiplicators as $single_multiplicator){
        $checked = $single_multiplicator[0] == $multiplicator ? true : false;
        echo form::radio('multiplicator', $single_multiplicator[0], $checked) . $single_multiplicator[1]. '<br />';
    }
    echo '</p>';

    echo form::submit('submit', 'Save');

    echo '
        <script type = "text/javascript">
            $("#settings_form").populate(' . json_encode($formData) . ')
        </script>
    ';
?>
</div>
<?php
    echo html::script(array
          (
          'media/js/settings.js',
          'media/js/jquery.populate.pack.js',
          ), FALSE);
?>
 
