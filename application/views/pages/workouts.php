<div class = "list-container">
    <a id = "add-set" href = "#" class = "add-link">Add workout</a>
    <div style = "clear:both;"></div>
    <div class = "scrollpane-wrapper">
        <ul id = "set-list" class = "items-list" >
            <?php foreach ($sets as $item){ ?>
            <li><?php echo html::specialchars($item->title) ?></li>
            <?php } ?>
        </ul>
    </div>
    <div id = "set-description" class = "desc-box">
    </div>
    <div style = "clear:both;"></div>
</div>

<script type = "text/javascript">
    var freeSets = true;
</script>
<div class = "details-container">
    <?php echo View::factory('pages_parts/set-exercises'); ?>
</div>
<?php
	echo html::script(array
	      (
		  'media/js/workouts.js',
	      ), FALSE);
	$selector = new View('popups/selector-popup'); 
	$selector->exercisesArray = $exercisesArray;
	$selector->groups = $groups;
	$selector->render(TRUE);
	echo View::factory('popups/set-popup');
	echo View::factory('popups/reps-popup');
        echo View::factory('popups/add-session-popup');
?>
 
