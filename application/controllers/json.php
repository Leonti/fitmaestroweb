<?php defined('SYSPATH') or die('No direct script access.');

class Json_Controller extends Controller {

    function __construct(){
        parent::__construct();

        $authentic = new Auth;
        if (!$authentic->logged_in()){
            echo json_encode(array('error' => 'UNAUTHORIZED'));
        }else{
            $this->user = $authentic->get_user(); //now you have access to user information stored in the database
        }
    }

	public function index(){

		$get = $this->input->get();
		print_r($get);
//		echo json_encode(array('preved' => 'medved'));
	}

	public function groups(){

		$groups = new Group_Model($this->user->id);
		echo json_encode(getArray($groups->getAll()));
	}

	public function groupinfo(){

		$get = $this->input->get();
		$groups = new Group_Model($this->user->id);
		echo json_encode(getArray($groups->getItem($get['id'])));
	}

	public function exercises(){

		$exercises = new Exercise_Model($this->user->id);
		echo json_encode(getArray($exercises->getAll()));
	}

	public function exercisesbygroup(){

		$get = $this->input->get();
		$exercises = new Exercise_Model($this->user->id);
		echo json_encode(getArray($exercises->getByGroupId($get['id'])));
	}

	public function exerciseinfo(){

		$get = $this->input->get();
		$exercises = new Exercise_Model($this->user->id);
                $files = new File_Model($this->user->id);
                $exercise = $exercises->getItem($get['id']);
                $exercise_array = $exercise->result_array();

                $file_id = $exercise[0]->file_id;
                if($file_id != 0){
                    $file_info = $files->getItem($file_id);
                    $exercise_array[0]->filename = $file_info[0]->filename;
                }
                
		echo json_encode($exercise_array);
	}

    public function programs(){

        $programs = new Program_Model($this->user->id);
        echo json_encode($programs->getAll()->result_array());
    }

    public function programinfo(){

        $get = $this->input->get();
        $programs = new Program_Model($this->user->id);
        echo json_encode($programs->getItem($get['id'])->result_array());
    }

    public function programsets(){

        $get = $this->input->get();
        echo json_encode(getProgramSets($get['id'], $this->user->id));
    }

	public function sets(){

		$sets = new Set_Model($this->user->id);
		echo json_encode($sets->getFreeSets()->result_array());
	}

	public function setexercises(){

		$get = $this->input->get();
		echo json_encode(getSetExercises($get['id'], $this->user->id));
	}

    public function sessionexercises(){

        $get = $this->input->get();
        echo json_encode(getSessionExercises($get['id'], $this->user->id));
    }

	public function setinfo(){

		$get = $this->input->get();
		$sets = new Set_Model($this->user->id);
		echo json_encode($sets->getItem($get['id'])->result_array());
	}

    public function sessioninfo(){

        $get = $this->input->get();
        $sessions = new Session_Model($this->user->id);
        echo json_encode($sessions->getItem($get['id'])->result_array());
    }

	public function reps(){

		$get = $this->input->get();
		$sets = new Set_Model($this->user->id);
		echo json_encode($sets->getReps($get['id'])->result_array());
	}

    public function sessions(){

        $get = $this->input->get();
        $sessions = new Session_Model($this->user->id);
        $filters = array();
        if(!empty($get['status'])){
            $filters['status'] = $get['status'];
        }
        echo json_encode(getArray($sessions->getFiltered($filters)));
    }

    public function measurement_types(){

        $measurements = new Measurement_Model($this->user->id);
        echo json_encode($measurements->getTypes()->result_array());
    }

    public function measurementinfo(){

        $get = $this->input->get();
        $measurements = new Measurement_Model($this->user->id);
        echo json_encode($measurements->getType($get['id'])->result_array());
    }

    public function getdatetime(){

        $get = $this->input->get();

        $settings = new Setting_Model($this->user->id);
        $userSettings = $settings->getSettings();

        $formattedTime = '';

        if(!empty($get['time'])){
            $formattedTime = date(timeConvert::getFormat($userSettings->time_format), 
                                strtotime($get['time'] . " + {$get['diff']} minutes"));
        }else{
            $formattedTime = timeConvert::formatDate("now", 
                                            timeConvert::getFormat($userSettings->time_format), 
                                            $userSettings->time_zone);
        }



        echo json_encode(array('formatted_time' => $formattedTime));
    }

