<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-05-20 03:15:50 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, Privacy.html, could not be found. in file system/core/Kohana.php on line 841
2010-05-20 23:56:18 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Column 'user_id' in where clause is ambiguous - SELECT SUM(`reps` * `weight`) AS `sum`, `done`, `session_id`, `sessions`.`title` 
            FROM `log` 
            LEFT JOIN `sessions` ON `sessions`.`id` = `session_id` 
                WHERE `exercise_id` = '55' AND `done` 
                BETWEEN '2010-01-01' AND '2010-05-22' AND `user_id` = 4 
                GROUP BY `session_id` in file system/libraries/drivers/Database/Mysql.php on line 371
