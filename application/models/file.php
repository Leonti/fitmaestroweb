<?php defined('SYSPATH') or die('No direct script access.');

class File_Model extends Model {

    public $userId;

	public function __construct($userId){

		parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId;
	}




	public function getItem($id){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('id', $id)
                            //->where('user_id', $this->userId)
			    ->from('files')
			    ->get();
	}

	public function addItem($data){

        $data['user_id'] = $this->userId;
		$query = $this->db->insert('files', $data);
		return $query->insert_id();
	}


	public function updateItem($data, $id){

		return $this->db->update('files', $data, array('id' => $id, 'user_id' => $this->userId));
	}

	public function deleteItem($id){

		return $this->db->update('files', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));
	}
}


