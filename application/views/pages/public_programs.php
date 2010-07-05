<h2>Public programs list</h2>
<?php 
    foreach($public_programs as $public_program){
?>
    <div class = "public-program">
        <div class = "public-program-title">
            <?php echo $public_program->title; ?>
        </div>
        <div class = "public-program-desc">
            <?php echo $public_program->desc; ?>
        </div>

        <?php
            $testProgram = $programs_model->getByPublicId($public_program->id);
            if(count($testProgram) == 0){
        ?>
            <a href = "import/<?php echo $public_program->id; ?>" class = "import-link">Import Program</a>
            <div style = "clear: both;"></div>
        <?php } ?>

    </div>
<?php } ?>
 
