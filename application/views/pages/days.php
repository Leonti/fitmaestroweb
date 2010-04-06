<style>td{border: dashed;}</style>
<a id = "add-set" href = "#">Add set</a>
<ul id = "set-list">
    <?php foreach ($sets as $item){ ?>
    <li><?php echo html::specialchars($item->title) ?></li>
    <?php } ?>
</ul>
<div id = "desc-holder">
    <div id = "set-description"></div>
    <a href = "#" id = "start-session-link" style = "display: none;">Start Session</a>
</div>

<div id = "set-exercises" style = "display: none;">
  <a href="#" id = "exercise-link">Add exercise</a>
  <table id = "set-exercise-list">
      <thead>
      <tr>
	  <th>Title</th>
	  <th>Description</th>
	  <th>Type</th>
	  <th>Group</th>
	  <th>Reps</th>
      </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
</div>
<?php 
	echo html::script(array
	      (
		  'media/js/days.js'
	      ), FALSE);
	$selector = new View('popups/selector-popup'); 
	$selector->exercisesArray = $exercisesArray;
	$selector->groups = $groups;
	$selector->render(TRUE);
	echo View::factory('popups/set-popup');
	echo View::factory('popups/reps-popup');
    echo View::factory('popups/add-session-popup');
?>
 
