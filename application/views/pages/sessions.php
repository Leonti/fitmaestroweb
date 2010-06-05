<ul id = "sessions-filter">
<li id = "sessions-inprogress">Current</li>
<li id = "sessions-done">Done</li>
<li id = "sessions-all">All</li>
</ul>

<div class = "list-container">
    <a id = "start-session" href = "#" class = "add-link" >Start session</a>
    <div style = "clear:both;"></div>
    <ul id = "session-list" class = "items-list">
    </ul>
    <div id = "session-description" class = "desc-box"></div>
    <div style = "clear:both;"></div>
    <div id = "session-stats"></div>
</div>

<div class = "details-container">
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
                <th class = "image-column no-pad" ></th>
            </tr>
        </thead>
      <tbody>
      </tbody>
  </table>
</div>
<?php
    $startSessionId = $sessionId ? $sessionId : 0;
    $startSessionFilter = '';

    echo '
        <script type = "text/javascript">
            var startSessionId = ' . $startSessionId . ';
            var startSessionFilter = \'' . $startSessionFilter . '\';
        </script>
    ';
    echo html::script(array
          (
          'media/js/sessions.js',
          'media/js/timepicker.js',
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
 
