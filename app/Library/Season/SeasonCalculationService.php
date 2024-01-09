<?php

namespace App\Library\Season;

use App\Library\CalculationService;

class SeasonCalculationService implements CalculationService
{
    CONST SEASONS = [
        'spring' => '03-20',
        'summer' => '06-21',
        'autumn' => '09-23',
        'winter' => '12-22',
    ];

    /**
     * @param \DateTime $entity
     * @return string
     * @throws \Exception
     */
    public function calculate($entity)
    {
        if(!($entity instanceof \DateTime)){
            throw new \Exception('Parameter not equal to DateTime');
        }

        $spring_date_obj = new \DateTime($entity->format('Y').'-'.self::SEASONS['spring']);
        $summer_date_obj = new \DateTime($entity->format('Y').'-'.self::SEASONS['summer']);
        $autumn_date_obj = new \DateTime($entity->format('Y').'-'.self::SEASONS['autumn']);
        $winter_date_obj = new \DateTime($entity->format('Y').'-'.self::SEASONS['winter']);

        $season = 'winter';
        if($entity->getTimestamp() > $spring_date_obj->getTimestamp()) $season = 'spring';
        if($entity->getTimestamp() > $summer_date_obj->getTimestamp()) $season = 'summer';
        if($entity->getTimestamp() > $autumn_date_obj->getTimestamp()) $season = 'autumn';
        if($entity->getTimestamp() > $winter_date_obj->getTimestamp()) $season = 'winter';

        return $season;
    }
}
