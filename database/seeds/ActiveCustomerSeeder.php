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
        ActiveCustomer::create(['id'=>'2']);
        $dao=ActiveCustomer::find('1');
        $huy=ActiveCustomer::find('2');

        Item::create(['id'=>'1']);
        Item::create(['id'=>'2']);
        Item::create(['id'=>'3']);
        Item::create(['id'=>'4']);
        Item::create(['id'=>'5']);
        Item::create(['id'=>'6']);
        Item::create(['id'=>'7']);

        $dao->watchingList()->attach(['1','2','3','4']);
        $huy->watchingList()->attach(['3','5','6','7']);
	}

}
