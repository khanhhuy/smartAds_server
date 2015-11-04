<?php namespace App\Commands;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Utils;

class UpdateTaxonomy extends Command implements SelfHandling, ShouldBeQueued
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Execute the command.
     *
     * @param CategoryRepositoryInterface $catRepo
     */
    public function handle(CategoryRepositoryInterface $catRepo)
    {
        $taxonomy = $catRepo->getTaxonomy(true);
        Utils::updateTaxonomy($taxonomy);
    }

}
