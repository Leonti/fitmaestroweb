<ul id = "sessions-filter">
<li id = "sessions-inprogress"><div style = "text-align: center; display: inline-block;">Current</div></li>
<li id = "sessions-done">Done</li>
<li id = "sessions-all">All</li>
</ul>

<div class = "list-container" id = "session-list-container">
    <a id = "start-session" href = "#" class = "add-link" >Start session</a>
    <div style = "clear:both;"></div>
    <div id = "session-list-wrapper">
        <ul id = "session-list" class = "items-list">
        </ul>
    </div>
    <div id = "session-description" class = "desc-box"></div>
    <div style = "clear:both;"></div>
    <div id = "session-stats"></div>
</div>

<div class = "details-container" style ="display:none;">
  <a href="#" id = "exercise-link" class = "add-link">Add exercise</a>
  <a href="#" id = "print-plan" class = "add-link">Print</a>
  <table id = "session-exercise-list" cellspacing="0">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Type</th>
                <th>Group</th>
                <th>Reps</th>
                <th>Done</th>
                <th class = "image-column no-pad non-printable" ></th>
            </tr>
        </thead>
      <tbody>
      </tbody>
  </table>
</div>
<?php

    $startSessionFilter = '';

    echo '
        <script type = "text/javascript">
            var startSessionId = ' . $session_id . ';
            var startSessionFilter = \'' . $session_status . '\';
        </script>
    ';


    // jScrollPane.css

    echo html::stylesheet(array
	(
        'media/css/jScrollPane',
	));

    echo html::script(array
          (
          'media/js/sessions.js',
          'media/js/timepicker.js',
          'media/js/jScrollPane.js',
          'media/js/jquery.mousewheel.js',
          ), FALSE);


    echo View::factory('popups/reps-session-popup');
    $selector = new View('popups/selector-popup'); 
    $selector->exercisesArray = $exercisesArray;
    $selector->groups = $groups;
    $selector->render(TRUE);
    echo View::factory('popups/session-popup');
/*
    $selector = new View('popups/selector-popup');
    $selector->exercisesArray = $exercisesArray;
    $selector->groups = $groups;
    $selector->render(TRUE);
    echo View::factory('popups/set-popup');

    echo View::factory('popups/add-session-popup');
*/
?>
 
