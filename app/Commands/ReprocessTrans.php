<?php namespace App\Commands;

use App\Commands\Command;
use App\Facades\ProcessTransaction;
use Utils;

use Illuminate\Contracts\Bus\SelfHandling;

class ReprocessTrans extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	protected $fromDate, $toDate;

	public function __construct($fromDate = null, $toDate = null)
	{
		$this->fromDate = $fromDate;
		$this->toDate = $toDate;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		ProcessTransaction::processAllCustomer($this->fromDate, $this->toDate);
		Utils::updateReprocessTrans();
	}

}
