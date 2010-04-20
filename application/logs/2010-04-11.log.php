<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-04-11 03:29:51 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Unknown column 'submit' in 'field list' - UPDATE `settings` SET `time_format` = 'ampm', `time_zone` = 'Europe/Warsaw', `submit` = 'Save' WHERE `user_id` = 9 in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-11 03:31:20 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Setting_Model::saveSettings(), called in /home/leonti/public_html/koh/application/controllers/settings.php on line 27 and defined in file application/models/setting.php on line 17
2010-04-11 23:29:45 +02:00 --- error: Uncaught Exception: DateTime::__construct(): Failed to parse time string (undefined +5 minutes) at position 0 (u): The timezone could not be found in the database in file application/helpers/timeConvert.php on line 7
2010-04-11 23:29:52 +02:00 --- error: Uncaught Exception: DateTime::__construct(): Failed to parse time string (undefined +5 minutes) at position 0 (u): The timezone could not be found in the database in file application/helpers/timeConvert.php on line 7
