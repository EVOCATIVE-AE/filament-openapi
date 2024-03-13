<?php

namespace Evocative\FilamentOpenAPI;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class Handler
{
    public static ?string $uri = '/';

    public static ?string $resource;

    public static function getMethod()
    {
        return 'GET';
    }

    public static function route(Router $router)
    {
        $method = static::getMethod();

        $router
            ->{$method}(static::$uri, [static::class, 'handler'])
            ->name(static::getKebabClassName());
    }

    public static function getKebabClassName()
    {
        return Str::of(
            Str::of(static::class)
                ->beforeLast('Handler')
                ->explode('\\')
                ->last()
        )->kebab();
    }
}
