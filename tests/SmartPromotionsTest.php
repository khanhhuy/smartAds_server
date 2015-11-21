<?php

use App\Ads;
use Carbon\Carbon;

class SmartPromotionsTest extends ContextAdsTestCase
{
    public function test_basic_example()
    {
        //arrange
        $ads = Ads::create([
            'discount_value' => 999999999999,
            'discount_rate' => 100,
            'is_whole_system' => true,
            'title' => $this->fake->sentence,
            'start_date' => Carbon::now()->subDays(1)->toDateString(),
            'end_date' => Carbon::now()->addDays(1)->toDateString(),
        ]);

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
        $passAdsIds = [];
        $now = Carbon::create();
        $days = array($now->copy()->subDays(14), $now->copy()->subDays(7), $now->copy(), $now->copy()->addDays(7), $now->copy()->addDays(14));
        for ($from = 0; $from < 5; $from++) {
            for ($to = $from; $to < 5; $to++) {
                $ads = Ads::create([
                    'discount_value' => 999999999999,
                    'discount_rate' => 100,
                    'is_whole_system' => true,
                    'title' => $this->fake->sentence,
                    'start_date' => $days[$from]->toDateString(),
                    'end_date' => $days[$to]->toDateString(),
                ]);

                if ($from <= 2 && $to >= 2) {
                    $passAdsIds[] = $ads->id;
                }

                $ads->items()->attach([1]);
            }
        }

        //act
        $result = $this->contextAdsService->getSmartPromotions($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $this->extractPromotions($result)->lists('id');
        $this->assertSetEquals($passAdsIds, $ids);
    }

//    public function test_promotions_appropriate_for_store_1()
//    {
//        //arrange
//        $passAdsIds = [];
//        $now = Carbon::create();
////        for ($to = $from; $to < 5; $to++) {
//            $ads = Ads::create([
//                'discount_value' => '999999999999',
//                'discount_rate' => '100',
//                'is_whole_system' => true,
//                'title' => $this->fake->sentence,
//                'start_date' => $days[$from]->toDateString(),
//                'end_date' => $days[$to]->toDateString(),
//            ]);
//
//            if ($from <= 2 && $to >= 2) {
//                $passAdsIds[] = $ads->id;
//            }
//
//            $ads->items()->attach([1]);
////        }
//
//        //act
//        $result = $this->contextAdsService->getSmartPromotions($this->customer,
//            $this->major, $this->minor);
//
//        //assert
//        $ids = Collection::make($result['entrancePromotions'])->lists('id');
//        $this->assertSetEquals($passAdsIds, $ids);
//    }

}
