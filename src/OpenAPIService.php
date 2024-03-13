<?php

namespace Evocative\FilamentOpenAPI;

use Filament\Panel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class OpenAPIService
{
    /**
     * Filament Resource
     */
    protected static ?string $resource = null;

    public static function getResource()
    {
        return static::$resource;
    }

    public static function registerRoutes(Panel $panel)
    {
        $slug = static::getResource()::getSlug();

        $name = Str::of($slug)
            ->replace('/', '.')
            ->append('.')
            ->toString();

        $panelId = $panel->getId();

        Route::name("api.$panelId.$name")
            ->prefix("$panelId/$slug")
            ->group(function (Router $route) {
                collect(static::handlers())
                    ->each(fn ($handler) => app($handler)->route($route));
            });
    }

    public static function handlers(): array
    {
        return [];
    }
}
