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
        $dayOfWeek = (int)$date_obj->format('N');
        return ($dayOfWeek >= 6) ? 'weekend' : 'weekday';
    }
}
