<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php

echo html::stylesheet(array
	(
        'media/css/reset',
        'media/css/site',
        'media/css/ui-lightness/jquery-ui-1.8.custom',
        'media/css/print',
        'media/css/dropdown/dropdown',
        'media/css/dropdown/default/default',
	),
	array
	(
        'screen, print',
        'screen',
        'screen',
        'print',
    ));


echo html::script(array
(
    'media/js/jquery-1.4.2.min.js',
    'media/js/jquery-ui-1.8.custom.min.js',
    'media/js/site.js',
    'media/js/fancyalert.js',
    'media/js/jquery.populate.pack.js',
    'media/js/jquery.metadata.js',
    'media/js/jquery.jqprint.js',
    'media/js/jquery.dropdown.js',
), FALSE);


    // setting default value
    if(!isset($timeFormat)){
        $timeFormat = "ampm";
    }

    if(!isset($timeZone)){
        $timeZone = "Europe/Warsaw";
    }

    if(!isset($weightUnits)){
        $weightUnits = "kg";
    }

    if(!isset($multiplicator)){
        $multiplicator = "0";
    }


echo '
    <script type = "text/javascript">
        var baseUrl ="' . url::base() . '";
        var timeFormat = "' . $timeFormat . '";
        var multiplicator = ' . $multiplicator . ';
        var weightUnits = "' . $weightUnits . '";
    </script>
';
$content->timeFormat = $timeFormat;
$content->timeZone = $timeZone;
$content->weightUnits = $weightUnits;
$content->multiplicator = $multiplicator;

?>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css' />

<title><?php echo html::specialchars($title) ?></title>
</head>
<body>
<div id = "header">
    <h1>FitMaestro</h1>
    <h2>Where health meets technology</h2>
    <div style = "clear: both;"></div>
</div>

    <?php if($user){ ?>
<div class="login-block">
    <?php echo html::anchor('user/logout', 'Sign Off'); ?>
</div>
<div id="menu-holder" style ="clear:both;">
    <div id="menu-wrapper">
        <ul id="nav" class="dropdown dropdown-horizontal">
            <?php 
            
            $count = 0;
            $links_count = count($links);
            foreach ($links as $link => $url){

                $count++;

                // we have submenus
                if(is_array($url)){
                    echo '<li><span class="dir">Programs</span><ul>';
                        foreach ($url as $sub_link => $sub_url){
                            echo '<li class = "submenu">' . html::anchor($sub_url, $sub_link) . '</li>';
                        }
                    echo '</ul></li>';
                }else{
                    $last_class = '';
                    if($count == $links_count){
                        $last_class = 'class = "last-menu-item"';
                    }
                    echo '<li ' . $last_class . '>' . html::anchor($url, $link) . '</li>';
                }
             } ?>
        </ul>
    </div>
    <div style="clear:both;"></div>
</div>
    <?php } ?>

<?php echo $content ?>

<div id = "footer">
    <div id = "copyright">Copyright 2010 Leonty Belskiy</div>
    <div id = "footer-links">
        <a href = "http://fitmaestro.com/Privacy.html">Privacy Policy</a>
    </div>
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19022201-1']);
  _gaq.push(['_setDomainName', '.fitmaestro.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
 
