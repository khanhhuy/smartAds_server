<?php

use App\PreferenceRule;
use App\Ads;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PreferenceRulesSeeder extends Seeder {
	public function run() {

		DB::table('ads_rules')->delete();
		DB::table('preference_rules')->delete();

		PreferenceRule::create(['id' => '1']);
		PreferenceRule::create(['id' => '2', 'from_age' => '0', 'to_age' => '5']);
		PreferenceRule::create(['id' => '3', 'from_age' => '14', 'to_age' => '30',
			'gender' => '1', 'jobs_desc' => '2,3,4']);
		PreferenceRule::create(['id' => '4', 'from_family_members' => '2', 
			'to_family_members' => '5', 'jobs_desc' => '1']);
		PreferenceRule::create(['id' => '5', 'from_age' => '15', 'to_age' => '25',
			'gender' => '0', 'from_family_members' => '2', 'to_family_members' => '5', 
			'jobs_desc' => '2,4']);

		Ads::find('1')->prefRules()->attach(['1', '3', '5']);
		Ads::find('2')->prefRules()->attach(['2', '3', '4']);
		Ads::find('3')->prefRules()->attach(['1', '2', '3', '5']);
	}
}