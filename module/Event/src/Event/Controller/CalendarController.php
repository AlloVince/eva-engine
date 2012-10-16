<?php
namespace Event\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Date\Calendar;


class CalendarController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/blank');
        $startDay = $this->params()->fromQuery('start');
        $calendar = new Calendar(array(
            'startDay' => $startDay,
        ));
        $calendarArray = $calendar->toArray();
        return new ViewModel(array(
            'calendar' => $calendarArray,
            'prevPath' => '/event/calendar/',
            'nextPath' => '/event/calendar/',
            'dayPath' => '/event/calendar/'
        ));
    }
}
