<?php

use App\TargetedRule;
use App\Ads;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TargetedRulesSeeder extends Seeder {
	public function run() {

		DB::table('targeted_rules')->delete();

		$rule1 = new TargetedRule(['from_age' => '0', 'to_age' => '5'
			'gender' => '0', 'from_family_members' => '5', 'to_family_members' => '0', 
			'jobs_desc' => '2,3,4']);
		$rule2 = new TargetedRule(['from_age' => '14', 'to_age' => '30',
			'gender' => '1', 'from_family_members' => '0', 'to_family_members' => '0', 
			'jobs_desc' => null]);
		$rule3 = new TargetedRule(['from_age' => '15', 'to_age' => '25',
			'gender' => '2', 'from_family_members' => '2', 'to_family_members' => '5', 
			'jobs_desc' => '1']);

		Ads::find('1')->targetedRule()->save($rule1);
		Ads::find('2')->targetedRule()->save($rule2);
		Ads::find('3')->targetedRule()->save($rule3);
	}
}