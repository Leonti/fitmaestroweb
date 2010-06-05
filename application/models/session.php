<?php defined('SYSPATH') or die('No direct script access.');

class Session_Model extends Model {

    public $userId;

    public function __construct($userId){

        parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId;
    }

    public function addSession($data){

        $data['user_id'] = $this->userId;
        $query = $this->db->insert('sessions', $data);
        return $query->insert_id();
    }

    public function updateItem($data, $id){

        return $this->db->update('sessions', $data, array('id' => $id, 'user_id' => $this->userId));  
    }

    public function addSetToSession($sessionId, $setId){
        $sets = new Set_Model($this->userId);
        $setExercises = $sets->getExercises($setId);

        foreach($setExercises as $setExercise){

            $sessionsConnector = $this->addExerciseToSession($sessionId, $setExercise->id);

            $exerciseReps = $sets->getReps($setExercise->connector_id);
            foreach($exerciseReps as $exerciseRep){

                
                $this->addReps(array(
                                    'sessions_connector_id' => $sessionsConnector,
                                    'reps' => $exerciseRep->reps,
                                    'percentage' => $exerciseRep->percentage,
                                    ));
            }

        }
        return true;
    }

    public function addExerciseToSession($sessionId, $exerciseId){

        $query = $this->db->insert('sessions_connector', array(
                                                            'session_id' => $sessionId, 
                                                            'exercise_id' => $exerciseId,
                                                            'user_id' => $this->userId,
                                                            ));

        return $query->insert_id(); 
    }

    public function deleteExercise($id){

        return $this->db->update('sessions_connector', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));
    }

    public function getReps($connectorId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('sessions_connector_id', $connectorId)
                ->where('user_id', $this->userId)
                ->from('sessions_detail')
                ->get();
    }

    public function addReps($data){

        $data['user_id'] = $this->userId;
        $query = $this->db->insert('sessions_detail', $data); 
        return $query->insert_id();
    }

    public function getExercises($sessionId){

        return $this->db->select
                (
                'exercises.id',
                'exercises.title',
                'exercises.desc',
                'exercises.max_weight',
                'exercises.ex_type',
                'groups.title AS group_title',
                'sessions_connector.id AS sessions_connector_id'
                )
                ->from('sessions_connector')
                ->join('exercises', array('sessions_connector.exercise_id' => 'exercises.id'))
                ->join('groups', array('exercises.group_id' => 'groups.id'))
                ->where('exercises.deleted', 0)
                ->where('sessions_connector.deleted', 0)
                ->where('sessions_connector.session_id', $sessionId)
                ->where('sessions_connector.user_id', $this->userId)
                ->orderby('sessions_connector.id','ASC')
                ->get();
    }

    public function getItem($id){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('id', $id)
                ->where('user_id', $this->userId)
                ->from('sessions')
                ->get();
    }

    public function getByProgramConnector($connectorId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('programs_connector_id', $connectorId)
                ->where('user_id', $this->userId)
                ->from('sessions')
                ->get();
    }

    public function getAll(){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('user_id', $this->userId)
                ->from('sessions')
                ->orderby('id', 'ASC')
                ->get();
    }

    public function getFiltered($filters = null){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('user_id', $this->userId)
                ->where($filters)
                ->from('sessions')
                ->orderby('id', 'ASC')
                ->get();
    }

    public function deleteItem($id){

        // first remove all connector references
        $this->db->update('sessions_connector', array('deleted' => 1), array('session_id' => $id, 'user_id' => $this->userId));
        return $this->db->update('sessions', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));
    }

}
 
