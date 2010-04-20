<div id="import-exercises" style="display:none;">
<a href = "#" id = "close-import">Close</a>
    <form>
        <div class = "accordion">
        <?php foreach ($publicGroups as $item){ ?>
            <h3><a href="#"><?php echo html::specialchars($item->title) ?></a></h3>
            <div><?php echo html::specialchars($item->desc); ?><br />
            <input type = "checkbox" name = "noimport_id[]" value = "<?php echo $item->id ?>" />
            Do not import group - add exercises to current one.
        <ul>
        <?php foreach($publicExercisesArray[$item->id] as $exercise){ ?>
            <li class = "{id: <?php echo html::specialchars($exercise->id); ?>}">
            <input type = "checkbox" name = "exercise_id[]" value = "<?php echo $exercise->id ?>" />
            <?php echo html::specialchars($exercise->title); ?>
            <div class = "exercise-desc"><?php echo html::specialchars($exercise->desc); ?>
            </div>
            </li>
        <?php }?>
        </ul>
        </div>
        <?php } ?>
        </div>
        <input type = "hidden" name = "current_group_id" value = "" />
        <input type = "submit" name = "submit" value = "Import" />
    </form>
</div>  
