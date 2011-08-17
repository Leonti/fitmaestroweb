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

<div class = "form shadowed-box">
<?php

    echo form::open(null, array('id' => 'login_form'));
    echo '<span class = "label" >Email: </span>' . form::input('username') . '<br /><br />';
    echo '<span class = "label" >Password: </span>' . form::password('password') . '<br /><br />';
    echo form::submit('submit', 'Login');
    echo form::close();

?>
<br /><a href="#" id="fb-login">Login with Facebook</a>
</div>
 
<script type="text/javascript">
    window.fbAsyncInit = function() {
        FB.init({
            appId  : '124862400943074',
            status : true, // check login status
            cookie : true, // enable cookies to allow the server to access the session
            xfbml  : true,  // parse XFBML
            oauth : true //enables OAuth 2.0
        });
        
        $('#fb-login').click(function() {
            FB.login(function(response) {
                if (response.authResponse) {
                    window.location.reload();
                }
            }, { scope: 'email' });

            return false;
        });   
    };

  (function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
</script> 