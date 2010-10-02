<div class = "list-container">
    <a id = "add-group" href = "#" class = "add-link">Add group</a><br />
    <div style = "clear:both;"></div>
    <ul id = "group-list" class = "items-list" >
        <li id = "all-groups" class = "list-title">All</li>
        <?php foreach ($groups as $item){ ?>
        <li><?php echo html::specialchars($item->title) ?></li>
        <?php } ?>
    </ul>
    <div id = "group-description" class = "desc-box">All groups</div>
    <div style = "clear:both;"></div>
</div>

<div class = "details-container">
    <a id = "add-exercise" href = "#" class = "add-link">Add exercise</a>
    <a id = "import-exercises-link" href = "#" class = "add-link">Import exercises</a>
    <table id = "exercise-list">
        <thead>
        <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Type</th>
        <th id = "groups-th">Group</th>
        <th class = "image-column no-pad no-right" ></th>
        <th class = "image-column no-pad no-right" ></th>
        <th class = "image-column no-pad" ></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($exercises as $item){ ?>
        <tr>
        <td><?php echo html::specialchars($item->title) ?></td>
        <td><?php echo html::specialchars($item->desc) ?></td>
        <td><?php echo html::specialchars($item->ex_type) ?></td>
        <td><?php echo html::specialchars($item->group_title) ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php 
	echo html::script(array
	      (
		  'media/js/exercises.js',
                  'media/js/jquery.form.js',
	      ), FALSE);
	$popup = new View('popups/exercise-popup'); 
	$popup->groups = $groups;
	$popup->render(TRUE);

	echo View::factory('popups/group-popup');


    $importExercises = new View('popups/import-exercises-popup'); 
    $importExercises->publicExercisesArray = $publicExercisesArray;
    $importExercises->publicGroups = $publicGroups;
    $importExercises->render(TRUE);
?>
 
