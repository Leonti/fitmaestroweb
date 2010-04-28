<?php defined('SYSPATH') OR die('No direct access allowed.');

    // if there is no post data - don't bother to exetute script
    if(!isset($_POST['jsonString'])){
        exit();
    }

ini_set('error_log','php-errors.log'); // path to server-writable log file

class Remote_Controller extends Controller {

    public function index(){

        $data = json_decode($_POST['jsonString']);

    // logging
    if($data -> what == "STARTUPDATE"){
    $handle = fopen('log', 'w');
    fwrite($handle, '');
    fclose($handle);
    }
    // end of logging

        error_log(print_r("-----------FROM PHONE-------------" . "\n", true)."\n", 3, "log");
        error_log(print_r($data, true)."\n", 3, "log");
            switch ($data -> what){
                case "REGISTER":
                    echo json_encode(remoteUser::createUser($data -> email, $data -> password));
                break;

                case "LOGIN":
                    echo json_encode(remoteUser::loginUser($data -> email, $data -> password));
                break;


                case "STARTUPDATE":
                    if($userId = remoteUser::checkUserByKey($data -> authkey)){

                        $toSend = json_encode(array("result" => "STARTUPDATED", "data" => prepareUpdate($userId, $data->data)));
                        echo $toSend;
        error_log(print_r("-----------TO PHONE-------------" . "\n", true)."\n", 3, "log");
        error_log(print_r(json_decode($toSend), true)."\n", 3, "log");
                    }
                break;

                case "FINISHUPDATE":
                    if($userId = remoteUser::checkUserByKey($data -> authkey)){

                        $toSend = json_encode(array("result" => "FINISHUPDATED", "data" => finishUpdate($userId, $data->data)));
                        echo $toSend;
        error_log(print_r("-----------TO PHONE-------------" . "\n", true)."\n", 3, "log");
        error_log(print_r(json_decode($toSend), true)."\n", 3, "log");
                    }
                break;


            }

    }

}

// update database with data from the phone and give the phone entries that are newer back
function prepareUpdate($userId, $phoneData = null){

    // order of the tables matter
    // for example groups have to be first so correct id's may be put in exercises table
    $tablesMap = array(
                'groups' =>   array('title', 'desc'),
                'exercises' => array('title', 'desc', 'group_id', 'ex_type', 'max_weight', 'max_reps'),
                'sets' =>   array('title', 'desc'),
                'sets_connector' => array('set_id', 'exercise_id'),
                'sets_detail' => array('sets_connector_id', 'reps', 'percentage'),
                'programs' =>   array('title', 'desc'),
                'programs_connector' => array('program_id', 'set_id', 'day_number'),
                'sessions' => array('programs_connector_id', 'title', 'desc', 'status'),
                'sessions_connector' => array('session_id', 'sets_connector_id', 'exercise_id'),
                'log' => array('exercise_id', 'weight', 'reps', 'done', 'session_id', 'sets_detail_id'),

                );


    $convertMap = array(
                'group_id' => 'groups',
                'set_id' => 'sets',
                'exercise_id' => 'exercises',
                'session_id' => 'sessions',
                'sets_detail_id' => 'sets_detail',
                'program_id' => 'programs',
                'programs_connector_id' => 'programs_connector',
                'sets_connector_id' => 'sets_connector',

                );

    $convertTimeMap = array('done');

    $exercisesObj = new exercises();
    $exercisesObj -> userId = $userId;
error_log("User id is: " . $userId . "\n", 3, "log");

    $exercisesObj -> convertMap = $convertMap;
    $exercisesObj -> convertTimeMap = $convertTimeMap;
    $lastUpdated = $exercisesObj -> getLastUpdated();

    foreach($tablesMap as $table => $fields){

        $updatedItems = $exercisesObj -> getUpdatedItems($table, $lastUpdated);

        // if it's null - just display updated items (with relations)
        if($phoneData != null){

            $localtime = $exercisesObj -> getLocalTime();
            $phonetime = $phoneData -> localtime;
            $diff = $localtime - $phonetime;
            $exercisesObj -> timeDiff = $diff;

            foreach($phoneData -> $table as $phoneItem){

                $updateFields = array('phone_id' => $phoneItem -> id, 'deleted' => $phoneItem -> deleted);
                foreach($fields as $updateField){

                    $updateFields[$updateField] = $phoneItem -> $updateField;
                }

                $updateFields = $exercisesObj -> convertToSite($updateFields);

                // which means the item is already in the db - check whichone is newer
                if($phoneItem -> site_id != 0){

                    $updateAction = false;

                    if(isset($updatedItems[$phoneItem -> site_id])){

                        // if the one on site is older (taking time difference into account)
                        if($updatedItems[$phoneItem -> site_id]['stamp'] < $phoneItem -> stamp + $diff ){

                              // removing item from array
                              unset($updatedItems[$phoneItem -> site_id]);
                              $updateAction = true;
                        }
                    }else{

                        // it's on site but it's so old it's not in array - updating
                        $updateAction = true;
                    }

                    if($updateAction){

                        $exercisesObj -> updateItem($table, $phoneItem -> site_id, $updateFields);
                    }

                }else{ // it's not in db - so adding it

                    if($newId = $exercisesObj -> addItem($table, $updateFields)){

                        $newItem = $exercisesObj -> getItem($table, $newId);
                        $newItem = $exercisesObj -> convertToPhone($newItem);
                        $updatedItems[$newId] = $newItem;
                    }
                }

            }
        }


        $data -> $table = jsonArray($updatedItems);
    }

    $exercisesObj -> setLastUpdated();

    return $data;
}

// we got new stuff from the phone, gave new site stuff back, no it's time to update phone_id's in site's database
function finishUpdate($userId, $phoneData){

    $exercisesObj = new exercises();
    $exercisesObj -> userId = $userId;

    $updateTables = array(
                        'groups',
                        'exercises',
                        'sets',
                        'sets_connector',
                        'sets_detail',
                        'programs',
                        'programs_connector',
                        'sessions',
                        'sessions_connector',
                        'log',

                        );

    foreach($updateTables as $table){

        foreach($phoneData -> $table as $item){

            $exercisesObj -> updateItem($table, $item -> site_id, array('phone_id' => $item -> phone_id));
        }
    }

    return prepareUpdate($userId);
}

function jsonArray($array){

    $noKeys = array();
    foreach($array as $value){
        $noKeys[] = $value;
    }

    return $noKeys;
}

 
 
