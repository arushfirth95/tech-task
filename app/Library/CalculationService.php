<?php

namespace App\Library;

interface CalculationService
{
    /**
     * @param $entity
     * @return mixed
     */
    public function calculate($entity);
}
