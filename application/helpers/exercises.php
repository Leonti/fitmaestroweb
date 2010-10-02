<?php

    class exercises_Core  extends Model{

	public $userId;
	public $convertMap;
	public $convertTimeMap;
	public $timeDiff = 0;

/************************************************
Start of GROUPS
************************************************/
	public function addGroup($title, $desc, $phone_id = 0){
	    
		$title = mysql_real_escape_string($title);
		$desc = mysql_real_escape_string($desc);
		$phone_id = intval($phone_id);

		$sql = "INSERT INTO `groups` (`id`, `title`, `desc`, `phone_id`, `user_id`, `updated`)"
			. " VALUES (NULL, '$title', '$desc', '$phone_id', '" . $this -> userId . "', CURRENT_TIMESTAMP)";
		mysql_unbuffered_query($sql);
		return mysql_insert_id();
	}

	public function addItem($table, $data){

		$data = escapeArray($data);

		// check if item with such phone_id already exists
		if(!$this -> getSiteId($table, $data['phone_id'])){

			$data['user_id'] = $this -> userId;
			$sql = "INSERT INTO `$table` " . formatInsertString($data);
			mysql_unbuffered_query($sql);
			return mysql_insert_id();
		}else{

			return false;
		}

	}

	public function updateItem($table, $id, $data){

		$id = intval($id);
		$data = escapeArray($data);

		$sql = 	"UPDATE `$table` " . formatUpdateString($data) . " WHERE `id` = $id LIMIT 1";
		return mysql_unbuffered_query($sql);
	}

    public function resetPhoneIds($table){

        $sql =  "UPDATE `$table` SET `phone_id` = 0 WHERE `user_id` = " . $this -> userId;
        return mysql_unbuffered_query($sql);
    }

	public function getItem($table, $id){

		$id = intval($id);
		$row = null;
		$sql = "SELECT *, UNIX_TIMESTAMP(`updated`) AS 'stamp' FROM `$table` WHERE `id` = '$id'";
		if($res = mysql_query($sql)){
			
			$row = mysql_fetch_assoc($res);
		}
		  
		return $row;
	}

	public function getSiteId($table, $phoneId){

		$phoneId = intval($phoneId);

        // for cases when id can be zero (for example free sets, free session exercises)
        // watch for regressions!
        if($phoneId == 0){
            return 0;
        }

		$siteId = 0;
		$sql = "SELECT `id` FROM `$table` WHERE `phone_id` = $phoneId AND `user_id` = " . $this -> userId . " LIMIT 1";
		if($res = mysql_query($sql)){
			
			$row = mysql_fetch_assoc($res);
			$siteId = $row['id'];
		}

		return $siteId;
	}

	public function getSiteTime($phoneTime){

		return date('o-m-d H:i:s', strtotime($time) + $this -> timeDiff);
	}

	public function getPhoneTime($siteTime){

		return date('o-m-d H:i:s', strtotime($time) - $this -> timeDiff);
	}

	public function getPhoneId($table, $siteId){

		$siteId = intval($siteId);

        // for cases when id can be zero (for example free sets, free session exercises)
        if($siteId == 0){
            return 0;
        }

		$phoneId = 0;
		$sql = "SELECT `phone_id` FROM `$table` WHERE `id` = $siteId AND `user_id` = " . $this -> userId . " LIMIT 1";
		if($res = mysql_query($sql)){
			
			$row = mysql_fetch_assoc($res);
			$phoneId = $row['phone_id'];
		}

		return $phoneId;
	}

	// takes an array and converts table connections to phone id's
	public function convertToPhone($data){

                // flag to determine if we have some id which is not mappable to phone id yet
                // so we can take it into account later
                $new_id = 0;

		// remap site id's to phone id's
		foreach($data as $fieldName => $value){

			if(isset($this -> convertMap[$fieldName])){

				$data[$fieldName] = $this -> getPhoneId($this -> convertMap[$fieldName], $data[$fieldName]);

                                if($data[$fieldName] == 0){
                                    $new_id = 1;
                                }
			}
  
			if(isset($this -> convertTimeMap[$fieldName])){

				$data[$fieldName] = $this -> getPhoneTime($data[$fieldName]);
			}
		}

                $data['new_id'] = $new_id;

		return $data;
	}

	public function convertToSite($data){

		// remap site id's to phone id's
		foreach($data as $fieldName => $value){

			if(isset($this -> convertMap[$fieldName])){

				$data[$fieldName] = $this -> getSiteId($this -> convertMap[$fieldName], $data[$fieldName]);
			}

			if(isset($this -> convertTimeMap[$fieldName])){

				$data[$fieldName] = $this -> getSiteTime($data[$fieldName]);
			}
		}

		return $data;
	}

	// get items with 'updated' after certain point
	public function getUpdatedItems($table, $date){

		$date = mysql_real_escape_string($date);
		$items = array();

		$sql = "SELECT *, UNIX_TIMESTAMP(`updated`) AS 'stamp' FROM `$table` WHERE `updated` > '$date' AND `user_id` =" . $this -> userId .
                " OR `id` IN (SELECT `item_id` FROM `request_update` WHERE `table` = '$table' AND `user_id` = " . $this -> userId . ")";
		if($res = mysql_query($sql)){
			
			$items = array();
			while($row = mysql_fetch_assoc($res)){
			
				$items[$row['id']] = $this -> convertToPhone($row);
			}
		}

		return $items;
	}

        // adding item to the queue inspecific cases (when it's not yet ready but will be for the second update round)
        public function addRequestUpdate($table, $itemId){

            mysql_unbuffered_query("INSERT INTO `request_update` (`id`, `table`, `item_id`, `user_id`) VALUES (NULL, '$table', '$itemId', '" . $this -> userId . "')");
        }

        public function clearRequestUpdates(){

            mysql_unbuffered_query("DELETE FROM `request_update` WHERE `user_id` = " . $this -> userId);
        }


/************************************************
End of GROUPS
************************************************/

/************************************************
Start of EXERCISES
************************************************/
	public function addExercise($data){

		$defaults = array('title' => '',
			      'desc' => '',
			      'group_id' => 0,
			      'phone_group_id' => 0,
			      'type' => 0,
			      'phone_id' => 0);

		$data = escapeArray($data, $defaults);

		$sql = "INSERT INTO `exercises` (`id`, `title`, `desc`, `type`, `group_id`, `phone_id`, `phone_group_id`, `user_id`, `updated`)"
			. " VALUES (NULL, '{$data['title']}', '{$data['desc']}', '{$data['type']}', '{$data['group_id']}', '{$data['phone_id']}', '{$data['phone_group_id']}', '" 
			. $this -> userId . "', CURRENT_TIMESTAMP)";
		mysql_unbuffered_query($sql);
		return mysql_insert_id();
	}


/************************************************
End of EXERCISES
************************************************/	

/************************************************
Start of SETS
************************************************/
	public function addSet($title, $desc, $phone_id = 0){
	    
		$title = mysql_real_escape_string($title);
		$desc = mysql_real_escape_string($desc);
		$phone_id = intval($phone_id);

		$sql = "INSERT INTO `sets` (`id`, `title`, `desc`, `phone_id`, `user_id`, `updated`)"
			. " VALUES (NULL, '$title', '$desc', '$phone_id', '" . $this -> userId . "', CURRENT_TIMESTAMP)";
		mysql_unbuffered_query($sql);
		return mysql_insert_id();
	}


/************************************************
End of SETS
************************************************/

	public static function addLogEntry(){

	}

	public static function getLogEntry($id){

		$id = intval($id);

	}

	public static function getLatestLogEntry($userId){

		$userId = intval($userId);

	}


	public static function getLocalTime(){

		$localTime = null;      
		$sql = "SELECT UNIX_TIMESTAMP(NOW()) AS `localtime`";

		if($res = mysql_query($sql)){
		
			$row = mysql_fetch_assoc($res);
			$localTime = $row['localtime'];
		}

		return $localTime;
	}

	public function setLastUpdated(){

		$sql = "UPDATE `settings` SET `last_updated` =  NOW( ) WHERE `user_id` =" . $this -> userId;
		return mysql_unbuffered_query($sql);
	}

	public function getLastUpdated(){

		$lastUpdated = '';

		$sql = "SELECT `last_updated` FROM `settings` WHERE `user_id` =" . $this -> userId;
		if($res = mysql_query($sql)){

			$row = mysql_fetch_assoc($res);
			$lastUpdated = $row['last_updated'];  
		}

		return $lastUpdated;
	}

    }

	function escapeArray($data, $defaults = null){

		if($defaults){

			foreach($defaults as $key => $value){

				$data[$key] = isset($data[$key]) ? mysql_real_escape_string($data[$key]) : $defaults[$key];
			}
		}else{

			foreach($data as $key => $value){

				$data[$key] = mysql_real_escape_string($data[$key]);
			}
		}

		return $data;
	}

	function formatUpdateString($data){
	    
		$updString = "SET ";
		$i = 0;
		foreach($data as $key => $value){

			$i++;
			$updString .= "`$key` = '$value'";

			// it's not the last item
			if($i != count($data)){

				$updString .= ", ";
			}
		}
	  
		return $updString;

	}

	function formatInsertString($data){

		$fields = '';
		$values = '';
		$i = 0;
		foreach($data as $key => $value){

			$i++;
			$fields .= "`$key`";
			$values .= "'$value'";

			// it's not the last item
			if($i != count($data)){

				$fields .= ", ";
				$values .= ", ";
			}
		}
		
		return "(" . $fields . ") VALUES (" . $values . ")";  
	}

?>