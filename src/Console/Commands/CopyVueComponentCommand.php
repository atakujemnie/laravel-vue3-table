<?php

namespace Atakujemnie\LaravelVue3Table\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CopyVueComponentCommand extends Command
{
    protected $signature = 'make:vue-component {--path= : The path to copy the component to}';

    protected $description = 'Copy the Vue.js component to a specific location';

    public function handle()
    {
        $baseSourcePath = __DIR__ . '/../../resources/js/LaravelVueTable';
        $baseDestinationPath = resource_path('js/components/LaravelVueTable');

        $componentsToCopy = [
            '/Table.vue',
            '/TableElements/TableSortIcon.vue'
        ];

        foreach ($componentsToCopy as $componentPath) {
            $sourcePath = $baseSourcePath . $componentPath;
            $destinationPath = $baseDestinationPath . $componentPath;

            if (!File::exists($sourcePath)) {
                $this->error("Source component does not exist: {$sourcePath}");
                continue;
            }

            if (!File::isDirectory(dirname($destinationPath))) {
                File::makeDirectory(dirname($destinationPath), 0755, true);
            }

            if (File::exists($destinationPath)) {
                $this->error("Component already exists at the destination: {$destinationPath}");
                continue;
            }

            File::copy($sourcePath, $destinationPath);
            $this->info("Component copied to: {$destinationPath}");
        }
    }
}
