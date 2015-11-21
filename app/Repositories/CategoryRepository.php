<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 4:26 PM
 */

namespace App\Repositories;

use App\Category;
use App\Facades\Connector;
use DB;


class CategoryRepository implements CategoryRepositoryInterface
{

    public function getItemIDsFromCategories($categories)
    {
        $itemIDs = [];
        foreach ($categories as $category) {
            $itemIDs = array_merge($itemIDs, Connector::getItemIDsFromCategory($category));
        }

        return $itemIDs;
    }

    public function getTaxonomy($convertToArray = false)
    {
        return Connector::getTaxonomy($convertToArray);
    }

    public function getAllCategoryNodesOfItems($items)
    {
        $allCats = [];
        foreach ($items as $item) {
            $remoteCat = Connector::getCategoryFromItemID($item->id);
            if (!empty($remoteCat)) {
                $cat = Category::find($remoteCat->id);
                do {
                    if (in_array($cat->id, $allCats)) {
                        break;
                    }
                    $allCats[] = $cat->id;
                    $cat = $cat->parentCategory;
                } while ($cat !== null);
            }
        }
        return $allCats;
    }

    public function getCategoryTree()
    {
        $tree = DB::table('categories as t1')
        ->select('t1.name as lv1Name', 't1.id as lv1Id', 't1.is_suitable as lv1Suitable',
                't2.name as lv2Name', 't2.id as lv2Id', 't2.is_suitable as lv2Suitable',
                't3.name as lv3Name', 't3.id as lv3Id', 't3.is_suitable as lv3Suitable')
        ->leftJoin('categories as t2', 't1.id', '=', 't2.parent_id')
        ->leftJoin('categories as t3', 't2.id', '=', 't3.parent_id')
        ->whereNull('t1.parent_id')
        ->orderBy('t1.name', 'asc')
        ->orderBy('t2.name', 'asc')
        ->orderBy('t3.name', 'asc')
        ->get();

        $parsedTree = array();
        $index1 = $index2 = $index3 = 0;
        $lv1 = $tree[0]->lv1Id;
        $lv2 = $tree[0]->lv2Id;
        $lv3 = $tree[0]->lv3Id;

        foreach ($tree as $key => $category) {

            //order by Name
            if ($lv1 != $category->lv1Id) {
                $index1++;
                $index2 = $index3 = 0;
                $lv1 = $category->lv1Id;
                $lv2 = $category->lv2Id;
            }
            elseif ($lv2 != $category->lv2Id) {
                $index2++;
                $index3 = 0;
                $lv2 = $category->lv2Id;
            }

            $parsedTree[$index1]["name"] = $category->lv1Name;
            $parsedTree[$index1]["id"] = $category->lv1Id;
            $parsedTree[$index1]["is_suitable"] = $category->lv1Suitable;

            $parsedTree[$index1]["subcat"][$index2]["name"] = $category->lv2Name;
            $parsedTree[$index1]["subcat"][$index2]["id"] = $category->lv2Id;
            $parsedTree[$index1]["subcat"][$index2]["is_suitable"] = $category->lv2Suitable;

            if ($category->lv3Name !== null) {
                $parsedTree[$index1]["subcat"][$index2]["subcat"][$index3]["name"] = $category->lv3Name;
                $parsedTree[$index1]["subcat"][$index2]["subcat"][$index3]["id"] = $category->lv3Id;
                $parsedTree[$index1]["subcat"][$index2]["subcat"][$index3]["is_suitable"] = $category->lv3Suitable;
            }
            else {
                $parsedTree[$index1]["subcat"][$index2]["subcat"]= [];
            }

            $index3++;
        }

        return $parsedTree;
    }

    public function searchCategoryByName($name, $categoryList) {
        $category = Category::query()->whereIn('id', $categoryList)->where('name', 'LIKE', '%'.$name.'%');
        return $category->lists('id');
    }

    public function selectCategory($categories) {
        $catsIds=array_keys($categories);
        DB::table('categories')->whereNotIn('id',$catsIds)->update(['is_suitable'=>0]);
        DB::table('categories')->whereIn('id',$catsIds)->update(['is_suitable'=>1]);
    }
}