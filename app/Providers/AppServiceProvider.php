<?php

namespace App\Providers;

use App\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Modules\User\Observers\UserObserver;
use Laravel\Cashier\Cashier;

// Import Builder where defaultStringLength method is defined

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191); // Update defaultStringLength

        User::observe(UserObserver::class);

        Validator::extend('base64_image', function ($attribute, $value, $parameters, $validator) {
            $explode = explode(',', $value);
            $allow = ['jpg', 'jpeg', 'png'];
            $format = str_replace(
                [
                    'data:image/',
                    ';',
                    'base64',
                ],
                [
                    '', '', '',
                ],
                $explode[0]
            );

            // check file format
            if (!in_array($format, $allow)) {
                return false;
            }

            // check base64 format
            if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                return false;
            }

            return true;
        }, __('validation.base64_image'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations();

        $this->registerJsonPaginateMacro();
    }

    /**
     *
     */
    private function registerJsonPaginateMacro(): void
    {
        EloquentBuilder::macro('jsonPaginate', function () {
            if (request()->has('search')) {
                $search = request()->get('search');
                if (property_exists($this->model, 'searchable')) {
                    $this->where(function ($q) use ($search) {
                        foreach ($this->model->searchable as $item) {
                            $q->orWhere($item, 'LIKE', '%' . $search . '%');
                        }
                    });
                }
            }

            if (request()->has('ordering')) {
                $this->getQuery()->orders = null;

                $order = request()->get('ordering');

                if (Str::contains($order, '-')) {
                    $this->orderByDesc(Str::replaceFirst('-', '', $order));
                } else {
                    $this->orderBy($order);
                }
            }


            $perPage = 10;
            if (request()->has('limit')) {
                $perPage = request()->get('limit');

                if ($perPage == '-1') {
                    $perPage = 3000;
                }
            }

            return $this->paginate($perPage);
        });
    }
}
