<?php

namespace Josephdlmd\FilamentUx;

use Illuminate\Support\ServiceProvider;

/**
 * Registers the layout building blocks' one view, the Identity bar heading, under the `filament-ux` namespace.
 */
class FilamentUxServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-ux');
    }
}
