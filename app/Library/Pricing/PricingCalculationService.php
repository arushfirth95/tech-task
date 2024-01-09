<?php

namespace App\Library\Pricing;

use App\Library\CalculationService;
use App\Library\Season\SeasonCalculationService;
use App\Library\Traits\WeekDayTypeFinder;

class PricingCalculationService implements CalculationService
{

    use WeekDayTypeFinder;

    /**
     * @var SeasonCalculationService
     */
    private $seasonCalculationService;
    /**
     * @var array
     */
    const PRICING = [
        'winter' => 10,
        'summer' => 15,
        'spring' => 5,
        'autumn' => 0,
        'weekday' => 10,
        'weekend' => 5
    ];

    /**
     * @param SeasonCalculationService $seasonCalculationService
     */
    public function __construct(SeasonCalculationService $seasonCalculationService)
    {
        $this->seasonCalculationService = $seasonCalculationService;
    }

    /**
     * @param \DateTime $entity
     * @return int
     * @throws \Exception
     */
    public function calculate($entity)
    {
        $season = $this->seasonCalculationService->calculate($entity);
        $week_day = $this->findWeekDay($entity);
        // Default pricing for unknown seasons or week days
        $price = 0;

        // Check if the season is known and increment the price
        if (isset(self::PRICING[$season])) {
            $price += self::PRICING[$season];
        }

        // Check if the week day is known and increment the price
        if (isset(self::PRICING[$week_day])) {
            $price += self::PRICING[$week_day];
        }
        return $price;
    }
}
