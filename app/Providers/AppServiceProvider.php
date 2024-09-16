<?php

namespace App\Providers;

use App\Core\Database\QueryDebugger;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $pathRepository = glob(app_path('/Repositories') ."/*.php");

        foreach ($pathRepository as $path) {
            $arrPath = explode('/', $path);
            $fileNameRepository = end($arrPath);
            if ($fileNameRepository == 'BaseRepository.php') {
                continue;
            }
            $repoName = preg_replace('/.php$/', '', $fileNameRepository);
            $this->app->singleton(
                "App\Contracts\Repositories\\{$repoName}Interface",
                "App\Repositories\\{$repoName}"
            );
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        QueryDebugger::setup();
        Validator::replacer('inertia', function ($validator) {
            return back()->withErrors($validator)->withInput();
        });
    }
}
