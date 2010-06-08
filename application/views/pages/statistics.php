<div class = "details-container">
    Start: <input type = "text" id = "start-date" value = "<?php echo date("m/d/Y", strtotime('-1 month')); ?>" /> 
    End: <input type = "text" id = "end-date" value = "<?php echo date("m/d/Y"); ?>" /> 
    <input type = "submit" value = "Refresh" id = "get-stats" /><br /><br />
    Stats type:
    <select id = "stats-type">
        <option value = "weight_log">Weight Log</option>
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

    <div id="holder"></div>
    <table id = "statistics-list">
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

</div>
<?php
    echo html::script(array
          (
          'media/js/raphael-min.js',
          'media/js/plugins/raphael.path.methods.js',
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
 
