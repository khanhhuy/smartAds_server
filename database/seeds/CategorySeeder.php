<?php

use App\ActiveCustomer;
use App\Beacon;
use App\BeaconMinor;
use App\Category;
use App\Item;
use App\Repositories\CategoryRepository;
use App\WatchingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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
        $categories = $taxonomy['categories'];
        foreach ($categories as $category) {
            $this->insert($category,null);
        }

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

    private function insert(array $category, $parent)
    {
        $cat = new Category(['id' => $category['id'], 'name' => $category['name'], 'parent_id' => $parent['id']]);
        if (array_key_exists('children', $category)) {
            $cat->is_leaf=false;
            $cat->save();
            foreach ($category['children'] as $child) {
                $this->insert($child,$category);
            }
        }
        else{
            $cat->is_leaf=true;
            $cat->save();
        }
    }

}
