<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PollRepositoryInterface;
use App\Repositories\PollRepository;
use App\Repositories\VoteRepositoryInterface;
use App\Repositories\VoteRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PollRepositoryInterface::class, PollRepository::class);
        $this->app->bind(VoteRepositoryInterface::class, VoteRepository::class);
    }

    public function boot()
    {
        //
    }
}