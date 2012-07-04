<?php
namespace Eva\Date;
class Date
{
    /**
     * Get current datetime
     *
     * @access public
     * @param int $timezone  timezone int
     *
     * @return string datetime format
     */
    public static function getNow($timezone = 0, $format = 'Y-m-d H:i:s')
    {
        date_default_timezone_set('UTC');
        return gmdate($format, mktime() + $timezone * 3600);
    }
}
