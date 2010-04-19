<style>td{border: dashed;}</style>

<a id = "start-session" href = "#">Start session</a>
<ul id = "session-list">
</ul>
<div id = "desc-holder">
    <div id = "session-description"></div>
</div>

<div id = "session-exercises" style = "display: none;">
  <a href="#" id = "exercise-link">Add exercise</a>
  <a href="#" id = "session-done">Done!</a>
  <a href="#" id = "print-plan">Print</a>
  <table id = "session-exercise-list">
      <thead>
      <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Type</th>
      <th>Group</th>
      <th>Reps</th>
      <th>Done</th>
      </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
</div>
<?php
    $startSessionId = $sessionId ? $sessionId : 0;

    echo '
        <script type = "text/javascript">
            var startSessionId = ' . $startSessionId . ';
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
 
