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

    // returns log data prepared for the charting
    public function getLogForPeriod($typeId, $startDate, $endDate){

        $startDateParsed = date("Y-m-d",strtotime($startDate));
        $endDateParsed = date("Y-m-d",strtotime($endDate));


        // own weight
        $result = $this->db->query(
            "SELECT *, DATE_FORMAT(`date`, '%Y-%m-%d') AS `date_formatted` 
                FROM `measurements_log` 
                    WHERE `measurement_type_id` = '$typeId' AND `date` 
                    BETWEEN '$startDateParsed' AND '$endDateParsed' AND `measurements_log`.`user_id` = {$this->userId}"
                                );

        $dates = array();
        $labels_string_days = '';
        $labels_array_months = array();
        $labels_string_months = '';
        $labels_months_positions = '';
        $values_array = array();
        $values_string = '';
        $day_number = 0;

        $currentDay = strtotime($startDate);
        while($currentDay <= strtotime($endDate)){

            $labels_string_days .= date("j", $currentDay) . '|' ;
            $current_month = date("M", $currentDay);
            if(($month_size = count($labels_array_months)) == 0 ||
                $labels_array_months[$month_size - 1]['name'] != $current_month){
                $labels_array_months[] = array('name' => $current_month, 'position' => $day_number);
            }

            $currentDayFormatted = date("Y-m-d", $currentDay);
            $dates[$currentDayFormatted] = array('date' => $currentDayFormatted);

            // look for results with the same day
            // needs optimization in the future

            $data = array();
            foreach($result as $dataEntry){

                if($dataEntry->date_formatted == $currentDayFormatted){
                    $data[] = $dataEntry;
                }
            }

            // we have an entry for this day
            if(count($data) > 0){
                $values_array[] = $data[0]->value;
            }else{
                $values_array[] = '0';
            }

            $dates[$currentDayFormatted]['data'] = $data;

            $currentDay = strtotime('+1 day', $currentDay);
            $day_number++;
        }

        foreach($labels_array_months as $month_name){
            $labels_string_months .= $month_name['name'] . '|';
            $labels_months_positions .= ceil($month_name['position']/$day_number*100) . ',';
        }

        $max_value = max($values_array);
        if(!$max_value){
            $max_value = 100;
        }
        foreach($values_array as $value){
            $values_string .= ceil($value/$max_value*100) . ",";
        }
        return array(
                    'dates' => $result, 
                    'labels_string_days' => substr($labels_string_days, 0, -1), 
                    'labels_string_months' => substr($labels_string_months, 0, -1),
                    'labels_months_positions' => substr($labels_months_positions, 0, -1),  
                    'values_string' => substr($values_string, 0, -1),
                    'max_value' => $max_value,
                    );
    }

}