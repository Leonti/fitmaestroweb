<div id="exercise-edit" style="display:none;" title="Add exercise">
    <form>
	<p>
	    Title:<br />
	    <input type="text" name = "title" />
	</p>
	<p>
	    Type:<br />
	    <select name = "ex_type">
		<option value = "0">Own Weight</option>
		<option value = "1">With Weight</option>
	    </select>
	</p>
    <p id = "max-weight" style = "display: none;">
        Max Weight:<br />
        <input type="text" name = "max_weight" />
    </p>
    <p id = "max-reps">
        Max Repetitions:<br />
        <input type="text" name = "max_reps" />
    </p>
	<p>
	    Description:<br />
	    <textarea name = "desc" ></textarea>
	</p>
	<p>
	    <select name = "group_id">
    <?php foreach ($groups as $item){ ?>
	    <option value = "<?php echo html::specialchars($item->id) ?>"><?php echo html::specialchars($item->title) ?></option>
    <?php } ?>
	    </select>
	</p>
	<input type = "hidden" name = "id" value = "" />
	<input type="submit" value="Save" />
    </form>
</div> 
