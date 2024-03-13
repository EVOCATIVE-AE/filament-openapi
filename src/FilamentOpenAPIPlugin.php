<?php

namespace Evocative\FilamentOpenAPI;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\RouteInfo;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FilamentOpenAPIPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-openapi';
    }

    public function register(Panel $panel): void
    {
        Scramble::routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api/');
        });

        Scramble::resolveTagsUsing(function (RouteInfo $routeInfo) {
            return array_values(array_unique(
                Arr::map($routeInfo->phpDoc()->getTagsByName('@tag'), fn ($tag) => trim($tag?->value?->value))
            ));
        });
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function route(Panel $panel): void
    {
        collect($panel->getResources())->each(function (string $resource) use ($panel) {
            $resourceName = Str::of($resource)
                ->beforeLast('Resource')
                ->explode('\\')
                ->last();

            $serviceClass = $resource . '\\OpenAPI\\' . $resourceName . 'Service';

            if (class_exists($serviceClass)) {
                $class = app($serviceClass);

                if (method_exists($class, 'registerRoutes')) {

                    $class->registerRoutes($panel);
                }
            }
        });
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
