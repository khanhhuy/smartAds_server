<?php

use App\Ads;
use App\Area;
use App\BeaconMajor;
use App\BeaconMinor;
use App\Category;
use App\Item;
use App\Store;
use Carbon\Carbon;
use App\TargetedRule;

class TargetedAdsTest extends ContextAdsTestCase
{	
	private function createTargetedAd() {
		Ads::create(['id'=>1,'title'=>'Giảm giá các sản phẩm học tập 20/11',
            'is_whole_system' => true, 
            'is_promotion' => false,
            'start_date' => Carbon::now()->subDays(1)->toDateString(),
            'end_date' => Carbon::now()->addDays(1)->toDateString(),
            'thumbnail_url'=>'/img/thumbnails/3.png']);
		Ads::create(['id'=>2,'title'=>'Quốc tế 8/3',
            'is_whole_system' => true, 
            'is_promotion' => false,
            'start_date' => Carbon::now()->subDays(1)->toDateString(),
            'end_date' => Carbon::now()->addDays(1)->toDateString(),
            'thumbnail_url'=>'/img/thumbnails/3.png']);
		Ads::create(['id'=>3,'title'=>'Mừng lễ Noel',
            'is_whole_system' => true, 
            'is_promotion' => false,
            'start_date' => Carbon::now()->subDays(1)->toDateString(),
            'end_date' => Carbon::now()->addDays(1)->toDateString(),
            'thumbnail_url'=>'/img/thumbnails/3.png']);
	}

