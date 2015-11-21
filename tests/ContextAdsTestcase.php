<?php

use App\ActiveCustomer;
use App\BeaconMajor;
use App\BeaconMinor;
use Faker\Factory as Faker;

class ContextAdsTestCase extends ApiTestCase
{

    protected $contextAdsService;
    protected $customer;
    protected $major;
    protected $minor;
    protected $fake;

    /**
     * SmartPromotionsTest constructor.
     * @param $contextAdsService
     */
    public function __construct()
    {
        $this->fake = Faker::create();

    }

    public function setUp()
    {
        parent::setUp();
        $this->contextAdsService = $this->app->make('contextAdsService');
        $this->seed('TestingDatabaseSeeder');
        $this->customer = ActiveCustomer::all()->first();
        $this->major = BeaconMajor::find(1);
        $this->minor = BeaconMinor::find(1);
    }


    public function tearDown()
    {
        parent::tearDown();
    }

    public function extractPromotions($result)
    {
        return $result['entrancePromotions']->merge($result['aislePromotions']);
    }
}
