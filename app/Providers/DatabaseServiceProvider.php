<?php

namespace App\Providers;


use App\Category;
use App\Repositories\Category\CachingCategoryRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\ICategoryRepository;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ICategoryRepository::class, function () {
            $categoryRepo = new CategoryRepository(new Category());

            return new CachingCategoryRepository(
                $categoryRepo, $this->app['cache.store']
            );
        });
    }

}