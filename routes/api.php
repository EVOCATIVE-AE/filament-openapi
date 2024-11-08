<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'api',
], function () {
    $panels = Filament::getPanels();

    foreach ($panels as $key => $panel) {
        if ($panel->hasPlugin('filament-openapi')) {
            Route::group([
                'prefix' => $key,
            ], function () use ($panel) {
                /** @var \Evocative\FilamentOpenAPI\FilamentOpenAPIPlugin */
                $oapiPlugin = $panel->getPlugin('filament-openapi');

                $oapiPlugin->route($panel);
            });
        }
    }
});
