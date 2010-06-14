<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-06-10 02:40:21 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Unknown column 'units' in 'field list' - INSERT INTO `measurement_types` (`title`, `units`, `user_id`) VALUES ('weight', 'kg', 35) in file system/libraries/drivers/Database/Mysql.php on line 371
2010-06-10 02:46:29 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, json/measurements, could not be found. in file system/core/Kohana.php on line 841
2010-06-10 03:39:42 +02:00 --- error: Uncaught PHP Error: Missing argument 2 for Measurement_Model::deleteType(), called in /home/leonti/public_html/koh/application/controllers/ajaxpost.php on line 687 and defined in file application/models/measurement.php on line 25
2010-06-10 03:40:54 +02:00 --- error: Uncaught PHP Error: Missing argument 2 for Measurement_Model::deleteType(), called in /home/leonti/public_html/koh/application/controllers/ajaxpost.php on line 687 and defined in file application/models/measurement.php on line 25
2010-06-10 20:27:33 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, ajaxpost/save-measurement-entry?value=34&date=2010-06-05&type_id=2, could not be found. in file system/core/Kohana.php on line 841
