<style>td{border: dashed;}</style>
<h2>Programs</h2>
<a id = "add-program" href = "#">Add program</a>
<ul id = "program-list"></ul>
<div id = "program-description"></div>
<br />
<?php echo View::factory('pages_parts/set-exercises'); ?>
<table id = "days" style = "display:none;">
    <thead>
        <tr>
            <th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<a id = "add-week" href = "#">Add week</a>
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
          'media/js/days.js',
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
 
