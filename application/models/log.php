<?php defined('SYSPATH') or die('No direct script access.');

class Log_Model extends Model {

    public $userId;

    public function __construct($userId){

        parent::__construct(); // assigns database object to $this->db
        $this->userId = $userId;
    }

    public function addReps($data){

        $query = $this->db->insert('log', array(
                                            'session_id' => $data['session_id'],
                                            'sessions_detail_id' => $data['sessions_detail_id'],
                                            'exercise_id' => $data['exercise_id'],
                                            'reps' => $data['reps'],
                                            'weight' => $data['weight'],
                                            'user_id' => $this->userId,
                                            'done' => $data['done']));
        return $query->insert_id();
    }

    // get entries for the reps planned for this exercises in set
    public function getEntryBySession($sessionId, $repsId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('session_id', $sessionId)
                ->where('sessions_detail_id', $repsId)
                ->where('user_id', $this->userId)
                ->from('log')
                ->get();
    }

    // log entries made in session but without predefined reps from set
    public function getFreeEntries($sessionId, $exerciseId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('session_id', $sessionId)
                ->where('sessions_detail_id', 0)
                ->where('exercise_id', $exerciseId)
                ->where('user_id', $this->userId)
                ->from('log')
                ->get();
    }

    // log entries made in session with and without plans from set
    // used for statistics
    public function getSessionEntries($sessionId, $exerciseId){

        return $this->db->select() // selects all fields by default
                ->where('deleted', 0)
                ->where('session_id', $sessionId)
                ->where('exercise_id', $exerciseId)
                ->where('user_id', $this->userId)
                ->from('log')
                ->get();
    }

    public function deleteItem($id){

        return $this->db->update('log', array('deleted' => 1), array('id' => $id, 'user_id' => $this->userId));
    }

    public function updateItem($data, $id){

        return $this->db->update('log', $data, array('id' => $id, 'user_id' => $this->userId));  
    }

    // wrapper for getLogForPeriod - totals
    public function getTotalForPeriod($exerciseId, $startDate, $endDate){

        return $this->getLogForPeriod('total', $exerciseId, $startDate, $endDate);
    }

    // wrapper for getLogForPeriod - max
    public function getMaxForPeriod($exerciseId, $startDate, $endDate){

        return $this->getLogForPeriod('max', $exerciseId, $startDate, $endDate);
    }


    // returns log data prepared (more or less) for the charting
    public function getLogForPeriod($type, $exerciseId, $startDate, $endDate){

        $exercises = new Exercise_Model($this->userId);
        $exercise = $exercises->getItem($exerciseId);

        $startDateParsed = date("Y-m-d",strtotime($startDate));
        $endDateParsed = date("Y-m-d",strtotime($endDate));

        switch($type){
        case 'total':

            // 0 - own weight
            if($exercise[0]->ex_type == 0){
                $sum = "`reps`";
            }elseif($exercise[0]->ex_type == 1){
                $sum = "`reps` * `weight`";
            }

            $selection = "SUM($sum)";
            break;

        case 'max':

            // 0 - own weight
            if($exercise[0]->ex_type == 0){
                $max = "`reps`";
            }elseif($exercise[0]->ex_type == 1){
                $max = "`weight`";
            }

            $selection = "MAX($max)";

            break;
        }



        // own weight
        $result = $this->db->query(
            "SELECT $selection AS `data`, DATE_FORMAT(`done`, '%Y-%m-%d') AS `done_formatted`, 
                    `session_id`, `sessions`.`title` AS `session_title` 
                FROM `log` 
                LEFT JOIN `sessions` ON `sessions`.`id` = `session_id` 
                    WHERE `exercise_id` = '$exerciseId' AND `done` 
                    BETWEEN '$startDateParsed' AND '$endDateParsed' AND `log`.`user_id` = {$this->userId} 
                    GROUP BY `session_id`"
                                );

        $dates = array();
        $currentDay = strtotime($startDate);
        while($currentDay <= strtotime($endDate)){
            $currentDayFormatted = date("Y-m-d", $currentDay);

            $dates[$currentDayFormatted] = array('date' => $currentDayFormatted);

            // look for results with the same day
            // needs optimization in the future

            $datas = array();
            foreach($result as $dataEntry){

                if($dataEntry->done_formatted == $currentDayFormatted){

                    $reps = $this->getSessionEntries($dataEntry->session_id, $exerciseId);
                    $datas[] = array(
                                'data' => $dataEntry->data, 
                                'session' => array('title' => $dataEntry->session_title, 'id' => $dataEntry->session_id),
                                'reps' => $reps->result_array());
                }
            }

            $dates[$currentDayFormatted]['datas'] = $datas;

            $currentDay = strtotime('+1 day', $currentDay);
        }

        return $dates;
    }
}
 
