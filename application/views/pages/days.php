<style>td{border: dashed;}</style>
<a id = "add-set" href = "#">Add set</a>
<ul id = "set-list">
    <?php foreach ($sets as $item){ ?>
    <li><?php echo html::specialchars($item->title) ?></li>
    <?php } ?>
</ul>

<?php echo View::factory('pages_parts/set-exercises'); ?>
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
 
