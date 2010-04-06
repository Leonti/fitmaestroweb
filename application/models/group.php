<?php defined('SYSPATH') or die('No direct script access.');

class Group_Model extends Model {

	public function __construct(){

		parent::__construct(); // assigns database object to $this->db
	}

	public function getAll(){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->from('groups')
			    ->orderby('id', 'ASC')
			    ->get();
	}

	public function getItem($groupId){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('id', $groupId)
			    ->from('groups')
			    ->get();
	}

	public function addItem($data){

		$query = $this->db->insert('groups', $data); 
		return $query->insert_id(); 
	}

	public function updateItem($data, $id){

		return $this->db->update('groups', $data, array('id' => $id));  
	}

	public function deleteItem($id){

		return $this->db->delete('groups', array('id' => $id));
	}
}
 
