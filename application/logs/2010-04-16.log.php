<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-04-16 21:55:22 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Column 'deleted' in where clause is ambiguous - SELECT `programs_connector`.*, `sets`.`title`
FROM (`programs_connector`)
JOIN `sets` ON (`programs_connector`.`set_id` = `sets`.`id`)
WHERE `deleted` = 0
AND `program_id` = '7'
AND `user_id` = 4
ORDER BY `day_number` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-16 21:56:04 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Column 'user_id' in where clause is ambiguous - SELECT `programs_connector`.*, `sets`.`title`
FROM (`programs_connector`)
JOIN `sets` ON (`programs_connector`.`set_id` = `sets`.`id`)
WHERE `programs_connector`.`deleted` = 0
AND `program_id` = '7'
AND `user_id` = 4
ORDER BY `day_number` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
