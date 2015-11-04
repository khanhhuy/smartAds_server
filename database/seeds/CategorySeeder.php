<?php

use App\BeaconMinor;
use App\Category;
use App\Utils\Utils;
use App\WatchingList;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder {

	public function run()
	{
        $forceRefresh=false;
        if (!$forceRefresh&&!Category::all()->isEmpty()){
            $this->command->info('Table categories not empty, skipped this table. Turn on forceRefresh if you want!');
            $this->seedCatMinor();
            return ;
        }
        DB::table('categories')->delete();
        DB::table('category_minor')->delete();
        $catRepo=App::make('App\Repositories\CategoryRepositoryInterface');
        $taxonomy = $catRepo->getTaxonomy(true);
        Utils::updateTaxonomy($taxonomy,false);
        $this->seedCatMinor();
	}

    private function seedCatMinor(){
        $catFabricSofteners=Category::find('1115193_1071967_1149392');
        $catLaundryDetergents=Category::find('1115193_1071967_1149379');
        $catToothpaste=Category::find('1085666_1007221_1023020');
        $catSoftDrinks=Category::find('976759_976782_1001680');

        $minor1=BeaconMinor::find(1);
        $minor2=BeaconMinor::find(2);
        $minor3=BeaconMinor::find(3);


        $minor1->categories()->attach($catFabricSofteners);
        $minor2->categories()->attach($catLaundryDetergents);
        $minor3->categories()->attach([$catSoftDrinks->id, $catToothpaste->id]);
    }

}
