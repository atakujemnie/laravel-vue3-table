<?php

namespace Atakujemnie\LaravelVue3Table\Console\Commands;

use Illuminate\Console\Command;

class MakeTableCommand extends Command
{
    protected $signature = 'make:table {name} {model}';
    protected $description = 'Create a new table class';

    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->argument('model');
        $modelClass = last(explode('/', $model));

        $tableClassContent = <<<EOT
        <?php

        namespace App\Tables;

        use App\Models\$model;
        use Illuminate\Database\Eloquent\Builder;
        use Illuminate\Http\Request;
        use Atakujemnie\LaravelVue3Table\TableService;


        class $name extends TableService
        {
            protected function setModel(): void
            {
                \$this->model = new $modelClass();
            }
        }
        EOT;

        $filePath = app_path("Tables/{$name}.php");

        if (file_exists($filePath)) {
            $this->error("{$name} already exists!");
            return false;
        }

        if (!is_dir(app_path('Tables'))) {
            mkdir(app_path('Tables'), 0755, true);
        }

        file_put_contents($filePath, $tableClassContent);

        $this->info("{$name} created successfully.");
    }
}
