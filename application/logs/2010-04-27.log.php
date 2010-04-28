<?php defined('SYSPATH') or die('No direct script access.'); ?>

2010-04-27 01:27:41 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, home/user/register, could not be found. in file system/core/Kohana.php on line 841
2010-04-27 01:28:57 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')
ORDER BY `id` ASC' at line 5 - SELECT *
FROM (`sets`)
WHERE `deleted` = 0
AND `user_id` = 9
AND `id` NOT IN ()
ORDER BY `id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 01:33:52 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')
ORDER BY `id` ASC' at line 5 - SELECT *
FROM (`sets`)
WHERE `deleted` = 0
AND `user_id` = 10
AND `id` NOT IN ()
ORDER BY `id` ASC in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 17:55:53 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, tests, could not be found. in file system/core/Kohana.php on line 841
2010-04-27 17:56:13 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, tests, could not be found. in file system/core/Kohana.php on line 841
2010-04-27 17:56:59 +02:00 --- error: Uncaught Kohana_404_Exception: The page you requested, probo, could not be found. in file system/core/Kohana.php on line 841
2010-04-27 18:07:53 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'dfgdf@sss.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('dfgdf@sss.com', 'dfgdf@sss.com', '7f94b95927f3344825e59b0da41a6c95fa3f57e5bb6bfec7af') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 18:10:48 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'dfgdf@sss.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('dfgdf@sss.com', 'dfgdf@sss.com', 'f68588b142e37364e3f9bb9612030ec010ff2d5a9f2cfbd9e1') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 18:10:50 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'dfgdf@sss.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('dfgdf@sss.com', 'dfgdf@sss.com', '4b0082d048b2d22f80441579fed81105b0308462eac23df120') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 18:10:51 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'dfgdf@sss.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('dfgdf@sss.com', 'dfgdf@sss.com', '8c3af81fd1f4492d554ec2d615ae4854c7480af0c29227af6a') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 18:10:54 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'dfgdf@sss.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('dfgdf@sss.com', 'dfgdf@sss.com', '02ee529682c64909f7fc1627454ac0861dd3a1c1e018b572a3') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 18:11:02 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'dfgdf@sss.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('dfgdf@sss.com', 'dfgdf@sss.com', '710533debc8cfc642c4acf78ed93a01de317e19b4ee610c508') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 18:11:03 +02:00 --- error: Uncaught Kohana_Database_Exception: There was an SQL error: Duplicate entry 'dfgdf@sss.com' for key 'uniq_username' - INSERT INTO `users` (`username`, `email`, `password`) VALUES ('dfgdf@sss.com', 'dfgdf@sss.com', 'a8ea3eecfebaef92f115a655fdba5ab2b4b5004907a78f35e2') in file system/libraries/drivers/Database/Mysql.php on line 371
2010-04-27 21:30:45 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:32:29 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:36:19 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:37:23 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:39:45 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:40:45 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:45:27 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:47:21 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 24
2010-04-27 21:51:59 +02:00 --- error: Uncaught PHP Error: error_log(log): failed to open stream: Permission denied in file application/controllers/remote.php on line 3
2010-04-27 22:41:20 +02:00 --- error: Uncaught Kohana_Exception: Callback password used for Validation is not callable in file system/libraries/Validation.php on line 250
