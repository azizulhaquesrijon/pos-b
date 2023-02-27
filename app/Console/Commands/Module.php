<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class Module extends Command
{
  
    

    protected $signature = 'make:module {name}';

    

    protected $description = 'Module Creating Command';

    
    
   

    public function handle()
    {

        $name = $this->argument('name');

        if (!is_dir('module')) {
            mkdir('module', 0755, true);
        }

        $module = "module/$name";

        if (!is_dir($module)) {
            
            mkdir($module, 0755, true);
        }

        if (!is_dir("$module/Controllers")) {

            mkdir("$module/Controllers", 0755, true);
        }

        if (!is_dir("$module/database")) {

            mkdir("$module/database", 0755, true);
        }

        if (!is_dir("$module/Models")) {

            mkdir("$module/Models", 0755, true);
        }

        if (!is_dir("$module/Providers")) {

            mkdir("$module/Providers", 0755, true);
        }

        if (!is_dir("$module/Requests")) {

            mkdir("$module/Requests", 0755, true);
        }

        if (!is_dir("$module/routes")) {

            mkdir("$module/routes", 0755, true);
        }

        if (!is_dir("$module/Services")) {

            mkdir("$module/Services", 0755, true);
        }

        $sidebar_name = Str::snake($module);

        if (!is_dir("$module/views/partials/sidebars")) {

            mkdir("$module/views/partials/sidebars", 0755, true);
        }



        $this->info("Module $name Created");

        // $path = $this->argument('path');
    
        
        // if($path == '') {
        //     $path = $this->ask('Module Name: ');
        // }



        // $stub = file_get_contents(base_path('app/Console/stubs/model.stub'));

        // $stub = str_replace('Path', $path, $stub);
        // $stub = str_replace('ModelName', $name, $stub);

        
        // $dir = "module/$path/Models";

        // if (!is_dir($dir)) {
        //     mkdir($dir, 0755, true);
        // }


        // $migration = $this->ask('Need Migration(Y/N): ');

        
        // $this->info("Creating Module $name");

        // file_put_contents("$dir/$name.php", $stub);


        // $this->info("Created Module $name");




        // // make migration
        // if(strtolower($migration) == 'y' || strtolower($migration) == 'yes') {



        //     $migrationDir = "module/$path/database/migrations";


        //     if (!is_dir($migrationDir)) {
        //         mkdir($migrationDir, 0755, true);
        //     }

        //     $migrationStub = file_get_contents(base_path('app/Console/stubs/migration.stub'));
            
        //     $modelPulural = str_plural($name);

        //     $tableName = snake_case($modelPulural);

        //     $time = Carbon::parse(now())->format('Y_m_d_u');


        //     $migrationStub = str_replace('TableName', $tableName, $migrationStub);
        //     $migrationStub = str_replace('ClassName', "Create" . $modelPulural . "Table", $migrationStub);

        //     $filename = $time . '_create_' . snake_case($tableName) . '_table.php';

        //     $this->info("Creating Migration $filename");

        //     file_put_contents("$migrationDir/$filename", $migrationStub);

        //     $this->info("Created Migration $filename");
        // }


    }
}
