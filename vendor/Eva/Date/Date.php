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
        return gmdate($format, time() + $timezone * 3600);
    }


    /**
     * Get a certain date in future by input seconds
     *
     * @access public
     * @param int $seconds  seconds int
     * @param string $startDate  datetime string
     * @param string $format date format
     *
     * @return string date format
     */
    public static function getFuture($seconds = 0, $startDay = null, $format = 'Y-m-d', $timezone = 0)
    {
        date_default_timezone_set('UTC');
        if (!$startDay) {
            return gmdate($format, time() + $seconds + $timezone * 3600);
        } else {
            return gmdate($format, strtotime($startDay) + $seconds);
        }    
    }
}
