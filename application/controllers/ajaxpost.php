<?php defined('SYSPATH') or die('No direct script access.');
ini_set('error_log','php-errors.log');
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


        public function checkImage($file_name){
            $allowed_extentions = array('jpg', 'jpeg', 'gif', 'png');
            $ext = substr($file_name, strrpos($file_name, '.') + 1);
            if(!in_array($ext, $allowed_extentions)){
                return array('error' => 'ext');
            };

            return array('error' => '', 'extention' => $ext);
        }

        public function loadimage(){
            $uploads_folder = Kohana::config('core.uploads_folder');
            $file_error = intval($_FILES['image']['error']);

            if(!$file_error){
                $file_name = $_FILES['image']['name'];
                $check_result = $this->checkImage($file_name);
                $error_type = $check_result['error'];
                if(!$error_type){
                    $ext = $check_result['extention'];
                    $new_filename = md5(uniqid()) . '.' . $ext;
                    $uploaded_file = $uploads_folder . $new_filename;
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploaded_file);
                    echo json_encode(array('result' => 'OK', 'file' => $new_filename));
                    return;
                }else{
                    echo json_encode(array('result' => 'FAILED', 'error' => $error_type));
                    return;
                }
            }
            
           echo json_encode(array('result' => 'FAILED', 'error' => 'upload error'));
           return;

        }

	public function saveexercise(){

		$post = $this->input->post();
                $uploads_folder = Kohana::config('core.uploads_folder');

		if(isset($post['title']) && isset($post['desc'])){
                   
                    $frames_count = 1;
                    $file_name = $post['file_name'];

                    if($file_name){

                        $full_filename = $uploads_folder . $file_name;
                        // doing second check on provided filename in case someone messed with hidden file_name field
                        $image_check_result = $this->checkImage($full_filename);
                        
                        if(!$image_check_result['error']){

                            $image = new Imagick($full_filename);
                            $frames_count = $image->getNumberImages();

                            // create frame files from animated gif
                            if($frames_count > 1){
                                animationWriteFrames($full_filename, $uploads_folder);
                            }
                        }else{

                            // resseting file_name if it's not valid
                            $file_name = '';
                        }
                    }

			$exercises = new Exercise_Model($this->user->id);
                        $files = new File_Model($this->user->id);

			$result = null;

                        $exerciseData = array('title' => $post['title'],
                                  'desc' => $post['desc'], 
                                  'ex_type' => $post['ex_type'],
                                  'max_weight' => floatval($post['max_weight']),
                                  'max_reps' => intval($post['max_reps']),
                                  'group_id' => $post['group_id']);


                        // existing item
                        if($post['id']){

                                $exercise = $exercises->getItem($post['id']);
                                $file_id = $exercise[0]->file_id;

                                // we have file uploaded
                                if($file_name){

                                    //error_log('File ID is: ' . $file_id);
                                    // it has already uploaded file
                                    if($file_id){
                                        $old_file = $files->getItem($file_id);
                                        $old_filename = $old_file[0]->filename;
                                        $file_user_id = $old_file[0]->user_id;

                                        // this file belongs to user - so we can safely update it
                                        if($file_user_id != 0){
                                            $files->updateItem(array('filename'=>$file_name, 'frames' => $frames_count), $file_id);

                                        // don't touch public item - instead create new file
                                        }else{
                                            $file_id = $files->addItem(array('filename'=>$file_name, 'frames' => $frames_count));
                                        }


                                        //remove old file - supress warnings if deleted
                                        @unlink($uploads_folder . $old_filename);
                                    }else{

                                        // this is new file - adding it to the database
                                        $file_id = $files->addItem(array('filename'=>$file_name, 'frames' => $frames_count));
                                    }
                                }

                                $exerciseData['file_id'] = $file_id;
                                if($exercises -> updateItem($exerciseData, $post['id'])){

                                        $result = true;
                                }
                        }else{
                                if($file_name){
                                    $file_id = $files->addItem(array('filename'=>$file_name, 'frames' => $frames_count));
                                }
                                $exerciseData['file_id'] = $file_id;

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

            if(isset($post['program_id']) && !empty($post['program_id'])){

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
                    $updateArray['desc'] = $post['desc'];
                }

                if($sessions -> updateItem($updateArray, $post['id'])){

                    $result = true;
                }
            }else{

                if($sessions -> addSession(array(
                                                'title' => $post['title'], 
                                                'desc' => $post['desc'],
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

					if($percentage != ''){

						$result = $sets -> addReps(array( 'sets_connector_id' => $post['connector_id'],
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

    public function sessiondeleteexercise(){

        $post = $this->input->post();
        if(isset($post['id'])){

            $sessions = new Session_Model($this->user->id);

            if($sessions -> deleteExercise($post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{
            echo "No data provided";
        }
    }

    public function deletesession(){

        $post = $this->input->post();
        if(isset($post['id'])){

            $sessions = new Session_Model($this->user->id);

            if($sessions -> deleteItem($post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{
            echo "No data provided";
        }
    }

    // adding extra reps before doing planned result in adding first planned reps
    public function savesessionreps(){

        $post = $this->input->post();

        $settings = new Setting_Model($this->user->id);
        $userSettings = $settings->getSettings();

        if(isset($post['log_id'])){

            $sessions = new Session_Model($this->user->id);
            $log = new Log_Model($this->user->id);
            $result = null;

            $i = 0;
            foreach($post['log_id'] as $logId){

                $reps = $post['reps'][$i];
                $weight = $post['weight'][$i];

               // we use UTC time in DB so converting time user has provided to UTC so it is saved right in DB
                $done = timeConvert::getUTCTime($post['done'][$i],  
                                            $userSettings->time_zone);

                // updating existing
                if($logId){

                    // update existing entry
                    if(isset($post['isDone'][$i])){

                        $result = $log->updateItem(array(
                                            'reps' => $reps,
                                            'weight' => $weight,
                                            'done' => $done,
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
                                            'sessions_detail_id' => $repsId,
                                            'reps' => $reps,
                                            'weight' => $weight,
                                            'done' => $done));
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

// measurements start

    public function savemeasurement(){

        $post = $this->input->post();
        if(isset($post['title']) && isset($post['units'])){

            $measurements = new Measurement_Model($this->user->id);

            $result = null;
            // existing item
            if($post['id']){

                if($measurements -> updateType(array(
                                                'title' => $post['title'], 
                                                'units' => $post['units'],
                                                'desc'  => $post['desc'],
                                                ), $post['id'])){

                    $result = true;
                }
            }else{

                if($measurements -> addType(array(
                                                'title' => $post['title'], 
                                                'units' => $post['units'],
                                                'desc'  => $post['desc'],
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


    public function deletemeasurement(){

        $post = $this->input->post();
        if(isset($post['id'])){

            $measurements = new Measurement_Model($this->user->id);

            if($measurements -> deleteType($post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{
            echo "No data provided";
        }
    }
// save-measurement-entry

    public function saveMeasurementEntry(){

        $post = $this->input->post();
        if(isset($post['value']) && isset($post['date'])){
    
            $date = date("Y-m-d H:i:s", strtotime($post['date']));
            $typeId = $post['type_id'];
            $value = floatval($post['value']);

            $measurements = new Measurement_Model($this->user->id);

            $result = null;
            // existing item
            if($post['id']){

                if($measurements -> updateLogEntry(array(
                                                'value' => $value, 
                                                'measurement_type_id' => $typeId,
                                                'date'  => $date,
                                                ), $post['id'])){

                    $result = true;
                }
            }else{

                if($measurements -> addLogEntry(array(
                                                'value' => $value, 
                                                'measurement_type_id' => $typeId,
                                                'date'  => $date,
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

    public function deleteMeasurementEntry(){

        $post = $this->input->post();
        if(isset($post['id'])){

            $measurements = new Measurement_Model($this->user->id);

            if($measurements -> deleteLogEntry($post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{
            echo "No data provided";
        }
    }

// measurements end


    public function importexercises(){

        $post = $this->input->post();
        if(isset($post['current_group_id'])){

            $result = null;

            foreach($post['exercise_id'] as $importExerciseId){

                $noImportGroups = isset($post['noimport_id']) ? $post['noimport_id'] : null;
                $result = imports::importExercise($this->user->id, $importExerciseId, $post['current_group_id'], $noImportGroups);
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

/*
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
*/

    public function exportprogram(){

        $post = $this->input->post();

        if(isset($post['id'])){

            if(exports::exportProgram($this->user->id, $post['id'])){

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

            if(exports::exportExercise($this->user->id, $post['id'])){

                echo json_encode(array('result' => 'OK'));
            }else{

                echo json_encode(array('result' => 'FAILED'));
            }

        }else{

            echo "No data provided";
        }
    }

}

function animationWriteFrames($filename, $destination="")
{
        $format = basename($filename, ".gif") . "-%0d.gif";
        try
        {
                $animation = new Imagick($filename);
                $coalesced = $animation->coalesceImages();

                // total frames
                // $total = $coalesced->getNumberImages();

                foreach ($coalesced as $frame)
                {
                        $index = 1 + $frame->getImageIndex();
                        $tofilename = $destination . sprintf($format, $index);

                        $frame->writeImage($tofilename);
                }
        }
        catch(Exception $e)
        {
                echo $e->GetMessage(), "\n";
                return FALSE;
        }
        return TRUE;
} 
