<?php
namespace Event\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Date\Calendar;
use Eva\Api;


class CalendarController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/blank');
        $startDay = $this->params()->fromQuery('start');
        
        $itemModel = Api::_()->getModel('Event\Model\Calendar');
        $calendarArray = $itemModel->getEventCalendar(array(
            'startDay' => $startDay,
        ));

        return new ViewModel(array(
            'calendar' => $calendarArray,
            'prevPath' => '/event/calendar/',
            'nextPath' => '/event/calendar/',
            'dayPath' => '/event/calendar/'
        ));
    }
}
