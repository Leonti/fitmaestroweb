<?php defined('SYSPATH') or die('No direct script access.');

class Exercise_Model extends Model {

    public $userId;

	public function __construct($userId){

		parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId; 
	}

	public function getAll(){

		return $this->db->select
			    (
			    'exercises.title AS exercise_title',
			    'exercises.desc',
			    'exercises.ex_type',
			    'groups.title AS group_title',
			    'exercises.id',
			    'groups.id AS group_id'
			    )
			    ->from('exercises')
			    ->join('groups', array('exercises.group_id' => 'groups.id'))
			    ->where('exercises.deleted', 0)
                ->where('exercises.user_id', $this->userId)
			    ->orderby('exercises.id','ASC')
			    ->get();
	}

	public function getByGroupId($groupId){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('group_id', $groupId)
                ->where('user_id', $this->userId)
			    ->from('exercises')
			    ->orderby('id','ASC')
			    ->get();
	}

	public function getItem($id){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('id', $id)
                ->where('user_id', $this->userId)
			    ->from('exercises')
			    ->get();
	}

	public function addItem($data){

        $data['user_id'] = $this->userId;
		$query = $this->db->insert('exercises', $data); 
		return $query->insert_id(); 
	}

	public function updateItem($data, $id){

		return $this->db->update('exercises', $data, array('id' => $id, 'user_id' => $this->userId));  
	}

	public function deleteItem($id){

		return $this->db->delete('exercises', array('id' => $id, 'user_id' => $this->userId));
	}
}
 
