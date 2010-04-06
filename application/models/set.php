<?php defined('SYSPATH') or die('No direct script access.');

class Set_Model extends Model {

	public function __construct(){

		parent::__construct(); // assigns database object to $this->db
	}

	public function getAll(){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->from('sets')
			    ->orderby('id', 'ASC')
			    ->get();
	}

	public function getItem($setId){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('id', $setId)
			    ->from('sets')
			    ->get();
	}


//SELECT sets_connector.exercise_id, exercises.title FROM `sets_connector` JOIN exercises on sets_connector.exercise_id = exercises.id WHERE set_id=25
	public function getExercises($setId){

		return $this->db->select
			    (
			    'exercises.id',
			    'exercises.title',
			    'exercises.desc',
                'exercises.max_weight',
			    'exercises.ex_type',
			    'groups.title AS group_title',
			    'sets_connector.id AS connector_id'
			    )
			    ->from('sets_connector')
			    ->join('exercises', array('sets_connector.exercise_id' => 'exercises.id'))
			    ->join('groups', array('exercises.group_id' => 'groups.id'))
			    ->where('exercises.deleted', 0)
			    ->where('sets_connector.deleted', 0)
			    ->where('sets_connector.set_id', $setId)
			    ->orderby('sets_connector.id','ASC')
			    ->get();
	}

	public function addToSet($setId, $exerciseId){

		$query = $this->db->insert('sets_connector', array('set_id' => $setId, 'exercise_id' => $exerciseId)); 
		return $query->insert_id(); 
	}

	public function addItem($data){

		$query = $this->db->insert('sets', $data); 
		return $query->insert_id(); 
	}

	public function updateItem($data, $id){

		return $this->db->update('sets', $data, array('id' => $id));  
	}

	public function deleteItem($id){

		return $this->db->delete('sets', array('id' => $id));
	}

	public function deleteExercise($id){

		return $this->db->update('sets_connector', array('deleted' => 1), array('id' => $id));
	}

	public function getReps($connectorId){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('set_connector_id', $connectorId)
			    ->from('sets_detail')
			    ->get();
	}

	public function addReps($data){

		$query = $this->db->insert('sets_detail', $data); 
		return $query->insert_id();
	}

	public function updateReps($data, $id){

		return $this->db->update('sets_detail', $data, array('id' => $id));
	}

	public function deleteRep($id){

		return $this->db->update('sets_detail', array('deleted' => 1), array('id' => $id));
	}
}
 
