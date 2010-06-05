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
), FALSE);

    // setting default value
    if(!isset($timeFormat)){
        $timeFormat = "ampm";
    }

    if(!isset($timeZone)){
        $timeZone = "Europe/Warsaw";
    }

echo '
    <script type = "text/javascript">
        var baseUrl ="' . url::base() . '";
        var timeFormat = "' . $timeFormat . '";
    </script>
';
$content->timeFormat = $timeFormat;
$content->timeZone = $timeZone;
?>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>

<title><?php echo html::specialchars($title) ?></title>
</head>
<body>
<div id = "header">
    <h1>FitMaestro</h1>
    <h2>Where health meets technology</h2>
    <div style = "clear: both;"></div>
</div>

    <ul class = "navigation-menu">
    <?php foreach ($links as $link => $url): ?>
    <li><?php echo html::anchor($url, $link) ?></li>
    <?php endforeach ?>
    </ul>

<?php echo $content ?>

<div id = "footer">
    <div id = "copyright">Copyright 2010 Leonty Belskiy</div>
    <div id = "footer-links">
        <a href = "http://fitmaestro.com/Privacy.html">Privacy Policy</a>
    </div>
</div>
</body>
</html>
 
