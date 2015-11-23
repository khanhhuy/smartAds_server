<?php

use App\ActiveCustomer;
use App\Ads;
use App\BeaconMajor;
use App\BeaconMinor;
use Carbon\Carbon;
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

    public function extractPromotions($result)
    {
        return $result['entrancePromotions']->merge($result['aislePromotions']);
    }

    public function createBasicPromotion($overwriteFields = [])
    {
        $fields = array_merge([
            'discount_value' => 99999999999999,
            'discount_rate' => 100,
            'is_whole_system' => true,
            'is_promotion' => true,
            'title' => $this->fake->sentence,
            'start_date' => Carbon::now()->subDays(1)->toDateString(),
            'end_date' => Carbon::now()->addDays(1)->toDateString(),
        ], $overwriteFields);

        return Ads::create($fields);
    }

    public function mockConnectorReturnForItem1()
    {
        $fakeCatReturn = (object)['id' => '1115193_1071967_1149379']; //Laundry Detergents
        Connector::shouldReceive('getCategoryFromItemID')->with(1)->andReturn($fakeCatReturn);
    }

    public function setDefaultThresholds()
    {
        Config::set('promotion-threshold.aisle_rate', 5);
        Config::set('promotion-threshold.aisle_value', 4000);
        Config::set('promotion-threshold.entrance_rate', 20);
        Config::set('promotion-threshold.entrance_value', 15000);
    }
}