    public function statistics($type = null){
        $type = $type ? $type : 'weight_log';

        $logs = new Log_Model($this->user->id);
        $startDate = $_GET['startdate'];
        $endDate = $_GET['enddate'];

        switch($type){
        case 'measurements_log':

            $measurement_type_id = intval($_GET['measurement_type_id']);
            $measurements = new Measurement_Model($this->user->id);
            $logData = $measurements->getLogForPeriod($measurement_type_id, $startDate, $endDate);
            $measurement_type = $measurements->getType($measurement_type_id);

            $chart_url = "http://chart.apis.google.com/chart?cht=bvs&chco=4d89f9&chbh=a&chs=775x250";
            $chart_url  .= "&chd=t:" . $logData['values_string']
                        .= "&chxt=x,x,y&chxl=0:|" . $logData['labels_string_days']
                        .= "|1:|" . $logData['labels_string_months']
                        .= "&chxp=1," . $logData['labels_months_positions']
                        .= "&chxr=2,0," . $logData['max_value'];

            echo json_encode(array(
                        'data' => array(
                                    'stats' => $logData['dates']->result_array(),
                                    'units' => $measurement_type[0]->units,
                                    ),
                        'chart_url' => $chart_url,
                            ));
            break;

        case 'exercise_log':

            $exercise_id = intval($_GET['id']);
            $exercises = new Exercise_Model($this->user->id);
            $exercise = $exercises->getItem($exercise_id);
            $exercise_array = $exercise->result_array();

            // now we have to choose which type
            // of exercise log:
            // max - max weight for the exercise (with weight type)
            //       max reps for exercises with own weight
            // total - weight*repetitions for the whole day (with weight)
            //       - total repetitions for the whole day (own weight)

            $logData = $logs->getLogForPeriod($_GET['type'], $exercise_id, $startDate, $endDate);

            $chart_url = "http://chart.apis.google.com/chart?cht=bvs&chco=4d89f9&chbh=a&chs=775x250";
            $chart_url  .= "&chd=t:" . $logData['values_string']
                        .= "&chxt=x,x,y&chxl=0:|" . $logData['labels_string_days']
                        .= "|1:|" . $logData['labels_string_months']
                        .= "&chxp=1," . $logData['labels_months_positions']
                        .= "&chxr=2,0," . $logData['max_value'];

            echo json_encode(array(
                                'stats' => $logData['dates'],
                                'exercise' => $exercise_array[0],
                                'chart_url' => $chart_url,
                                ));

            break;
        }
    }
}

function getArray($data){

	$jsonArray = array();

	foreach($data as $row){
		$jsonArray[] = $row;
	}
	return $jsonArray;
}

function getSetExercises($setId, $userId){
    $sets = new Set_Model($userId);
    $exercises = $sets->getExercises($setId)->result_array();

    foreach($exercises as &$exercise){
        $exercise->details = $sets->getReps($exercise->connector_id)->result_array();
    }
    return $exercises;
}

function getSessionExercises($sessionId, $userId){
    $sets = new Set_Model($userId);
    $sessions = new Session_Model($userId);
    $exercises = $sessions->getExercises($sessionId)->result_array();

    // we need settings to convert saved time to right time zone and time format
    $settings = new Setting_Model($userId);
    $userSettings = $settings->getSettings();

    foreach($exercises as &$exercise){
        $exercise->details = $sessions->getReps($exercise->sessions_connector_id)->result_array();

        // we need to fetch exercises actually done for this session

            $log = new Log_Model($userId);
            $sessionDone = array();
            foreach($exercise->details as &$detail){

                // $detail->id - sets_detail_id in log table
                $logData = $log->getEntryBySession($sessionId, $detail -> id)->result_array();
                if(count($logData) > 0){

                    $logData[0]->done =  timeConvert::formatDateFromUTC($logData[0]->done, 
                                            timeConvert::getFormat($userSettings->time_format), 
                                            $userSettings->time_zone);
                    $detail->log_data = $logData[0];
                }
            }

        // adding free(without reps planned) log entries
        $freeEntries = $log->getFreeEntries($sessionId, $exercise->id);
        foreach ($freeEntries as $freeEntry){

            // format time for user selected timezone
            $freeEntry->done =  timeConvert::formatDateFromUTC($freeEntry->done, 
                                            timeConvert::getFormat($userSettings->time_format), 
                                            $userSettings->time_zone);

            $exercise->details[] = array('id' => 0, 'log_data' => $freeEntry);
        }
    }
    return $exercises;
}

function getProgramSets($programId, $userId){
    $programs = new Program_Model($userId);
    $sets = $programs->getSets($programId)->result_array();

    $sessions = new Session_Model($userId);

    // adding session data to sets
    foreach($sets as &$set){

        $sessionsArray = $sessions->getByProgramConnector($set->id)->result_array();
        $sessionData = isset($sessionsArray[0]) ? $sessionsArray[0] : '';
        $set->session = $sessionData;
    }

    return $sets;
}