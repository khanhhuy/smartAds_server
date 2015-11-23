<?php

use App\Ads;
use App\BeaconMajor;
use App\BeaconMinor;
use App\Item;
use App\ActiveCustomer;
use Carbon\Carbon;

class FeedbackTest extends ApiTestCase {

	protected $customer;

	public function setUp()
    {
        parent::setUp();
        $this->seed('TestingDatabaseSeeder');
        $this->customer = ActiveCustomer::all()->first();
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

    private function createSimpleAds() {
    	return Ads::create(['id'=>1,'title'=>'Giảm giá các sản phẩm học tập 20/11',
	            'is_whole_system' => true, 
	            'is_promotion' => true,
	            'start_date' => Carbon::now()->subDays(1)->toDateString(),
	            'end_date' => Carbon::now()->addDays(1)->toDateString(),
	            'thumbnail_url'=>'/img/thumbnails/3.png']);
    }

	public function test_success_feedback1() {
		$this->createSimpleItems();
		$ad = $this->createSimpleAds();
		$ad->items()->attach([1, 2]);

		$this->customer->watchingList()->detach();
        $this->customer->watchingList()->attach(['1', '2', '3']);

        $response = $this->call('POST', '/api/v1/customers/'.$this->customer->id.'/feedback', 
        	['access_token' => 'dev', 'adsId' => $ad->id]);

        $watchingList = $this->customer->watchingList->lists('id');
        $blackList = $this->customer->blackList->lists('id');

        $this->assertResponseOk();
        $this->assertNotContains('1', $watchingList);
        $this->assertNotContains('2', $watchingList);
        $this->assertContains('3', $watchingList);
        $this->assertContains('1', $blackList);
        $this->assertContains('2', $blackList);
	}

	public function test_success_feedback2() {
		$this->createSimpleItems();
		$ad = $this->createSimpleAds();
		$ad->items()->attach([1, 2]);

		$this->customer->watchingList()->detach();
        $this->customer->watchingList()->attach(['1', '2', '3']);
        $this->customer->blackList()->detach();
        $this->customer->blackList()->attach('1');

        $response = $this->call('POST', '/api/v1/customers/'.$this->customer->id.'/feedback', 
        	['access_token' => 'dev', 'adsId' => $ad->id]);

        $watchingList = $this->customer->watchingList->lists('id');
        $blackList = $this->customer->blackList->lists('id');

        $this->assertResponseOk();
        $this->assertNotContains('1', $watchingList);
        $this->assertNotContains('2', $watchingList);
        $this->assertContains('3', $watchingList);
        $this->assertContains('1', $blackList);
        $this->assertContains('2', $blackList);
    }

    public function test_success_feedback3() {
		$this->createSimpleItems();
		$ad = $this->createSimpleAds();
		$ad->items()->attach([1, 2]);

		$this->customer->watchingList()->detach();
        $this->customer->blackList()->detach();
        $this->customer->blackList()->attach('1');

        $response = $this->call('POST', '/api/v1/customers/'.$this->customer->id.'/feedback', 
        	['access_token' => 'dev', 'adsId' => $ad->id]);

        $watchingList = $this->customer->watchingList->lists('id');
        $blackList = $this->customer->blackList->lists('id');

        $this->assertResponseOk();
        $this->assertNotContains('1', $watchingList);
        $this->assertNotContains('2', $watchingList);
        $this->assertContains('1', $blackList);
        $this->assertContains('2', $blackList);
    }

	public function test_empty_feedback1() {
		$this->createSimpleItems();
		$ad = $this->createSimpleAds();
		$response = $this->call('POST', '/api/v1/customers/'.$this->customer->id.'/feedback', 
        	['access_token' => 'dev', 'adsId' => $ad->id]);
		$this->assertResponseOk();
		$this->assertNull($response->getOriginalContent());
	}
	public function test_empty_feedback2() {
		$this->createSimpleItems();
		$ad = $this->createSimpleAds();
		$response = $this->call('POST', '/api/v1/customers/'.$this->customer->id.'/feedback', 
        	['access_token' => 'dev', 'adsId' => 100]);
		$this->assertResponseOk();
		$this->assertNull($response->getOriginalContent());
	}

}