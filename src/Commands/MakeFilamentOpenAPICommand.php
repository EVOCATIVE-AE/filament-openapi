<?php

namespace Evocative\FilamentOpenAPI\Commands;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeFilamentOpenAPICommand extends Command
{
    use CanManipulateFiles;

    public $signature = 'make:filament-openapi
                        {--panel= : Filament panel name}
                        {--resource= : Filament resource name}';

    public $description = 'Create an OpenAPI Schema for your resource.';

    public function handle(): int
    {
        $stubs = [
            'Service' => [
                'target' => '{{ modelClass }}Service.php',
            ],
            'Handlers/IndexHandler' => [
                'target' => 'Handlers/Index{{ modelClass }}Handler.php',
            ],
            'Handlers/ShowHandler' => [
                'target' => 'Handlers/Show{{ modelClass }}Handler.php',
            ],
            'Handlers/CreateHandler' => [
                'target' => 'Handlers/Create{{ modelClass }}Handler.php',
            ],
            'Handlers/UpdateHandler' => [
                'target' => 'Handlers/Update{{ modelClass }}Handler.php',
            ],
            'Handlers/DeleteHandler' => [
                'target' => 'Handlers/Delete{{ modelClass }}Handler.php',
            ],
            'Transformers/Transformer' => [
                'target' => 'Transformers/{{ modelClass }}Transformer.php',
            ],
            'Validators/CreateValidator' => [
                'target' => 'Validators/Create{{ modelClass }}Validator.php',
            ],
            'Validators/UpdateValidator' => [
                'target' => 'Validators/Update{{ modelClass }}Validator.php',
            ],
        ];

        $panel = $this->option('panel');

        if ($panel && gettype($panel) !== 'string') {
            $this->error('The panel provided was not a string');

            return self::INVALID;
        }

        /** @var string $panel */
        if ($panel) {
            $panel = Filament::getPanel($panel)->getId();
        }

        if (! $panel) {
            $panels = Filament::getPanels();

            $panel = (count($panels) > 1) ?
                $panels[select(
                    label: 'For which panel would you like to create the OpenAPI schema?',
                    options: Arr::map(
                        $panels,
                        fn (Panel $panel): string => $panel->getId(),
                    ),
                    default: Filament::getDefaultPanel()->getId()
                )]->getId() :
                Arr::first($panels)->getId();
        }

        $resource = (string) Str::of($this->option('resource') ?? text(
            label: 'What is the name of the resource?',
            placeholder: 'UserResource',
            required: true,
        ))
            ->studly()
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');

        if (blank($resource)) {
            $resource = 'Resource';
        }

        $resourceNamespace = "App\\Filament\\Resources\\$resource";
        $resourceClass = Str::of($resourceNamespace)->afterLast('\\')->toString();
        $resource = new $resourceNamespace();

        $modelNamespace = $resource::getModel();
        $modelClass = Str::of($modelNamespace)->afterLast('\\')->toString();
        $modelClassPlural = Str::plural($modelClass);
        $model = new $modelNamespace();

        $columns = Schema::getColumnListing($model->getTable());
        $hidden = $model->getHidden();

        $shownColumns = array_diff($columns, array_intersect($hidden, $columns));

        $cols = collect($shownColumns)
            ->map(fn ($col) => '            "' . Str::of($col)->camel()->toString() . '"' . " => \$this->$col")
            ->join(",\n");

        foreach ($stubs as $stub => $values) {
            $replacements = [
                'resourceNamespace' => $resourceNamespace,
                'resourceClass' => $resourceClass,
                'modelNamespace' => $modelNamespace,
                'modelClass' => $modelClass,
                'modelClassPlural' => $modelClassPlural,
                'panel' => $panel,
                'cols' => $cols,
            ];

            $target = Str::of($values['target']);

            foreach ($replacements as $key => $replacement) {
                $target = $target->replace("{{ {$key} }}", $replacement);
            }

            $this->copyStubToApp(
                $stub,
                Str::of((new ReflectionClass($resourceNamespace))->getFileName())->beforeLast('.')->toString() . '/OpenAPI/' . $target->toString(),
                $replacements
            );
        }

        $this->comment("Created $resource OpenAPI files.");

        return self::SUCCESS;
    }
}
