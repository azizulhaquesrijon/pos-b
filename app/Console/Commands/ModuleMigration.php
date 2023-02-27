<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ModuleMigration extends Command
{
    protected $signature = 'module:migration {name} {path?}';



    protected $description = 'Command description';





    public function handle()
    {

        $name = $this->argument('name');
        $path = $this->argument('path');


        if ($path == '') {
            $path = $this->ask('Module Name: ');
        }

        // make migration

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
