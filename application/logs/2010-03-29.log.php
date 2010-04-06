<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-03-29 15:55:15 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (groups.id = exercises.group_id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 15:59:16 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 16:00:12 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`, `exercises`.`group_id` AS `test`
FROM (`exercises`)
JOIN `groups` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 16:00:34 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 16:01:08 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`groups`)
JOIN `exercises` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 16:01:20 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 16:05:17 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 16:05:38 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-03-29 16:06:16 +02:00 --- error: Uncaught PHP Error: Missing argument 2 for Database_Core::join(), called in /home/leonti/public_html/koh/application/models/exercise.php on line 21 and defined in file system/libraries/Database.php on line 379
2010-03-29 16:06:35 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'NULL)
ORDER BY `exercises`.`id` ASC' at line 3 - SELECT `exercises`.`title` AS `exercise_title`, `exercises`.`desc`, `groups`.`title` AS `group_title`, `exercises`.`id`, `groups`.`id` AS `group_id`
FROM (`exercises`)
JOIN `groups` ON (exercises.group_id = groups.id NULL)
ORDER BY `exercises`.`id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
