<a id = "add-group" href = "#">Add group</a>
<ul id = "group-list">
    <li id = "all-groups">All</li>
    <?php foreach ($groups as $item){ ?>
    <li><?php echo html::specialchars($item->title) ?></li>
    <?php } ?>
</ul>
<div id = "group-description">
    All groups
</div>
<h2>Exercises</h2>
<a id = "add-exercise" href = "#">Add exercise</a>
<a id = "import-exercises-link" href = "#">Import exercises</a>
<?php

    $importExercises = new View('pages_parts/import-exercises'); 
    $importExercises->publicExercisesArray = $publicExercisesArray;
    $importExercises->publicGroups = $publicGroups;
    $importExercises->render(TRUE);

?>
<table id = "exercise-list">
    <thead>
    <tr>
	<th>Title</th>
	<th>Description</th>
	<th>Type</th>
	<th id = "groups-th">Group</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($exercises as $item){ ?>
    <tr>
    <td><?php echo html::specialchars($item->exercise_title) ?></td>
    <td><?php echo html::specialchars($item->desc) ?></td>
    <td><?php echo html::specialchars($item->ex_type) ?></td>
    <td><?php echo html::specialchars($item->group_title) ?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<?php 
	echo html::script(array
	      (
		  'media/js/exercises.js'
	      ), FALSE);
	$popup = new View('popups/exercise-popup'); 
	$popup->groups = $groups;
	$popup->render(TRUE);

	echo View::factory('popups/group-popup');
?>
 
