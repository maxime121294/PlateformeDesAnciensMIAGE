<?php

namespace AppBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Advert;

class CalendarEventListener
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // The original request so you can get filters from the calendar
        // Use the filter in your query for example

        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');


        // load events using your custom logic here,
        // for instance, retrieving events from a repository
        $repository = $this->entityManager->getRepository('AppBundle:Advert');

        $evenements = $repository->getAdvertsByCategory('Événement');
        
        foreach($evenements as $evenement) {
            $id = $evenement->getId();
            // create an event with a start/end time, or an all day event
            $eventEntity = new EventEntity($evenement->getTitle(), $evenement->getEvenementDate(),null, true);
            
            //optional calendar event settings
            $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor('#BB0F47'); //set the background color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $eventEntity->addField('advertUrl', 'annonce/' . $id);
            
            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}