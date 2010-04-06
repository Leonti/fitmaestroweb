<?php defined('SYSPATH') or die('No direct script access.');

class Json_Controller extends Controller {

	public function index(){

		$get = $this->input->get();
		print_r($get);
//		echo json_encode(array('preved' => 'medved'));
	}

	public function groups(){

		$groups = new Group_Model();
		echo json_encode(getArray($groups->getAll()));
	}

	public function groupinfo(){

		$get = $this->input->get();
		$groups = new Group_Model();
		echo json_encode(getArray($groups->getItem($get['id'])));
	}

	public function exercises(){

		$exercises = new Exercise_Model();
		echo json_encode(getArray($exercises->getAll()));
	}

	public function exercisesbygroup(){

		$get = $this->input->get();
		$exercises = new Exercise_Model();
		echo json_encode(getArray($exercises->getByGroupId($get['id'])));
	}

	public function exerciseinfo(){

		$get = $this->input->get();
		$exercises = new Exercise_Model();
		echo json_encode(getArray($exercises->getItem($get['id'])));
	}

	public function sets(){

		$sets = new Set_Model();
		echo json_encode(getArray($sets->getAll()));
	}

	public function setexercises(){

		$get = $this->input->get();
		echo json_encode(getSetExercises($get['id']));
	}

    public function sessionexercises(){

        $get = $this->input->get();
        $sessionId = $get['id'];
        $sessions = new Session_Model();
        $session = $sessions->getItem($sessionId);
        echo json_encode(getSetExercises($session[0]->set_id, $sessionId));
    }

	public function setinfo(){

		$get = $this->input->get();
		$sets = new Set_Model();
		echo json_encode(getArray($sets->getItem($get['id'])));
	}

	public function reps(){

		$get = $this->input->get();
		$sets = new Set_Model();
		echo json_encode(getArray($sets->getReps($get['id'])));
	}

    public function sessions(){

        $sessions = new Session_Model();
        echo json_encode(getArray($sessions->getAll()));
    }
}

function getArray($data){

	$jsonArray = array();

	foreach($data as $row){
		$jsonArray[] = $row;
	}
	return $jsonArray;
}

function getSetExercises($setId, $sessionId = null){
    $sets = new Set_Model();
    $exercises = getArray($sets->getExercises($setId));

    foreach($exercises as &$exercise){
        $exercise->details = getArray($sets->getReps($exercise->connector_id));

        // we need to fetch exercises actually done for this session
        if($sessionId){

            $log = new Log_Model();
            $sessionDone = array();
            foreach($exercise->details as &$detail){

                // $detail->id - sets_detail_id in log table
                $logData = getArray($log->getEntryBySession($sessionId, $detail -> id));
                if(count($logData) > 0){
                    $detail->log_data = $logData[0];
                }
            }
        }
    }
    return $exercises;
} 
