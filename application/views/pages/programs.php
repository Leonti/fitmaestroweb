<div class = "list-container">
    <a id = "add-program" href = "#" class = "add-link">Add program</a>
    <div style = "clear:both;"></div>
    <ul id = "program-list" class = "items-list"></ul>

    <div id = "program-description" class = "desc-box"></div>
    <div style = "clear:both;"></div>
</div>

<div class = "details-container">

    <div id = "program-workout" style = "display: none;">
        <?php echo View::factory('pages_parts/set-exercises'); ?>
    </div>

    <table id = "days" style = "display:none;">
        <thead>
            <tr>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th>Sunday</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <a id = "add-week" href = "#" class = "add-link-bottom" >Add week</a>
    <div style = "clear:both;"></div>
</div>
<?php

    $startProgramId = $programId ? $programId : 0;

    echo '
        <script type = "text/javascript">
            var startProgramId = ' . $startProgramId . ';
        </script>
    ';

	echo html::script(array
	      (
		  'media/js/programs.js',
          'media/js/workouts.js',
	      ), FALSE);

    $selector = new View('popups/selector-popup'); 
    $selector->exercisesArray = $exercisesArray;
    $selector->groups = $groups;
    $selector->render(TRUE);
	echo View::factory('popups/program-popup');
    echo View::factory('popups/set-popup');
    echo View::factory('popups/reps-popup');
    echo View::factory('popups/add-session-popup');

?>
 
