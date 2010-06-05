<h2>Openid Login</h2>

    <p style="color:#f00;"><?php //echo $error; ?></p>

    <form name="openid_form" id="openid_form" action="" method="post" autocomplete="off">

        <!-- Your OpenID input should be named "openid_identifier" to follow best practices
         and in order to work with the ID Selector JavaScript, should you choose to use it. -->
        <label>
            OpenID URL: <input type="text" name="openid_identifier" id="openid_identifier" maxlength="320" />
        </label>

        <input type="hidden" name="process" value="1" />

        <button type="submit">Sign In With OpenID</button>

    </form>

<!-- BEGIN ID SELECTOR -->
<script type="text/javascript" id="__openidselector" src="https://www.idselector.com/selector/d76faa5c851c8addd7bc649f06e123a16a37a94c" charset="utf-8"></script>
<!-- END ID SELECTOR -->

 
