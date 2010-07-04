<div class = "details-container">
    <?php echo View::factory('pages_parts/dates_selector'); ?><br /><br />
    Stats type:
    <select id = "stats-type">
        <option value = "measurements_log">Measurements Log</option>
        <option value = "exercise_log">Exercise Progress</option>
    </select>
    <div id = "exercise-holder" style = "display: none;">
        <span id = "exercise-label">Exercise: </span><span id = "exercise-name"></span>
        <a href = "#" id = "exercise-change">Click to change</a>
        <select id = "stats-subtype">
            <option value = "max">Max reps/weight</option>
            <option value = "total">Weight/reps sum</option>
        </select>
    </div>
    <div id = "measurements-holder">
        <select id = "measurements-type">
        <?php foreach($measurement_types as $type){ ?>
            <option value = "<?php echo $type->id; ?>"><?php echo $type->title; ?></option>
        <?php }?>
        </select>
    </div>

    <div id="holder"></div>
    <table id = "statistics-list" style = "display: none;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Session</th>
                <th>Reps</th>
                <th class = "to-hide">Weight</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <table id = "measurements-log-list">
        <thead>
            <tr>
                <th>Date</th>
                <th>Value</th>
                <th class = "image-column no-pad no-right" ></th>
                <th class = "image-column no-pad" ></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>
<?php
    echo html::script(array
          (
          'media/js/measurements.js',
          'media/js/statistics.js',
          ), FALSE);

    echo html::stylesheet(array
        (
            'media/css/statistics',
        ),
        array
        (
            'screen, print',
        ));

    $selector = new View('popups/selector-popup'); 
    $selector->exercisesArray = $exercisesArray;
    $selector->groups = $groups;
    $selector->render(TRUE);
?>
 
