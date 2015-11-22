<?php

use App\Ads;
use App\Area;
use App\BeaconMajor;
use App\BeaconMinor;
use App\Category;
use App\Item;
use App\Store;
use Carbon\Carbon;

class SmartPromotionsTest extends ContextAdsTestCase
{
    public function test_simple_example()
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

    public function test_complex_example()
    {
        //arrange
        //---------------------------------------------------------------------------
        //items
        Item::updateOrCreate(['id' => 1]);//Tide Downy 4.5kg
        Item::create(['id' => '2']);//Colgate 150g
        Item::create(['id' => '3']);//DOWNY nang mai
        Item::create(['id' => '4']);//Pepsi 1.5L
        Item::create(['id' => '5']);//ARIEL DOWNY
        Item::create(['id' => '6']);//Downy 1 lan xa
        Item::create(['id' => '7']);//Omo Matic

        //customer
        $this->customer->watchingList()->detach();
        $this->customer->watchingList()->attach(['1', '2', '3', '4', '5']);

        //ads
        Ads::create(['id' => 1, 'title' => 'Bột giặt Tide Downy 4.5kg Giảm giá 20%', 'end_date' => Carbon::now()->addYears(2)->toDateString(),
            'discount_value' => '32500',
            'is_whole_system' => true,
            'discount_rate' => '20'])->items()->attach(1);

        Ads::create(['id' => 2, 'title' => 'Bột giặt ARIEL DOWNY 4.1kg Giảm giá 20%',
            'end_date' => Carbon::now()->subYears(1)->toDateString(),
            'discount_value' => '35500',
            'is_whole_system' => true,
            'discount_rate' => '20'])->items()->attach(5);

        Ads::create(['id' => 3, 'title' => 'Kem đánh răng COLGATE the mát bạc hà 150g Giảm giá 19%',
            'is_whole_system' => false, 'end_date' => Carbon::now()->subYears(1)->toDateString(),
            'discount_value' => '3800',
            'discount_rate' => '19'])->items()->attach(2);

        Ads::create(['id' => 4, 'title' => 'Nước xả DOWNY nắng mai túi 1.8L Giảm giá 18%',
            'is_whole_system' => false, 'end_date' => Carbon::now()->addYears(2)->toDateString(),
            'discount_value' => '17200',//17200
            'discount_rate' => '18',//18
        ])->items()->attach(3);

        Ads::create(['id' => 5, 'title' => 'Nước ngọt Pepsi 1.5L Giảm giá 12%',
            'is_whole_system' => false, 'end_date' => Carbon::now()->addYears(2)->toDateString(),
            'discount_value' => '1900',
            'discount_rate' => '12',
        ])->items()->attach(4);

        Ads::create(['id' => 6, 'title' => 'Nước giặt Omo Matic Font Load 2.7kg Giảm giá 10%',
            'is_whole_system' => false, 'end_date' => Carbon::now()->addYears(2)->toDateString(),
            'discount_value' => '17500',
            'discount_rate' => '10',
        ])->items()->attach(7);

        Ads::create(['id' => 7, 'title' => 'Nước xả DOWNY 1 lần xả túi 1.6L Giảm giá 18%',
            'is_whole_system' => false,
            'start_date' => Carbon::now()->addYears(1)->toDateString(),
            'end_date' => Carbon::now()->addYears(2)->toDateString(),
            'discount_value' => '17500',
            'discount_rate' => '18',
        ])->items()->attach(6);

        Store::find('S_vn_tphcm_binhtan')->ads()->attach(3);
        Store::find('S_vn_dongnam_binhduong')->ads()->attach(5);
        Area::find('A_vn_tphcm')->ads()->attach([4, 6]);
        Area::find('A_vn')->ads()->attach([5, 7]);

        //beacon
        $minor1 = BeaconMinor::updateOrCreate(['minor' => '1']);
        $minor2 = BeaconMinor::create(['minor' => '2']);
        $minor3 = BeaconMinor::create(['minor' => '3']);
        $m1 = BeaconMajor::updateOrCreate(['major' => '1']);
        $m1->store()->associate(Store::find('S_vn_tphcm_binhtan'))->save();
        BeaconMajor::create(['major' => '2'])->store()->associate(Store::find('S_vn_tphcm_binhtrieu'))->save();
        BeaconMajor::create(['major' => '3'])->store()->associate(Store::find('S_vn_dongnam_binhduong'))->save();

        $catFabricSofteners = Category::find('1115193_1071967_1149392');
        $catLaundryDetergents = Category::find('1115193_1071967_1149379');
        $catToothpaste = Category::find('1085666_1007221_1023020');
        $catSoftDrinks = Category::find('976759_976782_1001680');
        $minor1->categories()->attach($catFabricSofteners);
        $minor2->categories()->attach($catLaundryDetergents);
        $minor3->categories()->attach([$catSoftDrinks->id, $catToothpaste->id]);

        $catOfItems = [
            '1' => $catLaundryDetergents->id,
            '2' => $catToothpaste->id,
            '3' => $catFabricSofteners->id,
            '4' => $catSoftDrinks->id,
            '5' => $catLaundryDetergents->id,
            '6' => $catFabricSofteners->id,
            '7' => $catLaundryDetergents->id
        ];
        Connector::shouldReceive('getCategoryFromItemID')->with(anyOf(['1', '2', '3', '4', '5', '6', '7']))->andReturnUsing(
            function ($itemId) use ($catOfItems) {
                $catId = @$catOfItems[$itemId];
                if ($catId !== null) {
                    return (object)['id' => $catId];
                } else {
                    return null;
                }
            });


        $expectedEntrances = [1, 4];
        $expectedAisles = [5];
        $expectedAislesMinors = [3];

        //act
        //---------------------------------------------------------------------------
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        //---------------------------------------------------------------------------
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedAislesMinors, $aislePromotions[0]->minors);
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
            ['A_vn'] //6
        ];
        for ($i = 0; $i < count($targets); $i++) {
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

        $expectedIds = [1, 3, 4, 6];

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

        $expectedIds = [2, 3, 4, 6];

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

        $expectedIds = [4, 6];

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

        $expectedIds = [6];

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

        $expectedIds = [5, 6];

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

    private function setUpForCustomerThresholdTests()
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
        $this->mockConnectorReturnForItem1();
        $this->setDefaultThresholds();
    }

    public function test_promotions_classification_1()
    {
        //arrange
        $this->setUpForCustomerThresholdTests();

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
        $this->setUpForCustomerThresholdTests();
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
        $this->setUpForCustomerThresholdTests();
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
        $this->setUpForCustomerThresholdTests();
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
        $this->setUpForCustomerThresholdTests();
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
        $this->setUpForCustomerThresholdTests();
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

    public function test_promotions_classification_7()
    {
        //arrange
        $this->setUpForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 0;
        $this->customer->min_aisle_value = 0;
        $this->customer->min_entrance_rate = 100;
        $this->customer->min_entrance_value = 99999999;
        $this->customer->save();

        $expectedAisles = [1, 2, 3, 4, 5, 6, 7, 8];
        $expectedEntrances = [9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_8()
    {
        //arrange
        $this->setUpForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 100;
        $this->customer->min_aisle_value = 99999999;
        $this->customer->min_entrance_rate = 100;
        $this->customer->min_entrance_value = 99999999;
        $this->customer->save();

        $expectedAisles = [];
        $expectedEntrances = [9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_9()
    {
        //arrange
        $this->setUpForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 25;
        $this->customer->min_aisle_value = 15000;
        $this->customer->min_entrance_rate = 25;
        $this->customer->min_entrance_value = 15000;
        $this->customer->save();

        $expectedAisles = [];
        $expectedEntrances = [7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_10()
    {
        //arrange
        $this->setUpForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 25;
        $this->customer->min_aisle_value = 1000;
        $this->customer->min_entrance_rate = 25;
        $this->customer->min_entrance_value = 15000;
        $this->customer->save();

        $expectedAisles = [4, 5, 6];
        $expectedEntrances = [7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_11()
    {
        //arrange
        $this->setUpForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 1;
        $this->customer->min_aisle_value = 15000;
        $this->customer->min_entrance_rate = 25;
        $this->customer->min_entrance_value = 15000;
        $this->customer->save();

        $expectedAisles = [3, 4, 5, 6];
        $expectedEntrances = [7, 8, 9];


        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    public function test_promotions_classification_12()
    {
        //arrange
        $this->setUpForCustomerThresholdTests();
        $this->customer->min_aisle_rate = 7;
        $this->customer->min_aisle_value = 3000;
        $this->customer->min_entrance_rate = 25;
        $this->customer->min_entrance_value = 5000;
        $this->customer->save();

        $expectedAisles = [];
        $expectedEntrances = [5, 6, 7, 8, 9];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $this->assertSetEquals($expectedEntrances, $result['entrancePromotions']->lists('id'));
        $this->assertSetEquals($expectedAisles, $result['aislePromotions']->lists('id'));
    }

    private function setUpForMinorMatchingTests()
    {
        $ads = $this->createBasicPromotion([
            'discount_rate' => 10,
            'discount_value' => 7000,
        ]);
        $this->setDefaultThresholds();

        $leafCatsIds = [
            '1115193_1071967_1149392',//Fabric Softener
            '1115193_1071967_1149379',//Laundry Detergents
            '1115193_1071965_1149384',//Bath Tissue
            '1085666_1007221_1023020',//Toothpaste
            '976759_976782_1001680', //Soft Drink
        ];
        $nonLeafCatsIds = [
            '1115193_1071967', //Laundry Room
            '1115193',//Household Essentials
            '1115193_1071965',//Bathroom
            '1085666_1007221',//Oral Care
            '1085666',//Beauty
            '976759_976782',//Beverages
            '976759',//Food
        ];

        //create items
        foreach ($leafCatsIds as $catId) {
            $item = Item::create([
                'id' => $catId,
            ]);
            $this->customer->watchingList()->attach($item);
        }

        Connector::shouldReceive('getCategoryFromItemID')->with(call_user_func_array('anyOf', $leafCatsIds))->andReturnUsing(function ($itemID) {
            return (object)['id' => $itemID];
        });

        //create minors with 1 cat
        foreach (array_merge($leafCatsIds, $nonLeafCatsIds) as $catId) {
            $ids = explode('_', $catId);
            $minor = '9';
            foreach ($ids as $id) {
                $minor .= ((int)$id) % 10;
            }
            BeaconMinor::create([
                'minor' => $minor,
            ])->categories()->attach($catId);
        }

        //create minors with many cats
        $minorCats = [
            2 => ['1115193_1071967_1149392', '1115193_1071967_1149379'],//Softener + Detergents
            3 => ['1115193_1071967_1149392', '1115193_1071965_1149384'],//Softener + Tissue
            4 => ['1085666_1007221_1023020', '976759_976782_1001680'],//Toothpaste + Drink
            5 => ['1115193_1071967_1149392', '1115193_1071967', '1115193'],//Softener + Laundry + Household
            6 => ['1115193_1071965_1149384', '1115193_1071967'],//Tissue + Laundry
            7 => ['1115193_1071965', '1115193_1071967'],//Bathroom + Laundry
            8 => ['1115193_1071965_1149384', '1085666'],//Tissue + Beauty
            9 => ['1115193', '1085666', '976759']//Household + Beauty + Food
        ];

        foreach ($minorCats as $minor => $catId) {
            BeaconMinor::create([
                'minor' => $minor,
            ])->categories()->attach($catId);
        }

        return $ads;
    }

    public function test_minor_matching_for_aisle_promotions_1()
    {
        //arrange
        $ads = $this->createBasicPromotion([
            'discount_rate' => 10,
            'discount_value' => 7000,
        ]);
        $ads->items()->attach(1);
        $this->setDefaultThresholds();
        $this->minor->categories()->detach();
        $this->minor->categories()->attach('1115193');//Household Essentials
        $this->mockConnectorReturnForItem1();

        $expectedMinors = [1];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors, $aislePromotions[0]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_2()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach(['1115193_1071967_1149392']);

        $expectedMinors = [9372, 937, 93, 2, 3, 5, 6, 7, 9];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors, $aislePromotions[0]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_3()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach(['1115193_1071967_1149379']);

        $expectedMinors = [9379, 937, 93, 2, 5, 6, 7, 9];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors, $aislePromotions[0]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_4()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach(['1085666_1007221_1023020']);

        $expectedMinors = [9610, 961, 96, 4, 8, 9];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors, $aislePromotions[0]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_5()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach(['976759_976782_1001680', '1115193_1071965_1149384']);

        $expectedMinors = [9920, 992, 99, 9354, 935, 93, 4, 8, 9, 3, 5, 6, 7];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors, $aislePromotions[0]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_6()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach(['1115193_1071967_1149392', '1115193_1071967_1149379', '1115193_1071965_1149384']);

        $expectedMinors = [9372, 937, 93, 9379, 9354, 935, 2, 3, 5, 6, 7, 8, 9];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors, $aislePromotions[0]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_7()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach(['1115193_1071967_1149392', '1115193_1071967_1149379', '1115193_1071965_1149384',
            '1085666_1007221_1023020', '976759_976782_1001680']);

        $expectedMinors = [9372, 937, 93, 9379, 9354, 935, 9610, 961, 96, 9920, 992, 99, 2, 3, 4, 5, 6, 7, 8, 9];

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors, $aislePromotions[0]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_8()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach('1115193_1071967_1149392');
        $ads2 = $this->createBasicPromotion([
            'discount_rate' => 10,
            'discount_value' => 7000,
        ]);
        $ads2->items()->attach('1115193_1071967_1149379');
        $ads3 = $this->createBasicPromotion([
            'discount_rate' => 10,
            'discount_value' => 7000,
        ]);
        $ads3->items()->attach('1115193_1071965_1149384');

        $expectedMinors1 = [9372, 937, 93, 2, 3, 5, 6, 7, 9];
        $expectedMinors2 = [9379, 937, 93, 2, 5, 6, 7, 9];
        $expectedMinors3 = [9354, 935, 93, 3, 5, 6, 7, 8, 9];
        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1, 2, 3], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors1, $aislePromotions[0]->minors);
        $this->assertSetEquals($expectedMinors2, $aislePromotions[1]->minors);
        $this->assertSetEquals($expectedMinors3, $aislePromotions[2]->minors);
    }

    public function test_minor_matching_for_aisle_promotions_9()
    {
        //arrange
        $ads = $this->setUpForMinorMatchingTests();
        $ads->items()->attach('1115193_1071967_1149392');
        $ads2 = $this->createBasicPromotion([
            'discount_rate' => 10,
            'discount_value' => 7000,
        ]);
        $ads2->items()->attach(['976759_976782_1001680', '1115193_1071965_1149384']);
        $ads3 = $this->createBasicPromotion([
            'discount_rate' => 10,
            'discount_value' => 7000,
        ]);
        $ads3->items()->attach(['1115193_1071967_1149392', '1115193_1071967_1149379', '1115193_1071965_1149384',
            '1085666_1007221_1023020', '976759_976782_1001680']);

        $expectedMinors1 = [9372, 937, 93, 2, 3, 5, 6, 7, 9];
        $expectedMinors2 = [9920, 992, 99, 9354, 935, 93, 4, 8, 9, 3, 5, 6, 7];
        $expectedMinors3 = [9372, 937, 93, 9379, 9354, 935, 9610, 961, 96, 9920, 992, 99, 2, 3, 4, 5, 6, 7, 8, 9];
        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $aislePromotions = $result['aislePromotions'];
        $this->assertSetEquals([1, 2, 3], $aislePromotions->lists('id'));
        $this->assertSetEquals($expectedMinors1, $aislePromotions[0]->minors);
        $this->assertSetEquals($expectedMinors2, $aislePromotions[1]->minors);
        $this->assertSetEquals($expectedMinors3, $aislePromotions[2]->minors);
    }
}
