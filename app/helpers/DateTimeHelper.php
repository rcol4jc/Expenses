<?php


class DateTimeHelper extends DateTime
{
    /**
     * @date_display_format string Stores date in mm/dd/yyyy format
     * @date_db_format string store date in yyyy-mm-dd format to use in the database
     */
    private $date_display_format;
    private $date_db_format;

    /**
     * DateTimeHelper constructor.
     * @param string $time
     * @param DateTimeZone|null $timezone
     * @throws Exception
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->date_display_format=$this->format('m/d/Y');
        $this->date_db_format=$this->format('Y-m-d');
    }

    /**
     * @return string Returns private date_display_format
     */
    public function getDisplayFormat(){
        return $this->date_display_format;
    }

    /**
     * @return string Returns private date_db_format
     */
    public function getDbFormat(){
        return $this->date_db_format;
    }


}