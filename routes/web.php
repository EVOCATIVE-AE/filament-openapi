<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    $panels = Filament::getPanels();

    foreach ($panels as $key => $panel) {
        Route::group([], function () use ($panel) {
            /** @var \Evocative\FilamentOpenAPI\FilamentOpenAPIPlugin */
            $oapiPlugin = $panel->getPlugin('filament-openapi');

            $oapiPlugin->route($panel);
        });
    }
});
