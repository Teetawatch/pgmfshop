<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Spatie\FlareClient\Context\ContextProvider;
use Spatie\FlareClient\Context\ContextProviderDetector;
use Spatie\FlareClient\Flare;
use Spatie\LaravelIgnition\ContextProviders\LaravelConsoleContextProvider;
use Spatie\LaravelIgnition\ContextProviders\LaravelRequestContextProvider;

class IgnitionFixServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix incompatibility between spatie/laravel-ignition v2.x and Livewire v4.
        // LaravelLivewireRequestContextProvider references the removed
        // Livewire\Mechanisms\ComponentRegistry class, crashing the error page.
        if ($this->app->resolved(Flare::class)) {
            $this->app->make(Flare::class)
                ->setContextProviderDetector(new LivewireV4SafeContextProviderDetector());
        } else {
            $this->app->afterResolving(Flare::class, function (Flare $flare) {
                $flare->setContextProviderDetector(new LivewireV4SafeContextProviderDetector());
            });
        }
    }
}

class LivewireV4SafeContextProviderDetector implements ContextProviderDetector
{
    public function detectCurrentContext(): ContextProvider
    {
        if (app()->runningInConsole()) {
            return new LaravelConsoleContextProvider($_SERVER['argv'] ?? []);
        }

        return new LaravelRequestContextProvider(app(Request::class));
    }
}
