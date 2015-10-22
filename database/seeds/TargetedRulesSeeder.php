<?php

use App\TargetedRule;
use App\Ads;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TargetedRulesSeeder extends Seeder {
	public function run() {

		DB::table('targeted_rules')->delete();

		$rule1 = new TargetedRule();
		$rule2 = new TargetedRule(['from_age' => '0', 'to_age' => '5']);
		$rule3 = new TargetedRule(['from_age' => '14', 'to_age' => '30',
			'gender' => '1', 'jobs_desc' => '2,3,4']);
		$rule4 = new TargetedRule(['from_family_members' => '2', 
			'to_family_members' => '5', 'jobs_desc' => '1']);
		$rule5 = new TargetedRule(['from_age' => '15', 'to_age' => '25',
			'gender' => '0', 'from_family_members' => '2', 'to_family_members' => '5', 
			'jobs_desc' => '2,4']);

		Ads::find('1')->targetedRule()->save($rule1);
		Ads::find('2')->targetedRule()->save($rule2);
		Ads::find('3')->targetedRule()->save($rule3);
	}
}