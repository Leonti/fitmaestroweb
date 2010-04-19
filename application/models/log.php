<?php defined('SYSPATH') or die('No direct script access.');

class Log_Model extends Model {

    public $userId;

    public function __construct($userId){

        parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId;
    }

    public function addReps($data){

        $query = $this->db->insert('log', array(
                                            'session_id' => $data['session_id'],
                                            'sets_detail_id' => $data['sets_detail_id'],
                                            'exercise_id' => $data['exercise_id'],
                                            'reps' => $data['reps'],
                                            'weight' => $data['weight'],
                                            'user_id' => $this->userId,
                                            'done' => new Database_Expression('NOW()')));
        return $query->insert_id();
    }

    // get entries for the reps planned for this exercises in set
    public function getEntryBySession($sessionId, $repsId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('session_id', $sessionId)
                ->where('sets_detail_id', $repsId)
                ->where('user_id', $this->userId)
                ->from('log')
                ->get();
    }

    // log entries made in session but without predefined reps from set
    public function getFreeEntries($sessionId, $exerciseId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('session_id', $sessionId)
                ->where('sets_detail_id', 0)
                ->where('exercise_id', $exerciseId)
                ->where('user_id', $this->userId)
                ->from('log')
                ->get();
    }

    public function deleteItem($id){

        return $this->db->update('log', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));
    }

    public function updateItem($data, $id){

        return $this->db->update('log', $data, array('id' => $id, 'user_id' => $this->userId));  
    }
}
 
