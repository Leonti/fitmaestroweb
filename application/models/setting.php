<?php defined('SYSPATH') or die('No direct script access.');

class Setting_Model extends Model {

    public $userId;

    public function __construct($userId){

        parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId;
    }

    public function getSettings(){

        $result =   $this->db->select() // selects all fields by default
                    ->where('user_id', $this->userId)
                    ->from('settings')
                    ->get();

        return $result[0];
    }

    public function saveSettings($data){

        return $this->db->update('settings', $data, array('user_id' => $this->userId));
    }

    public function updateItem($data, $id){

        return $this->db->update('settings', $data, array('id' => $id, 'user_id' => $this->userId));
    }

    public function addItem($data){

        $data['user_id'] = $this->userId;
        $query = $this->db->insert('settings', $data);
        return $query->insert_id();
    }
}
 
