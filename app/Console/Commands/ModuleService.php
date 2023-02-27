<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ModuleService extends Command
{

    protected $signature = 'module:service {name} {module?}';

    protected $description = 'Make Service In Module';

    public function handle()
    {

        $name = $this->argument('name');
        $module = $this->argument('module') ?? 'Hospital';


        $namspace = $module . '\\' . $name;

        $array = explode('/', $name);

        $className = end($array);


        $stub = file_get_contents(base_path('app/Console/stubs/module/service.stub'));


        $stub = str_replace('#NAMESPACE', $namspace, $stub);
        $stub = str_replace('#CLASS_NAME', $className, $stub);


        $dir = "module/$namspace";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }


        file_put_contents("$dir\\$className.php", $stub);


        $this->info("$className Service is Created");
    }
}
