<?php defined('SYSPATH') OR die('No direct access allowed.');

    // if there is no post data - don't bother to exetute script
    if(!isset($_POST['jsonString'])){
        exit();
    }

ini_set('error_log','php-errors.log'); // path to server-writable log file

class Remote_Controller extends Controller {

    public function index(){

    $data = json_decode($_POST['jsonString']);

/*
    $data = new stdClass;
    $data->what = "PUBLICPROGRAMS";
    $data->authkey = "c77feca090963cf3aee40f9de859d0c7";
*/

    // logging
    if($data -> what == "STARTUPDATE"){
    $handle = fopen('log', 'w');
    fwrite($handle, '');
    fclose($handle);
    }
    // end of logging

/*
    if(isset($_POST['jsonString'])){
        error_log(print_r("-----------FROM PHONE-------------" . "\n", true)."\n", 3, "log");
        error_log(print_r(json_decode($_POST['jsonString']), true)."\n", 3, "log");
    } */

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
                        $userId = remoteUser::checkUserByKey($data -> authkey);
                    if($userId){

                        $toSend = json_encode(array("result" => "STARTUPDATED", "data" => prepareUpdate($userId, $data->data, $data->fresh)->tables));
                        echo $toSend;
        error_log(print_r("-----------TO PHONE-------------" . "\n", true)."\n", 3, "log");
        error_log(print_r(json_decode($toSend), true)."\n", 3, "log");
                    }
                break;

                case "UPDATEPHONEIDS":
                    $userId = remoteUser::checkUserByKey($data -> authkey);
                    if($userId){

                        $returnedData = updatePhoneIds($userId, $data->data);

                        // in future add a flag if this is last ids from phone - if so - don't send anything back
                        $toSend = json_encode(array("result" => "PHONEIDSUPDATED", "data" => $returnedData->tables, "new_ids" => $returnedData->new_ids));
                        echo $toSend;
        error_log(print_r("-----------TO PHONE-------------" . "\n", true)."\n", 3, "log");
        error_log(print_r(json_decode($toSend), true)."\n", 3, "log");
                    }
                break;

                case "PUBLICEXERCISES":
                    $userId = remoteUser::checkUserByKey($data -> authkey);
                    if($userId){

                        $toSend = json_encode(array("data" => getPublicExercises($userId)));
                        echo $toSend;
                    }
                break;

                case "IMPORTEXERCISES":
                    $userId = remoteUser::checkUserByKey($data -> authkey);
                    if($userId){

                        $toSend = json_encode(array("result" => importExercises($userId, $data->data)));
                        echo $toSend;
                    }
                break;

                case "PUBLICPROGRAMS":
                    $userId = remoteUser::checkUserByKey($data -> authkey);
                    if($userId){

                        $toSend = json_encode(array("data" => getPublicPrograms($userId)));
                        echo $toSend;
                    }
                break;

                case "IMPORTPROGRAMS":
                    $userId = remoteUser::checkUserByKey($data -> authkey);
                    if($userId){

                        $toSend = json_encode(array("result" => importPrograms($userId, $data->data)));
                        echo $toSend;
                    }
                break;


            }

    }

}

