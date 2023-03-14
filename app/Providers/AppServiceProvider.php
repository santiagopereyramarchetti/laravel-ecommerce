<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Password::defaults(function () {
            $rule = Password::min(8);
     
            return $this->app->isProduction()
                        ? $rule->mixedCase()->uncompromised()
                        : $rule;
        });

        Relation::enforceMorphMap([
            'category' => Category::class,
            'category_product' => CategoryProduct::class,
            'product' => Product::class,
            'user' => User::class
        ]);
    }
}
