<div id="select-exercise" style="display:none;" title="Select exercise">
    <a href = "exercises">Edit Exercises</a><br />

    <div>
        <select id = "group-select">
    <?php foreach ($groups as $item){ ?>
        <option value = "<?php echo $item->id; ?>"><a href="#"><?php echo html::specialchars($item->title) ?></a></option>
    <?php } ?>
        </select>

        <select id = "exercise-select"></select>
        <br />
        <input type = "button" value = "Select" id = "select-exercise-button" />
    </div>
</div> 