// update database with data from the phone and give the phone entries that are newer back
function prepareUpdate($userId, $phoneData = null, $fresh = 0){

    // order of the tables matter
    // for example groups have to be first so correct id's may be put in exercises table
    $tablesMap = array(
                'groups' =>   array('title', 'desc'),
                'files' => array('filename', 'frames'),
                'exercises' => array('title', 'desc', 'group_id', 'ex_type', 'max_weight', 'max_reps', 'file_id'),
                'sets' =>   array('title', 'desc'),
                'sets_connector' => array('set_id', 'exercise_id'),
                'sets_detail' => array('sets_connector_id', 'reps', 'percentage'),
                'programs' =>   array('title', 'desc'),
                'programs_connector' => array('program_id', 'set_id', 'day_number'),
                'sessions' => array('programs_connector_id', 'title', 'desc', 'status'),
                'sessions_connector' => array('session_id', 'exercise_id'),
                'sessions_detail' => array('sessions_connector_id', 'reps', 'percentage'),
                'log' => array('exercise_id', 'weight', 'reps', 'done', 'session_id', 'sessions_detail_id'),
                'measurement_types' => array('title', 'units', 'desc'),
                'measurements_log' => array('measurement_type_id', 'value', 'date'),
                );


    $convertMap = array(
                'group_id' => 'groups',
                'file_id' => 'files',
                'set_id' => 'sets',
                'exercise_id' => 'exercises',
                'session_id' => 'sessions',
                'sets_detail_id' => 'sets_detail',
                'sessions_detail_id' => 'sessions_detail',
                'program_id' => 'programs',
                'programs_connector_id' => 'programs_connector',
                'sets_connector_id' => 'sets_connector',
                'sessions_connector_id' => 'sessions_connector',
                'measurement_type_id' => 'measurement_types',

                );

    $convertTimeMap = array('done', 'date');

    $exercisesObj = new exercises();
    $exercisesObj -> userId = $userId;
error_log("User id is: " . $userId . "\n", 3, "log");

    $exercisesObj -> convertMap = $convertMap;
    $exercisesObj -> convertTimeMap = $convertTimeMap;
    $lastUpdated = $exercisesObj -> getLastUpdated();

    $secondRound = array();
    $newIds = false;

    foreach($tablesMap as $table => $fields){

        // if it's a fresh install on  the phone - reset all phone id's first for each table
        if($fresh){
            $exercisesObj -> resetPhoneIds($table);
        }

        $updatedItems = $exercisesObj -> getUpdatedItems($table, $lastUpdated);
       // $requestedItems = $exercisesObj -> getRequestedItems($table);
        //$allUpdatedItems = array_merge($updatedItems, $requestedItems);
        error_log(print_r($updatedItems,true));
        //error_log(print_r($requestedItems,true));
       // error_log(print_r($allUpdatedItems,true));

        if($exercisesObj -> getRequestedItemsCount($table) > 0){
            error_log("We have some new requested ids!");
            $newIds = true;
        }

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

            // now we have removed all data from $updatedItems which are newer on the phone
            // in array only left data which is newer on the site and newly added data
            // now check updatedItems for items with unmappable phone id's and remove them too (they will be updated later)
            foreach($updatedItems as $itemId => $updatedItem){
                if(isset($updatedItem['new_id']) && $updatedItem['new_id'] == 1){
                    error_log('removing from updated items');
                    unset($updatedItems[$itemId]);

                    // updating id just to trigger update time so it will show up on the second round
                    // if it works - update field directly
                   // $exercisesObj -> updateItem($table, $itemId, array('id' => $itemId));
                    $secondRound[$table][] = $itemId;
                }
            }
        }


        $data -> $table = jsonArray($updatedItems);
    }

    $exercisesObj -> setLastUpdated();
    $exercisesObj -> clearRequestUpdates();

    foreach($secondRound as $table => $toUpdate){
        foreach($toUpdate as $itemId){

            $exercisesObj -> addRequestUpdate($table, $itemId);
        }
    }

    $returnValue->tables = $data;
    $returnValue->new_ids = $newIds;
    return $returnValue;
}

// we got new stuff from the phone, gave new site stuff back, now it's time to update phone_id's in site's database
function updatePhoneIds($userId, $phoneData){

    $exercisesObj = new exercises();
    $exercisesObj -> userId = $userId;

    $updateTables = array(
                        'groups',
                        'files',
                        'exercises',
                        'sets',
                        'sets_connector',
                        'sets_detail',
                        'programs',
                        'programs_connector',
                        'sessions',
                        'sessions_connector',
                        'sessions_detail',
                        'log',
                        'measurement_types',
                        'measurements_log',

                        );

    foreach($updateTables as $table){

        foreach($phoneData -> $table as $item){

            $exercisesObj -> updateItem($table, $item -> site_id, array('phone_id' => $item -> phone_id));
        }
    }

    return prepareUpdate($userId);
}

function getPublicExercises($user_id){

    $exercises = new Exercise_Model($user_id);
    $groups = new Group_Model($user_id);
    $public_groups = $groups->getPublicAll();

    $public_exercises_array = array();
    foreach($public_groups as $public_group){

        $public_exercises = $exercises->getPublicByGroupId($public_group->id)->result_array();
        foreach($public_exercises as &$public_exercise){
            $test_exercise = $exercises->getByPublicId($public_exercise->id);
            $public_exercise->imported = count($test_exercise);
        }

        $public_exercises_array[] = array(
                                        'id' => $public_group->id,
                                        'title' => $public_group->title,
                                        'desc' => $public_group->desc,
                                        'exercises' => $public_exercises,
        );
    }
    return $public_exercises_array;

}

function importExercises($user_id, $to_import){

    foreach($to_import as $import_exercise_id){
        imports::importExercise($user_id, $import_exercise_id);
    }
    return "SUCCESS";
}

function getPublicPrograms($user_id){

    $programs = new Program_Model($user_id);
    $public_programs = $programs->getPublicPrograms()->result_array();
    foreach($public_programs as &$public_program){
        $testProgram = $programs->getByPublicId($public_program->id);
        $public_program->imported = count($testProgram) > 0 ? 1 : 0;
    }
    return $public_programs;

}

function importPrograms($user_id, $to_import){

    foreach($to_import as $import_program_id){
        imports::importProgram($user_id, $import_program_id);
    }
    return "SUCCESS";
}

function jsonArray($array){

    $noKeys = array();
    foreach($array as $value){
        $noKeys[] = $value;
    }

    return $noKeys;
}

 
 