    public function test_success_example() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '30',
			'gender' => '1', 'from_family_members' => '5', 'to_family_members' => '0', 
			'jobs_desc' => '2,3,4']);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);


    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1990-10-18', 'gender' => '1', 
                            'family_members' => '6', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor);
		$this->assertEquals($ad1->id, $targetedAds[0]->id);	
    }

    public function test_empty_rule() {
    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1990-10-18', 'gender' => '1', 
                            'family_members' => '6', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor);
    	$this->assertTrue($targetedAds->isEmpty());
    }

    public function test_gender() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '30',
			'gender' => '1', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule2 = new TargetedRule(['from_age' => '0', 'to_age' => '30',
			'gender' => '0', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule3 = new TargetedRule(['from_age' => '0', 'to_age' => '30',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);
    	$ad2 = Ads::find(2);
    	$ad2->targetedRule()->save($rule2);
    	$ad3 = Ads::find(3);
    	$ad3->targetedRule()->save($rule3);

    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1990-10-18', 'gender' => '0', 
                            'family_members' => '0', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor)->lists('id');
    	$this->assertContains($ad2->id, $targetedAds);
    	$this->assertContains($ad3->id, $targetedAds);
    	$this->assertNotContains($ad1->id, $targetedAds);
    }

    public function test_age1() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule2 = new TargetedRule(['from_age' => '15', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule3 = new TargetedRule(['from_age' => '20', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);
    	$ad2 = Ads::find(2);
    	$ad2->targetedRule()->save($rule2);
    	$ad3 = Ads::find(3);
    	$ad3->targetedRule()->save($rule3);

    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1997-10-18', 'gender' => '0', 
                            'family_members' => '0', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor)->lists('id');
    	$this->assertContains($ad1->id, $targetedAds);
    	$this->assertContains($ad2->id, $targetedAds);
    	$this->assertNotContains($ad3->id, $targetedAds);	
    }

    public function test_age2() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '15', 'to_age' => '20',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule2 = new TargetedRule(['from_age' => '20', 'to_age' => '25',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule3 = new TargetedRule(['from_age' => '10', 'to_age' => '14',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);
    	$ad2 = Ads::find(2);
    	$ad2->targetedRule()->save($rule2);
    	$ad3 = Ads::find(3);
    	$ad3->targetedRule()->save($rule3);

    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1997-10-18', 'gender' => '0', 
                            'family_members' => '0', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor)->lists('id');
    	$this->assertContains($ad1->id, $targetedAds);
    	$this->assertNotContains($ad2->id, $targetedAds);
    	$this->assertNotContains($ad3->id, $targetedAds);	
    }

    public function test_member1() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule2 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '3', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule3 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '5', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);
    	$ad2 = Ads::find(2);
    	$ad2->targetedRule()->save($rule2);
    	$ad3 = Ads::find(3);
    	$ad3->targetedRule()->save($rule3);

    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1997-10-18', 'gender' => '0', 
                            'family_members' => '3', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor)->lists('id');
    	$this->assertContains($ad1->id, $targetedAds);
    	$this->assertContains($ad2->id, $targetedAds);
    	$this->assertNotContains($ad3->id, $targetedAds);	
    }

    public function test_member2() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '1', 'to_family_members' => '4', 
			'jobs_desc' => null]);

    	$rule2 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '1', 
			'jobs_desc' => null]);

    	$rule3 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '5', 'to_family_members' => '6', 
			'jobs_desc' => null]);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);
    	$ad2 = Ads::find(2);
    	$ad2->targetedRule()->save($rule2);
    	$ad3 = Ads::find(3);
    	$ad3->targetedRule()->save($rule3);

    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1997-10-18', 'gender' => '0', 
                            'family_members' => '1', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor)->lists('id');
    	$this->assertContains($ad1->id, $targetedAds);
    	$this->assertContains($ad2->id, $targetedAds);
    	$this->assertNotContains($ad3->id, $targetedAds);	
    }

    public function test_job1() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => '1,2,3']);

    	$rule2 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule3 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => '1']);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);
    	$ad2 = Ads::find(2);
    	$ad2->targetedRule()->save($rule2);
    	$ad3 = Ads::find(3);
    	$ad3->targetedRule()->save($rule3);

    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1997-10-18', 'gender' => '0', 
                            'family_members' => '3', 'jobs_id' => '2'
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor)->lists('id');
    	$this->assertContains($ad1->id, $targetedAds);
    	$this->assertContains($ad2->id, $targetedAds);
    	$this->assertNotContains($ad3->id, $targetedAds);	
    }

    public function test_job2() {
    	$this->createTargetedAd();

    	$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => '1,2,3']);

    	$rule2 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);

    	$rule3 = new TargetedRule(['from_age' => '0', 'to_age' => '0',
			'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => '1']);

    	$ad1 = Ads::find(1);
    	$ad1->targetedRule()->save($rule1);
    	$ad2 = Ads::find(2);
    	$ad2->targetedRule()->save($rule2);
    	$ad3 = Ads::find(3);
    	$ad3->targetedRule()->save($rule3);

    	Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1997-10-18', 'gender' => '0', 
                            'family_members' => '3', 'jobs_id' => null
                            ]);

    	$targetedAds = $this->contextAdsService->getTargetedAds($this->customer, $this->major, $this->minor)->lists('id');
    	$this->assertNotContains($ad1->id, $targetedAds);
    	$this->assertContains($ad2->id, $targetedAds);
    	$this->assertNotContains($ad3->id, $targetedAds);	
    }


    private function setupCustomerInfo() {
        Connector::shouldReceive('getCustomerInfo')->andReturn(['id' => '1','password' => bcrypt('123456'),
                            'first_name' => 'John', 'last_name' => 'Maxwell',
                            'address' => 'nothing', 'email' => 'john@gmail.com',
                            'birth' => '1997-10-18', 'gender' => '0', 
                            'family_members' => '3', 'jobs_id' => null
                            ]);
    }

    private function setUpTargetedForStoreTests()
    {
        $targets = [
            ['S_vn_tphcm_lythuongkiet'],//1
            ['S_vn_tphcm_phutho'],//2
            ['S_vn_tphcm_lythuongkiet', 'S_vn_tphcm_phutho'],//3
            ['A_vn_tphcm'],//4
            ['A_vn_bac'],//5
            ['A_vn'] //6
        ];
        for ($i = 0; $i < count($targets); $i++) {
            $ads = $this->createBasicTargeted([
                'is_whole_system' => false,
            ]);
            foreach ($targets[$i] as $t) {
                $a = Area::find($t);
                if (!empty($a)) {
                    $ads->areas()->attach($t);
                } else {
                    $ads->stores()->attach($t);
                }
            }
            $rule = new TargetedRule(['from_age' => '0', 'to_age' => '0',
                                'gender' => '2', 'from_family_members' => '0', 'to_family_members' => '0', 
                                'jobs_desc' => null]);
            $ads->targetedRule()->save($rule);
        }
    }



    public function test_targeted_store_1()
    {
        //arrange
        $this->setUpTargetedForStoreTests();
        $this->setupCustomerInfo();
        $this->major->store_id = 'S_vn_tphcm_lythuongkiet';
        $this->major->save();

        $expectedIds = [1, 3, 4, 6];

        //act
        $result = $this->contextAdsService->getTargetedAds($this->customer,
            $this->major, $this->minor);
        //assert
        $ids = $result->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_targeted_store_2()
    {
        //arrange
        $this->setUpTargetedForStoreTests();
        $this->setupCustomerInfo();
        $this->major->store_id = 'S_vn_tphcm_phutho';
        $this->major->save();

        $expectedIds = [2, 3, 4, 6];

        //act
        $result = $this->contextAdsService->getTargetedAds($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $result->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_targeted_store_3()
    {
        //arrange
        $this->setUpTargetedForStoreTests();
        $this->setupCustomerInfo();
        $this->major->store_id = 'S_vn_tphcm_cuchi';
        $this->major->save();

        $expectedIds = [4, 6];

        //act
        $result = $this->contextAdsService->getTargetedAds($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $result->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_targeted_store_4()
    {
        //arrange
        $this->setUpTargetedForStoreTests();
        $this->setupCustomerInfo();
        $this->major->store_id = 'S_vn_trung_danang';
        $this->major->save();

        $expectedIds = [6];

        //act
        $result = $this->contextAdsService->getTargetedAds($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $result->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

    public function test_targeted_store_5()
    {
        //arrange
        $this->setUpTargetedForStoreTests();
        $this->setupCustomerInfo();
        $this->major->store_id = 'S_vn_bac_bacgiang';
        $this->major->save();

        $expectedIds = [5, 6];

        //act
        $result = $this->contextAdsService->getTargetedAds($this->customer,
            $this->major, $this->minor);

        //assert
        $ids = $result->lists('id');
        $this->assertSetEquals($expectedIds, $ids);
    }

}