<div id="exercise-edit" class="exerciseForm" style="display:none;" title="Add exercise">
    <form id="main" action ="ajaxpost/saveexercise" method="post">

	<div class="row">
		<input class="title" type="text" name = "title" />	
	</div>

	<div class="row">
		<label>Group:</label>
		<select name = "group_id">
		<?php foreach ($groups as $item){ ?>
			<option value = "<?php echo html::specialchars($item->id) ?>"><?php echo html::specialchars($item->title) ?></option>
		<?php } ?>
		</select>
	</div>	

	<div class="row">
		<label>Type:</label> 
		<select name = "ex_type">
			<option value = "0">Own Weight</option>
			<option value = "1">With Weight</option>
		</select>
		<span id = "max-weight" style = "display: none;">
			Max Weight:
			<input type="text" name = "max_weight" class ="number" />
			<?php echo $weightUnits; ?>
		</span>
		<span id = "max-reps">
			Max Repetitions:
			<input type="text" name = "max_reps" class ="number" />
		</span>					
	</div>
	<div class="row">
		<label>Description:</label><br />
       	<textarea rows="5" name = "desc"></textarea>	
	</div>
	<div class="row">
		<input type="submit" value="Save" />
	</div>
	
        <div class="column">
            <p>

            </p>
        </div>
        <input type = "hidden" name = "id" value = "" />
        <input type = "hidden" name ="file_name" value ="" />
    </form>

     <form id="file" enctype="multipart/form-data" action ="ajaxpost/loadimage" method="post">
        <div class="column">
            <div id="image-holder">
            </div>
            <div class="fileinput-wrapper">
                Select Image...
                <input type="file" id="image" name ="image" style ="font-size: 500px;" />
            </div>
        </div>
     </form>

</div> 
