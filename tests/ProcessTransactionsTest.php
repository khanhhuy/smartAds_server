<?php

use App\Ads;
use App\Item;
use App\ActiveCustomer;
use App\Category;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ProcessTransactionsTest extends ApiTestCase {

	protected $customer;
	protected $processService;
	protected $fake;
	protected $categoryList = ['1115193_1071967_1149392', '1115193_1071967_1149379', 
								'1085666_1007221_1023020', '976759_976782_1001680'];

	public function __construct()
    {
        $this->fake = Faker::create();
    }

	public function setUp()
    {
        parent::setUp();
        $this->processService = $this->app->make('processTransaction');
        $this->seed('TestingDatabaseSeeder');
        $this->customer = ActiveCustomer::all()->first();
        $this->createSimpleItems();
        $this->customer->watchingList()->detach();
        $this->customer->blackList()->detach();
        $this->setUpCategoryItem();
        $this->resetCategorySuitable();
    }

    private function createSimpleItems() {
    	Item::updateOrCreate(['id' => 1]);//Tide Downy 4.5kg
        Item::create(['id' => '2']);//Colgate 150g
        Item::create(['id' => '3']);//DOWNY nang mai
        Item::create(['id' => '4']);//Pepsi 1.5L
        Item::create(['id' => '5']);//ARIEL DOWNY
        Item::create(['id' => '6']);//Downy 1 lan xa
        Item::create(['id' => '7']);//Omo Matic
    }

    private function createTransactionHistory($transIdList, $customerId) {
    	$transaction = array();
    	foreach ($transIdList as $transId) {
    		$transaction[] = ['customer_id' => $customerId, 'item_id' => $transId, 
    							'time' => '2015-10-25 10:02:01', 'quantity' => '1'];
    	}
    	return $transaction;
    }

    private function setUpCategoryItem() {
    	$catFabricSofteners = Category::find('1115193_1071967_1149392');
        $catLaundryDetergents = Category::find('1115193_1071967_1149379');
        $catToothpaste = Category::find('1085666_1007221_1023020');
        $catSoftDrinks = Category::find('976759_976782_1001680');
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
    }

    private function setupSuitableCategory($categories) {
    	foreach ($categories as $value) {
    		$cat = Category::find($value);
    		$cat->is_suitable = true;
    		$cat->save();
    	}
    }

    private function resetCategorySuitable() {
    	foreach ($this->categoryList as $value) {
    		$cat = Category::find($value);
    		$cat->is_suitable = false;
    		$cat->save();
    	}
    }

    public function test_simple_process() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 1, 2, 3, 5, 4, 2], 1));
		$this->setupSuitableCategory($this->categoryList);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(false);
		$watchingList = $this->processService->processCustomer($this->customer, $date);

		$this->assertContains(1, $watchingList);
		$this->assertContains(2, $watchingList);
    }

    public function test_blackList() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 1, 2, 3, 5, 4, 2], 1));
    	$this->customer->blackList()->attach([1, 3, 5]);
		$this->setupSuitableCategory($this->categoryList);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(false);
		$watchingList = $this->processService->processCustomer($this->customer, $date);

		$this->assertNotContains(1, $watchingList);
		$this->assertContains(2, $watchingList);
    }

    public function test_notSuitable() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 1, 2, 3, 5, 4, 2], 1));
		$this->setupSuitableCategory(['1115193_1071967_1149392', '1115193_1071967_1149379', '976759_976782_1001680']);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(false);
		$watchingList = $this->processService->processCustomer($this->customer, $date);

		$this->assertContains(1, $watchingList);
		$this->assertNotContains(2, $watchingList);	
    }

    public function test_empty1() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn([]);
		$watchingList = $this->processService->processCustomer($this->customer, $date);
		$this->assertEmpty($watchingList);
    }

    public function test_empty2() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn(null);
		$watchingList = $this->processService->processCustomer($this->customer, $date);
		$this->assertEmpty($watchingList);
    }

    public function test_empty3() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 1, 2, 3, 5, 4, 2], 1));
		$this->setupSuitableCategory(['1115193_1071967_1149392', '1115193_1071967_1149379', '976759_976782_1001680']);
		$this->customer->blackList()->attach([1, 2, 3, 4]);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(false);
		$watchingList = $this->processService->processCustomer($this->customer, $date);

		$this->assertEmpty($watchingList);
    }

    public function test_empty4() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 2, 3, 5, 4], 1));
		$this->setupSuitableCategory($this->categoryList);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(false);
		$watchingList = $this->processService->processCustomer($this->customer, $date);

		$this->assertEmpty($watchingList);
    }

    public function test_related1() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 1, 2, 2], 1));
		$this->setupSuitableCategory($this->categoryList);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(true);
		$relatedItems1 = [['id' => 3, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(1)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		Connector::shouldReceive('getRelatedItem')->with(1)->andReturn($relatedItems1);
		Connector::shouldReceive('getRelatedItem')->with(2)->andReturn(null);

		$watchingList = $this->processService->processCustomer($this->customer, $date);
		$this->assertContains(1, $watchingList);
		$this->assertContains(2, $watchingList);
		$this->assertContains(3, $watchingList);
		$this->assertContains(4, $watchingList);
    }

    public function test_related2() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 2, 1, 1, 3], 1));
		$this->setupSuitableCategory($this->categoryList);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(true);
		$relatedItems1 = [['id' => 3, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(1)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		Connector::shouldReceive('getRelatedItem')->with(1)->andReturn($relatedItems1);
		Connector::shouldReceive('getRelatedItem')->with(2)->andReturn(null);
		Connector::shouldReceive('getRelatedItem')->with(3)->andReturn([]);

		$watchingList = $this->processService->processCustomer($this->customer, $date);
		$this->assertContains(1, $watchingList);
		$this->assertContains(3, $watchingList);
		$this->assertContains(4, $watchingList);
		$this->assertNotContains(2, $watchingList);
		$this->assertEquals(3, sizeof($watchingList));
    }

    public function test_related3() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 2, 1, 1, 3, 2, 3], 1));
		$this->setupSuitableCategory($this->categoryList);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(true);
		$relatedItems1 = [['id' => 3, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(1)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		$relatedItems3 = [['id' => 5, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(5)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		Connector::shouldReceive('getRelatedItem')->with(1)->andReturn($relatedItems1);
		Connector::shouldReceive('getRelatedItem')->with(2)->andReturn(null);
		Connector::shouldReceive('getRelatedItem')->with(3)->andReturn($relatedItems3);

		$watchingList = $this->processService->processCustomer($this->customer, $date);
		$this->assertContains(1, $watchingList);
		$this->assertContains(2, $watchingList);
		$this->assertContains(3, $watchingList);
		$this->assertContains(4, $watchingList);
		$this->assertContains(5, $watchingList);
		$this->assertEquals(5, sizeof($watchingList));
    }

	public function test_related_notSuitable() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 2, 1, 1, 3, 2, 3], 1));
		$this->setupSuitableCategory(['1115193_1071967_1149392', '1115193_1071967_1149379', 
								'1085666_1007221_1023020']);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(true);
		$relatedItems1 = [['id' => 3, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(1)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		$relatedItems3 = [['id' => 5, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(5)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		Connector::shouldReceive('getRelatedItem')->with(1)->andReturn($relatedItems1);
		Connector::shouldReceive('getRelatedItem')->with(2)->andReturn(null);
		Connector::shouldReceive('getRelatedItem')->with(3)->andReturn($relatedItems3);

		$watchingList = $this->processService->processCustomer($this->customer, $date);
		$this->assertContains(1, $watchingList);
		$this->assertContains(2, $watchingList);
		$this->assertContains(3, $watchingList);
		$this->assertNotContains(4, $watchingList);
		$this->assertContains(5, $watchingList);
		$this->assertEquals(4, sizeof($watchingList));
    }

    public function test_related_blackList() {
    	$date = Carbon::now()->subMonths(6)->toDateString();
    	Connector::shouldReceive('getShoppingHistoryFromCustomer')->with(anything(), $date, null)
    						->andReturn($this->createTransactionHistory([1, 2, 1, 1, 3, 2, 3], 1));
		$this->setupSuitableCategory($this->categoryList);
		$this->customer->blackList()->attach([4, 5]);
		Setting::shouldReceive('get')->with('process-config.related-item')->andReturn(true);
		$relatedItems1 = [['id' => 3, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(1)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		$relatedItems3 = [['id' => 5, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(5)],
							['id' => 4, 'name' =>  $this->fake->sentence, 'category_id' => Connector::getCategoryFromItemID(4)]];
		Connector::shouldReceive('getRelatedItem')->with(1)->andReturn($relatedItems1);
		Connector::shouldReceive('getRelatedItem')->with(2)->andReturn(null);
		Connector::shouldReceive('getRelatedItem')->with(3)->andReturn($relatedItems3);

		$watchingList = $this->processService->processCustomer($this->customer, $date);
		$this->assertContains(1, $watchingList);
		$this->assertContains(2, $watchingList);
		$this->assertContains(3, $watchingList);
		$this->assertEquals(3, sizeof($watchingList));
    }
}
