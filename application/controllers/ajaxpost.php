<?php defined('SYSPATH') or die('No direct script access.');

class Ajaxpost_Controller extends Controller {

	public function index(){

		echo json_encode(array('preved' => 'medved'));
	}

	public function savegroup(){

		$post = $this->input->post();
		if(isset($post['title']) && isset($post['desc'])){

			$groups = new Group_Model();

			$result = null;
			// existing item
			if($post['id']){

				if($groups -> updateItem(array('title' => $post['title'], 'desc' => $post['desc']), $post['id'])){

					$result = true;
				}
			}else{

				if($groups -> addItem(array('title' => $post['title'], 'desc' => $post['desc']))){

					$result = true;
				}
			}

			if($result){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}
			//do adding stuff
		}else{
		      
			echo "No data provided";
		}
	}

	public function deletegroup(){

		$post = $this->input->post();
		if(isset($post['id'])){

			$groups = new Group_Model();

			if($groups -> deleteItem($post['id'])){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}

		}else{
		      
			echo "No data provided";
		}
	}

	public function saveexercise(){

		$post = $this->input->post();
		if(isset($post['title']) && isset($post['desc'])){

			$exercises = new Exercise_Model();

			$result = null;
			// existing item
			if($post['id']){

				if($exercises -> updateItem(array('title' => $post['title'], 
								  'desc' => $post['desc'], 
								  'ex_type' => $post['ex_type'],
								  'group_id' => $post['group_id']), $post['id'])){

					$result = true;
				}
			}else{

				if($exercises -> addItem(array('title' => $post['title'], 
							    'desc' => $post['desc'],
							    'ex_type' => $post['ex_type'],
							    'group_id' => $post['group_id']))){

					$result = true;
				}
			}

			if($result){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}
			//do adding stuff
		}else{
		      
			echo "No data provided";
		}
	}

	public function deleteexercise(){

		$post = $this->input->post();
		if(isset($post['id'])){

			$exercises = new Exercise_Model();

			if($exercises -> deleteItem($post['id'])){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}

		}else{
		      
			echo "No data provided";
		}
	}

	public function saveset(){

		$post = $this->input->post();
		if(isset($post['title']) && isset($post['desc'])){

			$sets = new Set_Model();

			$result = null;
			// existing item
			if($post['id']){

				if($sets -> updateItem(array('title' => $post['title'], 
								  'desc' => $post['desc']), $post['id'])){

					$result = true;
				}
			}else{

				if($sets -> addItem(array('title' => $post['title'], 
							    'desc' => $post['desc']))){

					$result = true;
				}
			}

			if($result){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}
			//do adding stuff
		}else{
		      
			echo "No data provided";
		}
	}

	public function setaddexercise(){

		$post = $this->input->post();
		if(isset($post['set_id']) && isset($post['exercise_id'])){

			$sets = new Set_Model();

			if($sets -> addToSet($post['set_id'], $post['exercise_id'])){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}

		}else{
		      
			echo "No data provided";
		}
	}

	public function setdeleteexercise(){

		$post = $this->input->post();
		if(isset($post['id'])){

			$sets = new Set_Model();

			if($sets -> deleteExercise($post['id'])){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}

		}else{
		      
			echo "No data provided";
		}
	}

	public function deleteset(){

		$post = $this->input->post();
		if(isset($post['id'])){

			$sets = new Set_Model();

			if($sets -> deleteItem($post['id'])){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}

		}else{
		      
			echo "No data provided";
		}
	}

	public function savereps(){

		$post = $this->input->post();

		if(isset($post['rep_id'])){

			$sets = new Set_Model();
			$result = null;

            // get array of reps we already have for this exercise
			$reps = $sets -> getReps($post['connector_id']);

			$toDelete = array();
			foreach($reps as $rep){
	    
				$toDelete[$rep->id] = 1;
			}

			$i = 0;
			foreach($post['rep_id'] as $rep_id){

				// updating existing
				if($rep_id){

					unset($toDelete[$rep_id]);  
					$result = $sets -> updateReps(array('reps' => $post['reps'][$i],
									    'percentage' => $post['percentage'][$i]), $rep_id);
				
				}else{

					$reps = $post['reps'][$i];
					$percentage = $post['percentage'][$i];

					if($reps != '' || $percentage != ''){

						$result = $sets -> addReps(array( 'set_connector_id' => $post['connector_id'],
										  'reps' => $reps,
										  'percentage' => $percentage));
					}
				}
				$i++;
			}

			foreach($toDelete as $delId => $nonImportant){

				$result = $sets -> deleteRep($delId);
			}

			if($result){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}
			//do adding stuff
		}else{
		      
			echo "No data provided";
		}
	}

    public function addsession(){

        $post = $this->input->post();
        if(isset($post['set_id']) && isset($post['title'])){

            $sessions = new Session_Model();

            if($sessions -> addSession($post['set_id'], $post['title'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{

            echo "No data provided";
        }
    }

    public function savesessionreps(){

        $post = $this->input->post();

        if(isset($post['log_id'])){

            $sessions = new Session_Model();
            $log = new Log_Model();
            $result = null;

            $i = 0;
            foreach($post['log_id'] as $logId){

                $reps = $post['reps'][$i];
                $weight = $post['weight'][$i];

                // updating existing
                if($logId){

                    // update existing entry
                    if(isset($post['done'][$i])){

                        $result = $log->updateItem(array(
                                            'reps' => $reps,
                                            'weight' => $weight,
                                              ), $logId);
                    }else{

                        // checkbox is unset - delete item
                        $result = $log->deleteItem($logId);
                    }

                }else{
                    // check if checkbox is checked
                    if(isset($post['done'][$i])){
                        $repsId = !empty($post['rep_id'][$i]) ? $post['rep_id'][$i] : 0;

                        if($reps != '' || $weight != ''){

                            $result = $log -> addReps(array( 
                                            'session_id' => $post['session_id'],
                                            'sets_detail_id' => $repsId,
                                            'reps' => $reps,
                                            'weight' => $weight));
                        }
                    }
              }

                $i++;
            }

            if($result){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }
        }else{

            echo "No data provided";
        }

    }
}

