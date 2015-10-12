<?php

use App\ActiveCustomer;
use App\Ads;
use App\Beacon;
use App\BeaconMinor;
use App\Item;
use App\WatchingList;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdsSeeder extends Seeder {

	public function run()
	{
        DB::table('ads_item')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ads')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        Ads::create(['id'=>1,'title'=>'Bột giặt Tide Downy 4.5kg Giảm giá 20%','end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'32500',
            'is_whole_system'=>true,
            'discount_rate'=>'20'])->items()->attach(1);

        Ads::create(['id'=>2,'title'=>'Bột giặt ARIEL DOWNY 4.1kg Giảm giá 20%',
            'end_date'=>Carbon::createFromDate(2015,1,1),
        'discount_value'=>'35500',
            'is_whole_system'=>true,
        'discount_rate'=>'20'])->items()->attach(5);

        Ads::create(['id'=>3,'title'=>'Kem đánh răng COLGATE the mát bạc hà 150g Giảm giá 19%',
        'is_whole_system'=>false,'end_date'=>Carbon::createFromDate(2016,1,1),
            'discount_value'=>'3800',
            'discount_rate'=>'19'])->items()->attach(2);

        Ads::create(['id'=>4,'title'=>'Nước xả DOWNY nắng mai túi 1.8L Giảm giá 18%',
            'is_whole_system'=>false,'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'17200',//17200
            'discount_rate'=>'18'//18
        ])->items()->attach(3);

        Ads::create(['id'=>5,'title'=>'Nước ngọt Pepsi 1.5L Giảm giá 12%',
            'is_whole_system'=>false,'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'1900',
            'discount_rate'=>'12'
        ])->items()->attach(4);

        Ads::create(['id'=>6,'title'=>'Nước giặt Omo Matic Font Load 2.7kg Giảm giá 10%',
        'is_whole_system'=>false,'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'17500',
            'discount_rate'=>'10'
        ])->items()->attach(7);

        Ads::create(['id'=>7,'title'=>'Nước xả DOWNY 1 lần xả túi 1.6L Giảm giá 18%',
        'is_whole_system'=>false,
            'start_date'=>Carbon::createFromDate(2016,5,5),
            'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'17500',
            'discount_rate'=>'18'
        ])->items()->attach(6);

	}
}
