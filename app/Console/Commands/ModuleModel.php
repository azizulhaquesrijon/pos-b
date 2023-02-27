<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ModuleModel extends Command
{

    protected $signature = 'module:model {name} {path?}';

    protected $description = 'Command description';

    public function handle()
    {

        $name = $this->argument('name');
        $path = $this->argument('path');


        if ($path == '') {
            $path = $this->ask('Module Name: ');
        }



        $stub = file_get_contents(base_path('app/Console/stubs/model.stub'));

        $stub = str_replace('Path', $path, $stub);
        $stub = str_replace('ModelName', $name, $stub);

        $dir = "module/$path/Models";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }


        $migration = $this->ask('Need Migration(Y/N): ');


        $this->info("Creating Module $name");

        file_put_contents("$dir/$name.php", $stub);


        $this->info("Created Module $name");




        // make migration
        if (strtolower($migration) == 'y' || strtolower($migration) == 'yes') {

            $migrationDir = "module/$path/database/migrations";

            if (!is_dir($migrationDir)) {
                mkdir($migrationDir, 0755, true);
            }

            $migrationStub = file_get_contents(base_path('app/Console/stubs/migration.stub'));

            $modelPulural = Str::plural($name);

            $tableName = Str::snake($modelPulural);

            $time = Carbon::parse(now())->format('Y_m_d_His');


            $migrationStub = str_replace('TableName', $tableName, $migrationStub);
            $migrationStub = str_replace('ClassName', "Create" . $modelPulural . "Table", $migrationStub);

            $filename = $time . '_create_' . Str::snake($tableName) . '_table.php';

            $this->info("Creating Migration $filename");

            file_put_contents("$migrationDir/$filename", $migrationStub);

            $this->info("Created Migration $filename");
        }
    }
}
