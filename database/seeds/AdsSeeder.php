<?php

use App\Ads;
use App\WatchingList;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder {

	public function run()
	{
        DB::table('ads_item')->delete();
        DB::table('targeted_rules')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ads')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        Ads::create(['id'=>1,'title'=>'Bột giặt Tide Downy 4.5kg Giảm giá 20%','end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'32500',
            'is_whole_system'=>true,
            'discount_rate'=>'20',
            'thumbnail_url' => '/img/thumbnails/1.png',
            'provide_image_link' => false,
            'auto_thumbnail' => true,
            'image_url' => '/img/ads/1.png'])->items()->attach(1);

        Ads::create(['id'=>2,'title'=>'Bột giặt ARIEL DOWNY 4.1kg Giảm giá 20%',
            'end_date' => Carbon::createFromDate(2015, 1, 1),
        'discount_value'=>'35500',
            'is_whole_system'=>true,
        'discount_rate'=>'20',
            'thumbnail_url' => '/img/thumbnails/2.png',
            'provide_image_link' => false,
            'auto_thumbnail' => true,
            'image_url' => '/img/ads/2.png'])->items()->attach(5);

        Ads::create(['id'=>3,'title'=>'Kem đánh răng COLGATE the mát bạc hà 150g Giảm giá 19%',
            'is_whole_system' => false, 'end_date' => Carbon::createFromDate(2015, 1, 1),
            'discount_value'=>'3800',
            'discount_rate'=>'19',
            'thumbnail_url' => '/img/thumbnails/3.png',
            'provide_image_link' => false,
            'auto_thumbnail' => true,
            'image_url' => '/img/ads/3.png'])->items()->attach(2);

        Ads::create(['id'=>4,'title'=>'Nước xả DOWNY nắng mai túi 1.8L Giảm giá 18%',
            'is_whole_system'=>false,'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'17200',//17200
            'discount_rate'=>'18',//18
            'thumbnail_url' => '/img/thumbnails/4.png',
            'provide_image_link' => false,
            'auto_thumbnail' => true,
            'image_url' => '/img/ads/4.png'
        ])->items()->attach(3);

        Ads::create(['id'=>5,'title'=>'Nước ngọt Pepsi 1.5L Giảm giá 12%',
            'is_whole_system'=>false,'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'1900',
            'discount_rate'=>'12',
            'thumbnail_url' => '/img/thumbnails/5.png',
            'provide_image_link' => false,
            'auto_thumbnail' => true,
            'image_url' => '/img/ads/5.png'
        ])->items()->attach(4);

        Ads::create(['id'=>6,'title'=>'Nước giặt Omo Matic Font Load 2.7kg Giảm giá 10%',
        'is_whole_system'=>false,'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'17500',
            'discount_rate'=>'10',
            'thumbnail_url' => '/img/thumbnails/6.png',
            'provide_image_link' => false,
            'auto_thumbnail' => true,
            'image_url' => '/img/ads/6.png'
        ])->items()->attach(7);

        Ads::create(['id'=>7,'title'=>'Nước xả DOWNY 1 lần xả túi 1.6L Giảm giá 18%',
        'is_whole_system'=>false,
            'start_date'=>Carbon::createFromDate(2016,5,5),
            'end_date'=>Carbon::createFromDate(2017,1,1),
            'discount_value'=>'17500',
            'discount_rate'=>'18',
            'thumbnail_url' => '/img/thumbnails/7.png',
            'provide_image_link' => false,
            'auto_thumbnail' => true,
            'image_url' => '/img/ads/7.png'
        ])->items()->attach(6);

	}
}
