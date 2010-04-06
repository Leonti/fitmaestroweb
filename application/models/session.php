<?php defined('SYSPATH') or die('No direct script access.');

class Session_Model extends Model {

	public function __construct(){

		parent::__construct(); // assigns database object to $this->db
	}

    public function addSession($setId, $title, $notes = ''){
        $query = $this->db->insert('sessions', array('set_id' => $setId,
                                                    'title' => $title,
                                                    'notes' => $notes,
                                                    'set_id' => $setId,
                                                    'started' => new Database_Expression('NOW()')));
        return $query->insert_id();
    }

    public function getItem($id){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('id', $id)
                ->from('sessions')
                ->get();
    }

    public function getAll(){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->from('sessions')
                ->orderby('id', 'ASC')
                ->get();
    }
}
 
