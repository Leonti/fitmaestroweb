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

    public function getPublicAll(){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('user_id', 0)
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

    // get group using original id of public group
    public function getByPublicId($publicId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('import_id', $publicId)
                ->where('user_id', $this->userId)
                ->from('groups')
                ->get();
    }

    // get public group
    public function getPublicItem($groupId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('id', $groupId)
                ->where('user_id', 0)
                ->from('groups')
                ->get();
    }

	public function addItem($data){

        $data['user_id'] = $this->userId;
		$query = $this->db->insert('groups', $data); 
		return $query->insert_id(); 
	}

    public function addPublicItem($data){

        $data['user_id'] = 0;
        $query = $this->db->insert('groups', $data); 
        return $query->insert_id(); 
    }

	public function updateItem($data, $id){

		return $this->db->update('groups', $data, array('id' => $id, 'user_id' => $this->userId));
	}

	public function deleteItem($id){

		$result = $this->db->update('groups', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));

        // also delete all exercises with this group
        $this->db->update('exercises', array('deleted' => 1), array('group_id' => $id, 'user_id' => $this->userId));
        return $result; 
	}
}
 
