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
        $path = $this->option('path') ?: resource_path('js/components');
        $sourcePath = __DIR__ . '/../../resources/js/components/ApiTable.vue';
        $destinationPath = $path . '/ApiTable.vue';

        if (!File::exists($sourcePath)) {
            $this->error("Source component does not exist.");
            return;
        }

        if (File::exists($destinationPath)) {
            $this->error("Component already exists at the destination.");
            return;
        }

        File::copy($sourcePath, $destinationPath);

        $this->info("Component copied to: {$destinationPath}");
    }
}
