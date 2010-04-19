<?php defined('SYSPATH') or die('No direct script access.');

class Ajaxpost_Controller extends Controller {

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

		echo json_encode(array('preved' => 'medved'));
	}

	public function savegroup(){

		$post = $this->input->post();
		if(isset($post['title']) && isset($post['desc'])){

			$groups = new Group_Model($this->user->id);

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

			$groups = new Group_Model($this->user->id);

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

			$exercises = new Exercise_Model($this->user->id);

			$result = null;
			// existing item
			if($post['id']){

				if($exercises -> updateItem(array('title' => $post['title'], 
								  'desc' => $post['desc'], 
								  'ex_type' => $post['ex_type'],
                                  'max_weight' => floatval($post['max_weight']),
								  'group_id' => $post['group_id']), $post['id'])){

					$result = true;
				}
			}else{

				if($exercises -> addItem(array('title' => $post['title'], 
							    'desc' => $post['desc'],
							    'ex_type' => $post['ex_type'],
                                'max_weight' => floatval($post['max_weight']),
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

			$exercises = new Exercise_Model($this->user->id);

			if($exercises -> deleteItem($post['id'])){

				echo json_encode(array('result' => 'OK'));
			}else{

				echo json_encode(array('result' => 'FAILED'));
			}

		}else{
		      
			echo "No data provided";
		}
	}


    public function saveprogram(){

        $post = $this->input->post();
        if(isset($post['title']) && isset($post['desc'])){

            $programs = new Program_Model($this->user->id);

            $result = null;
            // existing item
            if($post['id']){

                if($programs -> updateItem(array('title' => $post['title'], 'desc' => $post['desc']), $post['id'])){

                    $result = true;
                }
            }else{

                if($programs -> addItem(array('title' => $post['title'], 'desc' => $post['desc']))){

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

    public function deleteprogram(){

        $post = $this->input->post();
        if(isset($post['id'])){

            $programs = new Program_Model($this->user->id);

            if($programs -> deleteItem($post['id'])){

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

			$sets = new Set_Model($this->user->id);
            $setId = null;

			$result = null;
			// existing item
			if($post['id']){

                $setId = $post['id'];
				if($sets -> updateItem(array('title' => $post['title'], 
								  'desc' => $post['desc']), $post['id'])){

					$result = true;
				}
			}else{

				if($setId = $sets -> addItem(array('title' => $post['title'], 
							    'desc' => $post['desc']))){

					$result = true;
				}
			}

            if(isset($post['program_id'])){

                $programs = new Program_Model($this->user->id);
                $programs->addSetToProgram(array(
                                            'program_id' => $post['program_id'],
                                            'day_number' => $post['day_number'],
                                            'set_id' => $setId,
                                            ));
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

    // moves set to another day in program
    public function moveset(){

        $post = $this->input->post();

        if(isset($post['connector_id']) && isset($post['day_number'])){

            $programs = new Program_Model($this->user->id);

            if($programs->moveSet($post['connector_id'], $post['day_number'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{

            echo "No data provided";
        }
    }

    // removes set from program
    public function removefromprogram(){

        $post = $this->input->post();

        if(isset($post['connector_id'])){

            $programs = new Program_Model($this->user->id);

            if($programs->deleteSet($post['connector_id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{

            echo "No data provided";
        }
    }

    public function savesession(){

        $post = $this->input->post();
        if(isset($post['title']) || isset($post['status'])){

            $sessions = new Session_Model($this->user->id);

            $result = null;
            // existing item
            if($post['id']){

                $updateArray = array();

                if(isset($post['status'])){
                    $updateArray['status'] = $post['status'];
                }


                if(isset($post['title'])){
                    $updateArray['title'] = $post['title'];
                    $updateArray['notes'] = $post['notes'];
                }

                if($sessions -> updateItem($updateArray, $post['id'])){

                    $result = true;
                }
            }else{

                if($sessions -> addSession(array(
                                                'title' => $post['title'], 
                                                'notes' => $post['notes'],
                                                ))){

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

			$sets = new Set_Model($this->user->id);

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

			$sets = new Set_Model($this->user->id);

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

			$sets = new Set_Model($this->user->id);

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

			$sets = new Set_Model($this->user->id);
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

					if($reps != ''){

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
        if(isset($post['title'])){

            $sessions = new Session_Model($this->user->id);
            $result = null;
            $programsConnectorId = !empty($post['programs_connector_id']) ? $post['programs_connector_id'] : 0;
            $result = $sessionId = $sessions -> addSession(array(
                                                                'title' => $post['title'],
                                                                'programs_connector_id' => $programsConnectorId,
                                                                ));

            if(isset($post['set_id'])){
                $result = $sessions -> addSetToSession($sessionId, $post['set_id']);
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

    public function sessionaddexercise(){

        $post = $this->input->post();
        if(isset($post['session_id']) && isset($post['exercise_id'])){

            $sessions = new Session_Model($this->user->id);

            if($sessions -> addExerciseToSession($post['session_id'], $post['exercise_id'])){

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

            $sessions = new Session_Model($this->user->id);
            $log = new Log_Model($this->user->id);
            $result = null;

            $i = 0;
            foreach($post['log_id'] as $logId){

                $reps = $post['reps'][$i];
                $weight = $post['weight'][$i];

                // updating existing
                if($logId){

                    // update existing entry
                    if(isset($post['isDone'][$i])){

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
                    if(isset($post['isDone'][$i])){
                        $repsId = !empty($post['rep_id'][$i]) ? $post['rep_id'][$i] : 0;

                        if($reps != '' || $weight != ''){

                            $result = $log -> addReps(array( 
                                            'session_id' => $post['session_id'],
                                            'exercise_id' => $post['exercise_id'],
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

