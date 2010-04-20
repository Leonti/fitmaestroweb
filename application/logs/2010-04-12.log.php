<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-04-12 03:31:22 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry '' for key 'uniq_email' - INSERT INTO `users` (`username`, `password`) VALUES ('test@email.com', '73bf769122ec78bc6f6b5757eecc0a480a75f4449bc568541f') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-12 03:44:33 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:44:42 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:48:06 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:50:51 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:51:33 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:51:50 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:52:15 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:53:27 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:53:52 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 03:53:53 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, index, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 04:18:32 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'proverko@proverko.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('proverko@proverko.com', 'proverko@proverko.com', '604f0f66075fed5d7f437e3367c75f5626722a2853213578ba') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-12 04:27:08 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, login, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 21:35:34 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Exercise_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/sessions.php on line 22 and defined in file application/models/exercise.php on line 7
2010-04-12 21:36:16 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, groups, could not be found. in file system/core/Kohana.php on line 841
2010-04-12 21:36:50 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Group_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/json.php on line 14 and defined in file application/models/group.php on line 7
2010-04-12 21:36:50 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Exercise_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/json.php on line 27 and defined in file application/models/exercise.php on line 7
2010-04-12 21:39:59 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Column 'user_id' in where clause is ambiguous - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `exercises`.`ex_type`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (`exercises`.`group_id` = `groups`.`id`)
WHERE `exercises`.`deleted` = 0
AND `user_id` = 4
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-12 21:42:00 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Exercise_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/json.php on line 27 and defined in file application/models/exercise.php on line 7
2010-04-12 21:42:00 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Group_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/json.php on line 14 and defined in file application/models/group.php on line 7
2010-04-12 21:42:19 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Group_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/ajaxpost.php on line 15 and defined in file application/models/group.php on line 7
2010-04-12 21:42:23 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Group_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/json.php on line 14 and defined in file application/models/group.php on line 7
2010-04-12 21:42:23 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Exercise_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/json.php on line 27 and defined in file application/models/exercise.php on line 7
2010-04-12 21:45:34 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Group_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/ajaxpost.php on line 15 and defined in file application/models/group.php on line 7
2010-04-12 21:55:35 +02:00 --- error: Uncaught PHP Error: Missing argument 1 for Exercise_Model::__construct(), called in /home/leonti/public_html/koh/application/controllers/days.php on line 22 and defined in file application/models/exercise.php on line 7
2010-04-12 22:35:25 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Unknown column 'time_format' in 'field list' - INSERT INTO `groups` (`time_format`, `time_zone`, `user_id`) VALUES ('ampm', 'Europe/Warsaw', 7) in file system/libraries/drivers/Database/Mysql.php on line 371
