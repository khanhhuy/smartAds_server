<?php

use App\Category;
use App\Utils\Utils;
use App\WatchingList;
use Illuminate\Database\Seeder;

class TestingCategorySeeder extends Seeder
{

    public function run()
    {
        $forceRefresh = false;
        if (!$forceRefresh && !Category::all()->isEmpty()) {
            return;
        }
        DB::table('categories')->delete();
        DB::table('category_minor')->delete();
        $catRepo = App::make('App\Repositories\CategoryRepositoryInterface');
        $taxonomy = $catRepo->getTaxonomy(true);
        Utils::updateTaxonomy($taxonomy, false);
    }

//    private function seedCatMinor()
//    {
//        $catFabricSofteners = Category::find('1115193_1071967_1149392');
//        $minor1 = BeaconMinor::find(1);
//        $minor1->categories()->attach($catFabricSofteners);
//    }

}
