<div class="login-block">
    <?php echo html::anchor('home', 'Main Page'); ?>
</div>
<div class = "form">
<?php

    echo form::open(null, array('id' => 'login_form'));
    echo '<span class = "label" >Email: </span>' . form::input('username') . '<br /><br />';
    echo '<span class = "label" >Password: </span>' . form::password('password') . '<br /><br />';
    echo form::submit('submit', 'Login');

?>
</div>
 
