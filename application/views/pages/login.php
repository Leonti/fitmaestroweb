<div class="login-block">
    <?php echo html::anchor('home', 'Main Page'); ?>
</div>

<?php
    if(count($errors) > 0){
?>
    <div id="errors">
        Please correct following errors: <br />
        <ul>
        <?php
            foreach($errors as $key => $value){
                echo '<li>' . $errors_mapping[$key][$value] . '</li>';
            }
        ?>
        </ul>
    </div>
        <?php
    }
?>

<div class = "form">
<?php

    echo form::open(null, array('id' => 'login_form'));
    echo '<span class = "label" >Email: </span>' . form::input('username') . '<br /><br />';
    echo '<span class = "label" >Password: </span>' . form::password('password') . '<br /><br />';
    echo form::submit('submit', 'Login');
    echo form::close();

?>
</div>
 
