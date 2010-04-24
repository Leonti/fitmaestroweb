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

            $exerciseData = array('title' => $post['title'], 
                                  'desc' => $post['desc'], 
                                  'ex_type' => $post['ex_type'],
                                  'max_weight' => floatval($post['max_weight']),
                                  'max_reps' => intval($post['max_reps']),
                                  'group_id' => $post['group_id']);

			// existing item
			if($post['id']){

				if($exercises -> updateItem($exerciseData, $post['id'])){

					$result = true;
				}
			}else{

				if($exercises -> addItem($exerciseData)){

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

    public function importexercises(){

        $post = $this->input->post();
        if(isset($post['current_group_id'])){

            $result = null;

            foreach($post['exercise_id'] as $importExerciseId){

                $noImportGroups = isset($post['noimport_id']) ? $post['noimport_id'] : null;
                $result = importExercise($this->user->id, $importExerciseId, $post['current_group_id'], $noImportGroups);
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

    public function importprogram(){

        $post = $this->input->post();

        if(isset($post['id'])){

            if(importProgram($this->user->id, $post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{

            echo "No data provided";
        }
    }

    public function exportprogram(){

        $post = $this->input->post();

        if(isset($post['id'])){

            if(exportProgram($this->user->id, $post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{

            echo "No data provided";
        }
    }

    public function exportexercise(){

        $post = $this->input->post();

        if(isset($post['id'])){

            if(exportExercise($this->user->id, $post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{

            echo "No data provided";
        }
    }

}

function importExercise($userId, $importExerciseId, $defaultGroupId = null, $noImportGroups = null){

    $exercises = new Exercise_Model($userId);
    $groups = new Group_Model($userId);

    // get public exercise from db
    $importExercise = $exercises->getPublicItem($importExerciseId);

    // if $groupId is not specified - look if exercise group is already imported or
    // import it if it's not

    // test if this group is already imported
    $testGroup = $groups->getByPublicId($importExercise[0]->group_id);

    if(count($testGroup) > 0 &&
        !(isset($noImportGroups) 
        && in_array($importExercise[0]->group_id, $noImportGroups))){
                $groupId = $testGroup[0]->id;
    }else{

        // it's not in the system - 2 choices:
        // 1. import group
        // 2. add exercise to current group

        // if user doesn't want to import group we use current group id
        if(isset($noImportGroups) && in_array($importExercise[0]->group_id, $noImportGroups)){
            $groupId = $defaultGroupId;
        }else{
            $importGroup = $groups->getPublicItem($importExercise[0]->group_id);
            $importGroupData = $importGroup[0];

            $groupId = $groups->addItem(array(
                                            'title' => $importGroupData->title,
                                            'desc' => $importGroupData->desc,
                                            'import_id' => $importGroupData->id,
                                            ));
        }
    }

    // now we have groupId - lets add exercise itself
    $importedExerciseId = null;

    // check if this exercise is already imported
    $testExercise = $exercises->getByPublicId($importExercise[0]->id);

    if(count($testExercise) > 0){
        $importedExerciseId = $testExercise[0]->id;
    }else{

        $importedExerciseId = $exercises->addItem(array(
                                                    'title' => $importExercise[0]->title,
                                                    'desc' => $importExercise[0]->desc,
                                                    'ex_type' => $importExercise[0]->ex_type,
                                                    'group_id' => $groupId,
                                                    'import_id' =>$importExercise[0]->id,
                                                    ));
    }

    return $importedExerciseId;
}

// importing program
// 1. program itself should be imported
// 2. programs_connector
// 3. sets
// 4. sets_connector
// 5. exercises + groups
// oh my
function importProgram($userId, $importProgramId){

    $programs = new Program_Model($userId);
    $sets = new Set_Model($userId);

    $importProgram = $programs->getPublicItem($importProgramId);

    // 1. importing program itself 
    $importedProgramId = $programs->addItem(array(
                                        'title' => $importProgram[0]->title,
                                        'desc' => $importProgram[0]->desc,
                                        'import_id' => $importProgram[0]->id,
                                        ));

    // importing sets
    foreach($programs->getPublicSets($importProgramId) as $importSet){

        $programs->addSetToProgram(array(
                                        'program_id' => $importedProgramId,
                                        'set_id' => importSet($userId, $importSet->set_id),
                                        'day_number' => $importSet->day_number,
                                         ));
    }

    return $importedProgramId;
}

function importSet($userId, $importSetId){

    $sets = new Set_Model($userId);

    $importSet = $sets->getPublicItem($importSetId);

    $importedSetId = $sets->addItem(array(
                                'title' => $importSet[0]->title,
                                'desc' => $importSet[0]->desc,
                                'import_id' => $importSet[0]->id,
                                ));

    // now lets add exercises
    foreach($sets->getPublicExercises($importSet[0]->id) as $importExercise){

        $importedExerciseId = importExercise($userId, $importExercise->id);
        $sets->addToSet($importedSetId, $importedExerciseId);
    }

    return $importedSetId;
}

function exportExercise($userId, $exportExerciseId){
    $exercises = new Exercise_Model($userId);
    $groups = new Group_Model($userId);

    $exportGroupId = 0;
    $result = null;

    // get exercise from db
    $exportExercise = $exercises->getItem($exportExerciseId);

    // test if this group is already exported
    $exportGroup = $groups->getItem($exportExercise[0]->group_id);

    // there is an entry of this group in public groups
    if($exportGroup[0]->import_id != 0){
        $exportGroupId = $exportGroup[0]->import_id;
    }else{

        // we need to export current group
        $exportGroupId = $groups->addPublicItem(array(
                                                    'title' => $exportGroup[0]->title,
                                                    'desc' => $exportGroup[0]->desc,
                                                    ));
        // make this id an import_id in internal group
        $groups->updateItem(array('import_id' => $exportGroupId), $exportGroup[0]->id);
    }

    $exportedExerciseId = null;

    // check if it was exported before
    if($exportExercise[0]->import_id != 0){
        $exportedExerciseId = $exportExercise[0]->import_id;
    }else{
        // exporting exercise itself
        $exportedExerciseId = $exercises->addPublicItem(array(
                                                    'title' => $exportExercise[0]->title,
                                                    'desc' => $exportExercise[0]->desc,
                                                    'ex_type' => $exportExercise[0]->ex_type,
                                                    'group_id' => $exportGroupId,
                                                    ));

        // make this id an import_id in internal exercise
        $exercises->updateItem(array('import_id' => $exportedExerciseId), $exportExercise[0]->id);
    }

    return $exportedExerciseId;
}


function exportProgram($userId, $exportProgramId){

    $programs = new Program_Model($userId);

    $exportProgram = $programs->getItem($exportProgramId);

    $exportedProgramId = $programs->addPublicItem(array(
                                                        'title' => $exportProgram[0]->title,
                                                        'desc' => $exportProgram[0]->desc,
                                                        ));
    // updating internal program to contain exported id
    $programs->updateItem(array('import_id' => $exportedProgramId), $exportProgram[0]->id);

    foreach($programs->getSets($exportProgram[0]->id) as $exportSet){

        $programs->addPublicSetToProgram(array(
                                'program_id' => $exportedProgramId,
                                'set_id' => exportSet($userId, $exportSet->set_id),
                                'day_number' => $exportSet->day_number,
                                    ));
    }

    return $exportedProgramId;

}

function exportSet($userId, $exportSetId){

    $sets = new Set_Model($userId);

    $exportSet = $sets->getItem($exportSetId);

    $exportedSetId = $sets->addPublicItem(array(
                                'title' => $exportSet[0]->title,
                                'desc' => $exportSet[0]->desc,
                                ));

    // make this id an import_id in internal set
    $sets->updateItem(array('import_id' => $exportedSetId), $exportSet[0]->id);

    // now lets add exercises
    foreach($sets->getExercises($exportSet[0]->id) as $exportExercise){

        $exportedExerciseId = exportExercise($userId, $exportExercise->id);
        $sets->addPublicToSet($exportedSetId, $exportedExerciseId);
    }

    return $exportedSetId;
}


