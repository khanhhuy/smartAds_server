<?php namespace App\Commands;

use App\Facades\ProcessTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDelay extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	protected $customer, $fromDate;

	public function __construct($customer, $fromDate = null)
	{
		$this->customer = $customer;
		$this->fromDate = $fromDate;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		ProcessTransaction::processCustomer($this->customer, $this->fromDate);
	}

}
