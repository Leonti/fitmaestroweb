<?php defined('SYSPATH') or die('No direct script access.');

class Measurement_Model extends Model {

    public $userId;

    public function __construct($userId){

        parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId; 
    }

    public function addType($data){

        $data['user_id'] = $this->userId;
        $query = $this->db->insert('measurement_types', $data); 
        return $query->insert_id(); 
    }

    public function updateType($data, $id){

        return $this->db->update('measurement_types', $data, array('id' => $id, 'user_id' => $this->userId));
    }

    public function deleteType($id){

        $result = $this->db->update('measurement_types', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));

        // also delete all exercises with this group
        $this->db->update('measurements_log', array('deleted' => 1), array('measurement_type_id' => $id, 'user_id' => $this->userId));
        return $result;
    }

    public function getTypes(){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('user_id', $this->userId)
                ->from('measurement_types')
                ->get();
    }

    public function getType($id){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('id', $id)
                ->where('user_id', $this->userId)
                ->from('measurement_types')
                ->get();
    }

    public function addLogEntry($data){

        $data['user_id'] = $this->userId;
        $query = $this->db->insert('measurements_log', $data); 
        return $query->insert_id(); 
    }

    public function updateLogEntry($data, $id){

        return $this->db->update('measurements_log', $data, array('id' => $id, 'user_id' => $this->userId));
    }

    public function deleteLogEntry($id){

        return $this->db->update('measurements_log', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));
    }

    public function getLogEntries($typeId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('user_id', $this->userId)
                ->where('measurement_type_id', $typeId)
                ->from('measurements_log')
                ->orderby('date', 'DESC')
                ->get();
    }

}