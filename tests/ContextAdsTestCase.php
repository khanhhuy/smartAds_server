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


    public function tearDown()
    {
        parent::tearDown();
    }

    public function extractPromotions($result)
    {
        return $result['entrancePromotions']->merge($result['aislePromotions']);
    }

    public function createBasicPromotion($overwriteFields = [])
    {
        $fields = array_merge([
            'discount_value' => $this->fake->numberBetween(0, 99999999999),
            'discount_rate' => $this->fake->numberBetween(0, 100),
            'is_whole_system' => true,
            'is_promotion' => true,
            'title' => $this->fake->sentence,
            'start_date' => Carbon::now()->subDays(1)->toDateString(),
            'end_date' => Carbon::now()->addDays(1)->toDateString(),
        ], $overwriteFields);

        return Ads::create($fields);
    }
}
