<?php

namespace Evocative\FilamentOpenAPI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Evocative\FilamentOpenAPI\FilamentOpenAPI
 */
class FilamentOpenAPI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Evocative\FilamentOpenAPI\FilamentOpenAPI::class;
    }
}
