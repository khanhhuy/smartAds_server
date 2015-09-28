<?php

use App\ActiveCustomer;
use App\Item;
use App\WatchingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ActiveCustomerSeeder extends Seeder {

	public function run()
	{
        DB::table('watching_lists')->delete();
        DB::table('active_customers')->delete();
        DB::table('items')->delete();

        ActiveCustomer::create(['id'=>'1']);
        ActiveCustomer::create(['id'=>'2',
            'min_aisle_value'=>'5000',
            'min_aisle_rate'=>'0.12',
            'min_entrance_value'=>'20000',
            'min_entrance_value'=>'0.2',
        ]);

        ActiveCustomer::create(['id'=>'3']);
        ActiveCustomer::create(['id'=>'4']);

        $dao=ActiveCustomer::find('1');
        $huy=ActiveCustomer::find('2');

        Item::create(['id'=>'1']);
        Item::create(['id'=>'2']);
        Item::create(['id'=>'3']);
        Item::create(['id'=>'4']);
        Item::create(['id'=>'5']);
        Item::create(['id'=>'6']);
        Item::create(['id'=>'7']);
        Item::create(['id'=>'23591099']);
        Item::create(['id'=>'23591257']);
        Item::create(['id'=>'43172984']);

        $dao->watchingList()->attach(['1','2','3','4']);
        $huy->watchingList()->attach(['3','5','6','7']);
	}

}
