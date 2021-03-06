<?php defined('SYSPATH') or die('No direct script access.');

class Program_Model extends Model {

    public $userId;

    public function __construct($userId){

        parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId;
    }

	public function getAll(){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
                ->where('user_id', $this->userId)
			    ->from('programs')
			    ->orderby('id', 'ASC')
			    ->get();
	}

    public function getPublicPrograms(){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('user_id', 0)
                ->from('programs')
                ->orderby('id', 'ASC')
                ->get();
    }

    public function getByPublicId($public_id){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('import_id', $public_id)
                ->where('user_id', $this->userId)
                ->from('programs')
                ->get();
    }

	public function getItem($programId){

		return $this->db->select() // selects all fields by default
			    ->where('deleted', 0)
			    ->where('id', $programId)
                ->where('user_id', $this->userId)
			    ->from('programs')
			    ->get();
	}

    // get public program
    public function getPublicItem($programId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('id', $programId)
                ->where('user_id', 0)
                ->from('programs')
                ->get();
    }


	public function addSetToProgram($data){

        $data['user_id'] = $this->userId;
		$query = $this->db->insert('programs_connector', $data);
		return $query->insert_id();
	}

    // add public set to public program
    public function addPublicSetToProgram($data){

        $data['user_id'] = 0;
        $query = $this->db->insert('programs_connector', $data);
        return $query->insert_id();
    }

    public function getSets($id){
        return $this->db->select
                (
                'programs_connector.*',
                'sets.title'
                )
                ->where('programs_connector.deleted', 0)
                ->where('program_id', $id)
                ->where('programs_connector.user_id', $this->userId)
                ->from('programs_connector')
                ->join('sets', array('programs_connector.set_id' => 'sets.id'))
                ->orderby('day_number', 'ASC')
                ->get();
    }

    // get sets for public program
    public function getPublicSets($id){
        return $this->db->select
                (
                'programs_connector.*',
                'sets.title'
                )
                ->where('programs_connector.deleted', 0)
                ->where('program_id', $id)
                ->where('programs_connector.user_id', 0)
                ->from('programs_connector')
                ->join('sets', array('programs_connector.set_id' => 'sets.id'))
                ->orderby('day_number', 'ASC')
                ->get();
    }

	public function addItem($data){

        $data['user_id'] = $this->userId;
		$query = $this->db->insert('programs', $data);
		return $query->insert_id(); 
	}

    public function addPublicItem($data){

        $data['user_id'] = 0;
        $query = $this->db->insert('programs', $data);
        return $query->insert_id(); 
    }

	public function updateItem($data, $id){

		return $this->db->update('programs', $data, array('id' => $id, 'user_id' => $this->userId));
	}

	public function deleteItem($id){

        $result = $this->db->update('programs', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));

        // delete all sets of this program using programs_connector_id
        foreach($this->getSets($id) as $set){

            $this->deleteSet($set->id);
        }
		return $result;
	}


    // deletes programs_connector reference and set itself
	public function deleteSet($id){

        // first get data about set we are deleting
        $connectorData = $this->db->select() // selects all fields by default
                            ->where('deleted', 0)
                            ->where('id', $id)
                            ->where('user_id', $this->userId)
                            ->from('programs_connector')
                            ->get();

        $sets = new Set_Model($this->userId);

        // delete set from sets
        $sets->deleteItem($connectorData[0]->set_id);

		return $this->db->update('programs_connector', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));
	}

    // moves set to another day
    public function moveSet($id, $dayNumber){

        return $this->db->update('programs_connector', array('day_number' => $dayNumber), array('id' => $id, 'user_id' => $this->userId));
    }
}
 
