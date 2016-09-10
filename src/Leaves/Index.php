<?php

namespace HackTheHub\Leaves;

use HackTheHub\Models\Event\Event;
use Rhubarb\Leaf\Leaves\Leaf;
use Rhubarb\Stem\Filters\AndGroup;
use Rhubarb\Stem\Filters\OneOf;
use Rhubarb\Stem\Filters\OrGroup;

class Index extends Leaf
{
    protected function getViewClass()
    {
        return IndexView::class;
    }

    protected function createModel()
    {
        $model = new IndexModel();

        $model->getEventsEvent->attachHandler(function($selected){
            $events = [];

            $filter = new AndGroup();

            if(!empty($selected)) {
                $filter->addFilters(new OneOf('CategoryID', $selected));
            }

            foreach(Event::find($filter) as $event) {
                $eventClass = new \stdClass();
                $eventClass->Latitude = $event->Latitude;
                $eventClass->Longitude = $event->Longitude;
                $events[] = $eventClass;
            }

            return $events;
        });

        return $model;
    }
}