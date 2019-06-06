<?php

class DatetimeFactory{

    /**
     * @var $lastError string Last error that occured in creating a date time helper
     */
    public static $lastError;

    /**
     * @param $datestring string Date and/or time string
     * @return bool|DateTimeHelper returns DateTimeHelper object on success or false on failure
     * @throws Exception
     */
    public static function createDateTimeHelper($datestring){
        $datetimehelper = new DateTimeHelper($datestring);
        //If the datetime given was valid, $datetimehelper will be an object. If not, the datestring was invalid so we
        //will return false
        if ($datetimehelper instanceof DateTimeHelper){
            return $datetimehelper;
        } else {
            self::$lastError='Datetime format given was invalid. Could not create a proper DateTimeHelper object';
            return false;
        }
    }

    /**
     * @return string Returns the last error stored in $lastError
     */
    public static function getLastError(){
        return self::$lastError;
    }
}
