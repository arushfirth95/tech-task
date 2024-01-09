<?php

namespace Tests\Unit\Season;

use App\Library\Season\SeasonCalculationService;
use Illuminate\Container\Container;
use Tests\TestCase;

class SeasonCalculationServiceTest extends TestCase
{
    protected $seasonCalculationService;
    protected $winterDateObj;
    protected $springDateObj;
    protected $summerDateObj;
    protected $autumnDateObj;
    protected function setUp(): void
    {
        parent::setUp();
        $c = Container::getInstance();
        $this->seasonCalculationService = $c->make(SeasonCalculationService::class);

        $date_obj_now = new \DateTime();
        $year = $date_obj_now->format('Y');

        $this->winterDateObj = new \DateTime($year.'-01-05');
        $this->springDateObj = new \DateTime($year.'-03-25');
        $this->summerDateObj = new \DateTime($year.'-06-25');
        $this->autumnDateObj = new \DateTime($year.'-09-25');
    }

    public function testCalculateSpring(){
        $season = $this->seasonCalculationService->calculate($this->springDateObj);
        $this->assertEquals('spring',$season);
    }
    public function testCalculateWinter(){
        $season = $this->seasonCalculationService->calculate($this->winterDateObj);
        $this->assertEquals('winter',$season);
    }
    public function testCalculateAutumn(){
        $season = $this->seasonCalculationService->calculate($this->autumnDateObj);
        $this->assertEquals('autumn',$season);
    }
    public function testCalculateSummer(){
        $season = $this->seasonCalculationService->calculate($this->summerDateObj);
        $this->assertEquals('summer',$season);
    }
    public function testExceptionIsThrown(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Parameter not equal to DateTime');

        $season = $this->seasonCalculationService->calculate('gggg');
    }
}
