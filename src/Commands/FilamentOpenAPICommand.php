<?php

namespace Evocative\FilamentOpenAPI\Commands;

use Illuminate\Console\Command;

class FilamentOpenAPICommand extends Command
{
    public $signature = 'filament-openapi';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
