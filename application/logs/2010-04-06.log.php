<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-04-06 21:31:22 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Unknown column 'exercises.sets_connector_id' in 'field list' - SELECT `exercises`.`id`, `exercises`.`title`, `exercises`.`desc`, `exercises`.`max_weight`, `exercises`.`ex_type`, `exercises`.`sets_connector_id`, `groups`.`title` AS `group_title`, `sessions_connector`.`id` AS `connector_id`
FROM (`sessions_connector`)
JOIN `exercises` ON (`sessions_connector`.`exercise_id` = `exercises`.`id`)
JOIN `groups` ON (`exercises`.`group_id` = `groups`.`id`)
WHERE `exercises`.`deleted` = 0
AND `sessions_connector`.`deleted` = 0
AND `sessions_connector`.`session_id` = '17'
ORDER BY `sessions_connector`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-06 21:32:09 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Unknown column 'exercises.sets_connector_id' in 'field list' - SELECT `exercises`.`id`, `exercises`.`title`, `exercises`.`desc`, `exercises`.`max_weight`, `exercises`.`ex_type`, `exercises`.`sets_connector_id`, `groups`.`title` AS `group_title`, `sessions_connector`.`id` AS `connector_id`
FROM (`sessions_connector`)
JOIN `exercises` ON (`sessions_connector`.`exercise_id` = `exercises`.`id`)
JOIN `groups` ON (`exercises`.`group_id` = `groups`.`id`)
WHERE `exercises`.`deleted` = 0
AND `sessions_connector`.`deleted` = 0
AND `sessions_connector`.`session_id` = '16'
ORDER BY `sessions_connector`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-06 21:32:18 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Unknown column 'exercises.sets_connector_id' in 'field list' - SELECT `exercises`.`id`, `exercises`.`title`, `exercises`.`desc`, `exercises`.`max_weight`, `exercises`.`ex_type`, `exercises`.`sets_connector_id`, `groups`.`title` AS `group_title`, `sessions_connector`.`id` AS `connector_id`
FROM (`sessions_connector`)
JOIN `exercises` ON (`sessions_connector`.`exercise_id` = `exercises`.`id`)
JOIN `groups` ON (`exercises`.`group_id` = `groups`.`id`)
WHERE `exercises`.`deleted` = 0
AND `sessions_connector`.`deleted` = 0
AND `sessions_connector`.`session_id` = '17'
ORDER BY `sessions_connector`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
