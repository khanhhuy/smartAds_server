<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use App\Facades\ProcessTransaction;

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
