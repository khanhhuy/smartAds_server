<?php

use App\Area;
use App\Item;
use Carbon\Carbon;

class SmartPromotionsTest extends ContextAdsTestCase
{
    public function test_basic_example()
    {
        //arrange
        $ads = $this->createBasicPromotion();
        $ads->items()->attach([1]);

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertEquals([$ads->id], $this->extractPromotions($result)->lists('id'));
    }

    public function test_promotions_are_active()
    {
        //arrange
        $expectedIds = [];
        $now = Carbon::create();
        $days = array($now->copy()->subDays(14), $now->copy()->subDays(7), $now->copy(), $now->copy()->addDays(7), $now->copy()->addDays(14));
        for ($from = 0; $from < 5; $from++) {
            for ($to = $from; $to < 5; $to++) {
                $ads = $this->createBasicPromotion([
                    'start_date' => $days[$from]->toDateString(),
                    'end_date' => $days[$to]->toDateString(),
                ]);

                if ($from <= 2 && $to >= 2) {
                    $expectedIds[] = $ads->id;
                }

                $ads->items()->attach([1]);
            }
        }

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    private function setUpPromotionsForStoreTests()
    {
        $targets = [
            ['S_vn_tphcm_lythuongkiet'],//1
            ['S_vn_tphcm_phutho'],//2
            ['S_vn_tphcm_lythuongkiet', 'S_vn_tphcm_phutho'],//3
            ['A_vn_tphcm'],//4
            ['A_vn_bac'],//5
        ];
        for ($i = 0; $i < 5; $i++) {
            $ads = $this->createBasicPromotion([
                'is_whole_system' => false,
            ]);
            $ads->items()->attach([1]);
            foreach ($targets[$i] as $t) {
                $a = Area::find($t);
                if (!empty($a)) {
                    $ads->areas()->attach($t);
                } else {
                    $ads->stores()->attach($t);
                }
            }
        }
    }

    public function test_promotions_appropriate_for_store_1()
    {
        //arrange
        $this->setUpPromotionsForStoreTests();
        $this->major->store_id = 'S_vn_tphcm_lythuongkiet';
        $this->major->save();

        $expectedIds = [1, 3, 4];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_store_2()
    {
        //arrange
        $this->setUpPromotionsForStoreTests();
        $this->major->store_id = 'S_vn_tphcm_phutho';
        $this->major->save();

        $expectedIds = [2, 3, 4];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_store_3()
    {
        //arrange
        $this->setUpPromotionsForStoreTests();
        $this->major->store_id = 'S_vn_tphcm_cuchi';
        $this->major->save();

        $expectedIds = [4];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_store_4()
    {
        //arrange
        $this->setUpPromotionsForStoreTests();
        $this->major->store_id = 'S_vn_trung_danang';
        $this->major->save();

        $expectedIds = [];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_store_5()
    {
        //arrange
        $this->setUpPromotionsForStoreTests();
        $this->major->store_id = 'S_vn_bac_bacgiang';
        $this->major->save();

        $expectedIds = [5];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    private function setUpPromotionsForCustomerWatchinglistTests()
    {
        $items = [
            [1],
            [2],
            [1, 2],
            [],
            [3],
            [1, 2, 3, 4, 5, 6],
        ];

        for ($i = 0; $i < count($items); $i++) {
            $ads = $this->createBasicPromotion();
            foreach ($items[$i] as $itemId) {
                $item = Item::firstOrCreate(['id' => $itemId]);
                $ads->items()->attach($item);
            }
        }
    }

    public function test_promotions_appropriate_for_customer_watchinglist_1()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        $this->customer->watchingList()->sync([1]);

        $expectedIds = [1, 3, 6];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_customer_watchinglist_2()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        $this->customer->watchingList()->sync([2]);

        $expectedIds = [2, 3, 6];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_customer_watchinglist_3()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        $this->customer->watchingList()->sync([3]);

        $expectedIds = [5, 6];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_customer_watchinglist_4()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        $this->customer->watchingList()->sync([]);

        $expectedIds = [];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_customer_watchinglist_5()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        $this->customer->watchingList()->sync([4]);

        $expectedIds = [6];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_customer_watchinglist_6()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        $this->customer->watchingList()->sync([1, 2]);

        $expectedIds = [1, 2, 3, 6];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_customer_watchinglist_7()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        $this->customer->watchingList()->sync([1, 3]);

        $expectedIds = [1, 3, 5, 6];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_promotions_appropriate_for_customer_watchinglist_8()
    {
        //arrange
        $this->setUpPromotionsForCustomerWatchinglistTests();
        Item::create(['id' => 7]);
        $this->customer->watchingList()->sync([1, 2, 3, 4, 5, 6, 7]);

        $expectedIds = [1, 2, 3, 5, 6];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    private function setUpPromotionsForCustomerThresholdTests()
    {
        $thresholds = [
            [0, 0],
            [0.1, 0.1],
            [1, 500],
            [5, 2500],
            [10, 7000],
            [15, 10000],
            [25, 15000],
            [30, 20000],
            [100, 9999999999],
        ];
        foreach ($thresholds as $index => $t) {
            $ads = $this->createBasicPromotion([
                'discount_rate' => $t[0],
                'discount_value' => $t[1],
            ]);
            $ads->items()->attach([1]);
        }
        Config::set('promotion-threshold.aisle_rate', 5);
        Config::set('promotion-threshold.aisle_value', 4000);
        Config::set('promotion-threshold.entrance_rate', 20);
        Config::set('promotion-threshold.entrance_value', 15000);
    }

    public function test_promotions_classification_1()
    {
        //arrange
        $this->setUpPromotionsForCustomerThresholdTests();

        $expectedAisles = [4, 5, 6];
        $expectedEntrances = [7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }


    public function test_promotions_classification_2()
    {
        //arrange
        $this->setUpPromotionsForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 1;
        $this->customer->min_aisle_value = 1000;
        $this->customer->min_entrance_rate = 5;
        $this->customer->min_entrance_value = 5000;
        $this->customer->save();

        $expectedAisles = [3];
        $expectedEntrances = [4, 5, 6, 7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_3()
    {
        //arrange
        $this->setUpPromotionsForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 5;
        $this->customer->min_aisle_value = 4000;
        $this->customer->min_entrance_rate = 15;
        $this->customer->min_entrance_value = 10000;
        $this->customer->save();

        $expectedAisles = [4, 5];
        $expectedEntrances = [6, 7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_4()
    {
        //arrange
        $this->setUpPromotionsForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 15;
        $this->customer->min_aisle_value = 20000;
        $this->customer->min_entrance_rate = 55;
        $this->customer->min_entrance_value = 100000;
        $this->customer->save();

        $expectedAisles = [6, 7, 8];
        $expectedEntrances = [9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_5()
    {
        //arrange
        $this->setUpPromotionsForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 0;
        $this->customer->min_aisle_value = 0;
        $this->customer->min_entrance_rate = 20;
        $this->customer->min_entrance_value = 15000;
        $this->customer->save();

        $expectedAisles = [1, 2, 3, 4, 5, 6];
        $expectedEntrances = [7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_6()
    {
        //arrange
        $this->setUpPromotionsForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 0;
        $this->customer->min_aisle_value = 0;
        $this->customer->min_entrance_rate = 0;
        $this->customer->min_entrance_value = 0;
        $this->customer->save();

        $expectedAisles = [];
        $expectedEntrances = [1, 2, 3, 4, 5, 6, 7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

}
