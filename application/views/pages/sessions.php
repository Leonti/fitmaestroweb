<style>td{border: dashed;}</style>
<ul id = "session-list">
</ul>
<div id = "desc-holder">
    <div id = "session-description"></div>
</div>

<div id = "session-exercises" style = "display: none;">
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
    echo html::script(array
          (
          'media/js/sessions.js'
          ), FALSE);

    echo View::factory('popups/reps-session-popup');

/*
    $selector = new View('popups/selector-popup');
    $selector->exercisesArray = $exercisesArray;
    $selector->groups = $groups;
    $selector->render(TRUE);
    echo View::factory('popups/set-popup');

    echo View::factory('popups/add-session-popup');
*/
?>
 
