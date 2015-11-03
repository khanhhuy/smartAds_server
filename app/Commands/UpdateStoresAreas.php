<?php namespace App\Commands;

use App\Repositories\StoreRepositoryInterface;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Utils;

class UpdateStoresAreas extends Command implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @param StoreRepositoryInterface $storeRepo
     */
    public function handle(StoreRepositoryInterface $storeRepo)
    {
        $stores = $storeRepo->getAllStores(true);
        Utils::updateStoresAreas($stores);
    }

}
