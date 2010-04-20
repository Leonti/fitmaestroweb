<div id="select-exercise" style="display:none;" title="Select exercise">
    <a href = "exercises">Edit Exercises</a>
    <div class = "accordion">
	<?php foreach ($groups as $item){ ?>
	    <h3><a href="#"><?php echo html::specialchars($item->title) ?></a></h3>
	    <div><?php echo html::specialchars($item->desc); ?> 
	<ul>
	<?php foreach($exercisesArray[$item->id] as $exercise){ ?>
	    <li class = "{id: <?php echo html::specialchars($exercise->id); ?>}">
		<?php echo html::specialchars($exercise->title); ?>
		<div class = "exercise-desc"><?php echo html::specialchars($exercise->desc); ?>
		</div>
	    </li>
	<?php }?>
	</ul>
	</div>
	<?php } ?>
    </div>
</div> 
