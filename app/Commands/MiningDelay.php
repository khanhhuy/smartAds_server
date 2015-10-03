<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use App\Facades\Mining;

class MiningDelay extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	protected $customer, $lastMiningDate;

	public function __construct($customer, $lastMiningDate = null)
	{
		$this->customer = $customer;
		$this->lastMiningDate = $lastMiningDate;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		Mining::miningCustomer($this->customer, true, $this->lastMiningDate);
	}

}
