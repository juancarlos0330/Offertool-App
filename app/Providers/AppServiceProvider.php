<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Pricetype;
use App\Pricerole;
use App\Language;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $viewShare['languagelists'] = Language::all();

        view()->share($viewShare);

        view()->composer('partials.menu', function($view) {
            $view->with([
                'pricetypelist_count' => Pricetype::all()->count(),
                'menu_pricetypelist' => DB::table('priceroles')->leftJoin('pricetypes', 'priceroles.pricetype_id', '=', 'pricetypes.id')->whereNull('priceroles.deleted_at')->where('priceroles.user_id', auth()->user()->id)->select('priceroles.*', 'pricetypes.name', 'pricetypes.id as ptid')->get(),
            ]);
        });
    }
}
