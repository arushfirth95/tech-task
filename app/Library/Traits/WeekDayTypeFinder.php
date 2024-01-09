<?php

namespace App\Library\Traits;

trait WeekDayTypeFinder
{
    /**
     * @param \DateTime $date_obj
     * @return string
     */
    public function findWeekDay(\DateTime $date_obj): string
    {
        //If is 1-5 is weekday else weekend
        $dayOfWeek = (int)$date_obj->format('N');
        return ($dayOfWeek >= 6) ? 'weekend' : 'weekday';
    }
}
