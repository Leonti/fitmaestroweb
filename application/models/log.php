<?php defined('SYSPATH') or die('No direct script access.');

class Log_Model extends Model {

	public function __construct(){

		parent::__construct(); // assigns database object to $this->db
	}

    public function addReps($data){

        $query = $this->db->insert('log', array(
                                            'session_id' => $data['session_id'],
                                            'sets_detail_id' => $data['sets_detail_id'],
                                            'reps' => $data['reps'],
                                            'weight' => $data['weight'],
                                            'done' => new Database_Expression('NOW()')));
        return $query->insert_id();
    }

    public function getEntryBySession($sessionId, $repsId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('session_id', $sessionId)
                ->where('sets_detail_id', $repsId)
                ->from('log')
                ->get();
    }

    public function deleteItem($id){

        return $this->db->update('log', array('deleted' => 1), array('id' => $id));
    }

    public function updateItem($data, $id){

        return $this->db->update('log', $data, array('id' => $id));  
    }
}
 
