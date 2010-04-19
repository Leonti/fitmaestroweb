<?php defined('SYSPATH') or die('No direct script access.');

class Group_Model extends Model {

    public $userId;

	public function __construct($userId){

		parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId; 
	}

	public function getAll(){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
                ->where('user_id', $this->userId)
			    ->from('groups')
			    ->orderby('id', 'ASC')
			    ->get();
	}

	public function getItem($groupId){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('id', $groupId)
                ->where('user_id', $this->userId)
			    ->from('groups')
			    ->get();
	}

	public function addItem($data){

        $data['user_id'] = $this->userId;
		$query = $this->db->insert('groups', $data); 
		return $query->insert_id(); 
	}

	public function updateItem($data, $id){

		return $this->db->update('groups', $data, array('id' => $id, 'user_id' => $this->userId));
	}

	public function deleteItem($id){

		return $this->db->delete('groups', array('id' => $id, 'user_id' => $this->userId));
	}
}
 
