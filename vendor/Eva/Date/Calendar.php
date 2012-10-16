<?php
namespace Eva\Date;

class Calendar
{

    protected $options;

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        //init setting start
        $defautOptions = array(
            'today' => '',
            'startDay' => '',
            'dayRows' => 31,
            'monthly' => true, //get prev and next months
            'weekStartDay' => 0, // 0 => Sun
            'formatDay' => 'd', // d => 01  j => 1
            'formatWeek' => 'D', // D => Sun  l => Sunday
            'formatMonth' => 'F', // F => January  n  => 1  m => 01 M => Jan
            'formatMonthPer' => 'M',
            'formatDate' => 'm/d/Y',
            'formatYear' => 'Y',
        );

        $options = array_merge($defautOptions, $options);
        //weekly start day
        if(!$options['today']){
            //set start day to today
            $options['today'] = gmdate('Y-m-d', time());
        }

        if(!$options['startDay']){
            $options['startDay'] = gmdate('Y-m') . '-01';
        }

        $this->options = $options;
        return $this;
    }

    public function __construct(array $options = array())
    {
        date_default_timezone_set('UTC');
        $this->setOptions($options);
    }

    /**
    * Create a calendar under UTC
    * setting params:
    *
    *
    * @access public
    * @param array $options
    *
    * @return string datetime format
    */
    public function toArray()
    {
        $options = $this->getOptions();

        $timestamp = strtotime($options['startDay']);
        $timestampToday = strtotime($options['today']);
        $startDayInWeekNumber = gmdate('w', $timestamp);

        //set new start day
        $timestampBegin = $timestamp; //for complement days for one week
        $timestampStartDay = $timestamp; //record start day timestamp
        $monthDays = gmdate('t', $timestampStartDay); //how many days in a month with startDay
        if($options['monthly']) {
            $options['dayRows'] = $monthDays;
        }

        $dayRows = $options['dayRows'];
        if($startDayInWeekNumber != $options['weekStartDay']) {
            if($startDayInWeekNumber > $options['weekStartDay']){
                $dayDiff = $startDayInWeekNumber - $options['weekStartDay'];
            } else {
                $dayDiff = 7 + $startDayInWeekNumber - $options['weekStartDay'];
            }
            $timestampBegin = $timestampBegin - $dayDiff * 3600 * 24;
            $dayRows += $dayDiff;
        }
        $weekCount = ceil($dayRows / 7);
        $startDay = gmdate('Y-m-d', $timestampStartDay);
        $endDay = gmdate('Y-m-d', $timestampStartDay + ($options['dayRows'] - 1) * 86400);

        //put days into weeks
        $weeks = array();
        $timestamp = $timestampBegin;
        $currentMonth = gmdate('Ym', $timestampStartDay);
        $currentMonthStart = $currentMonth . '01';
        $currentMonthEnd = $currentMonth . '31';
        $currentMonthName = '';
        for($i = 0; $i < $weekCount; $i++){
            for($j = 0; $j < 7; $j++){
                $date = gmdate('Ymd', $timestamp);

                $weeks[$i][$j] = array(
                    'day' => gmdate($options['formatDay'], $timestamp),
                    'dayNumber' => gmdate('j', $timestamp),  // without 0
                    'date' => gmdate($options['formatDate'], $timestamp),
                    'datedb' => gmdate('Y-m-d', $timestamp),
                    'monthName' => $currentMonthName,
                    'isCurrentMonth' => $date >= $currentMonthStart && $date <= $currentMonthEnd ? true : false,
                    'isAdd' => false,
                );

                if($weeks[$i][$j]['dayNumber'] == 1){
                    $currentMonthName = gmdate($options['formatMonthPer'], $timestamp);
                    $weeks[$i][$j]['monthName'] = $currentMonthName;
                }

                if($weeks[$i][$j]['datedb'] < $startDay || $weeks[$i][$j]['datedb'] > $endDay){
                    $weeks[$i][$j]['isAdd'] = true;
                }

                $timestamp += 86400;
            }
        }
        $finishDay = gmdate('Y-m-d', $timestamp - 86400);

        //get week names
        $weekNames = array();
        for($j = 0; $j < 7; $j++){
            $weekNames[] = gmdate($options['formatWeek'], $timestampBegin + $j * 86400);
        }

        //get month name for startDay
        $monthName =  gmdate($options['formatMonth'], $timestampStartDay);
        $yearName =  gmdate($options['formatYear'], $timestampStartDay);


        //get prev & next
        if($options['monthly']) {
            $currentMonth = gmdate('Y-m-', $timestampStartDay);
            $timestamp = strtotime($currentMonth . '01') - 1;
            $prev = array(
                'startDay' => gmdate('Y-m-', $timestamp) . '01',
                'monthName' => gmdate($options['formatMonth'], $timestamp),
            );
            $timestamp = strtotime($currentMonth . $monthDays) + 86401;
            $next = array(
                'startDay' => gmdate('Y-m-', $timestamp) . '01',
                'monthName' => gmdate($options['formatMonth'], $timestamp),
            );
        } else {
            $prev = array(
                'startDay' => gmdate('Y-m-d', $timestampStartDay - $options['dayRows'] * 86400),
                'monthName' => gmdate($options['formatMonth'], $timestampStartDay - $options['dayRows'] * 86400),
            );
            $next = array(
                'startDay' => gmdate('Y-m-d', $timestampStartDay + $options['dayRows'] * 86400),
                'monthName' => gmdate($options['formatMonth'], $timestampStartDay + $options['dayRows'] * 86400),
            );
        }

        //get today
        $timestamp = time();
        return array(
            'yearName' => $yearName,
            'monthName' => $monthName,
            'today' => array(
                'day' => gmdate($options['formatDay'], $timestampToday),
                'date' => gmdate($options['formatDate'], $timestampToday),
                'datedb' => gmdate('Y-m-d', $timestampToday),
                'startDay' => gmdate('Y-m', $timestampToday) . '-01',
                'isCurrentMonth' => true,
            ),
            'startDay' => $startDay,
            'endDay' => $endDay,
            'beginDay' => gmdate('Y-m-d', $timestampBegin),
            'finishDay' => $finishDay,
            'monthDays' => $monthDays,
            'weekCount' => $weekCount,
            'prev' => $prev,
            'next' => $next,
            'weekNames' => $weekNames,
            'days' => $weeks,
        );
    }
}
