<div id="exercise-edit" style="display:none;" title="Add exercise">
    <form id="main" action ="ajaxpost/saveexercise" method="post">

        <div class="column">
            <p>
                Title:<br />
                <input type="text" name = "title" />
            </p>
            <p>
                Type: 
                <select name = "ex_type">
                    <option value = "0">Own Weight</option>
                    <option value = "1">With Weight</option>
                </select>
            </p>
            <p id = "max-weight" style = "display: none;">
                Max Weight:
                <input type="text" name = "max_weight" class ="number" />
                <?php echo $weightUnits; ?>
            </p>
            <p id = "max-reps">
                Max Repetitions:<br />
                <input type="text" name = "max_reps" class ="number" />
            </p>
            <p>
                Group: <br />
                <select name = "group_id">
        <?php foreach ($groups as $item){ ?>
                <option value = "<?php echo html::specialchars($item->id) ?>"><?php echo html::specialchars($item->title) ?></option>
        <?php } ?>
                </select>
            </p>
            <p></p>
            <p style ="padding-bottom: 0;"><input type="submit" value="Save" /></p>
        </div>
        <div class="column">
            <p>
                Description:<br />
                <textarea rows="9" name = "desc" ></textarea>
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
