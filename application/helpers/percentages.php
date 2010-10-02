<?php defined('SYSPATH') OR die('No direct access allowed.');

class percentages_Core {

    public static function calculateReps($percentage, $max_reps){

        return $percentage != 0 ? round($percentage*$max_reps/100) : 0;
    }

    public static function calculateWeight($percentage, $max_weight, $multiplicator){
        $calculatedValue = $percentage != 0 ? $percentage*$max_weight/100 : 0;
        $steppedNumber = $multiplicator != 0 ? round($calculatedValue / $multiplicator) * $multiplicator
				: round($calculatedValue);
        return round($steppedNumber, 2);
    }
}
?>
