<?php defined('SYSPATH') OR die('No direct access allowed.');

class timeConvert_Core{

    public static function formatDate($timeString = "now", $outputFormat = 'Y-m-d H:i:s', $timeZone = 'UTC'){

        if ($date = new DateTime($timeString)) {
            $date->setTimeZone(new DateTimeZone($timeZone));
            return $date->format($outputFormat);
        }
        return null;
    }

    public static function formatDateFromUTC($timeString = "now", $outputFormat = 'Y-m-d H:i:s', $timeZone = 'UTC'){

        if ($date = new DateTime($timeString, new DateTimeZone("UTC"))) {
            $date->setTimeZone(new DateTimeZone($timeZone));
            return $date->format($outputFormat);
        }
        return null;
    }

    public static function getTimezones(){

        $timeZonesList = array();
        foreach (DateTimeZone::listIdentifiers() as $timeZone){

            $region = explode("/", $timeZone);
            $timeZonesList[$timeZone] = str_replace(array('/','_'), array(' - ',' '), $timeZone);

            $timeZonesList[$timeZone] .= (self::formatDate("now", 'I', $timeZone)) ? ' DST' : '';
            $timeZonesList[$timeZone] .= ' ' . self::formatDate("now", 'P', $timeZone);

        }
        return $timeZonesList;
    }

    public static function getFormat($shortFormat){

        switch($shortFormat){

        case '24':
            return 'Y-m-d H:i';
            break;

        case 'ampm':
            return 'm/d/Y g:i a';
            break;
        }

        return false;
    }

    public static function getUTCTime($timeString = "now", $fromTimeZone = "UTC"){

        if ($date = new DateTime($timeString, new DateTimeZone($fromTimeZone))) {
            $date->setTimeZone(new DateTimeZone("UTC"));
            return $date->format('Y-m-d H:i:s');
        }
        return null;
    }
}