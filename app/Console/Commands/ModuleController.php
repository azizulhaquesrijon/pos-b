<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ModuleController extends Command
{


    protected $signature = 'module:controller {name} {path?}';




    public function handle()
    {

        $name      = $this->argument('name');
        $path      = $this->argument('path');
        $extraPath = '';
        $namespace = '';

        if ($path == '') {
            $path = $this->ask('Module Name: ');
        }


        $controller = file_get_contents(base_path('app/Console/stubs/controller.stub'));

        $additionalPath = explode("/",$name);

        if(sizeof($additionalPath) > 1){
            $name           = end($additionalPath);
            array_pop($additionalPath);

            foreach($additionalPath as $addiPath){
                $extraPath = $extraPath.'/'.$addiPath;
                $namespace = $namespace."\\".$addiPath;

            }
        }

        $controller     = str_replace('DummyClass', $name, $controller);
        $controller     = str_replace('ModuleName', $path, $controller);
        $controller     = str_replace('\AdditionalPath', $namespace, $controller);

        if($extraPath != ''){
            file_put_contents("module/$path/Controllers" .$extraPath.'/'. $name . '.php', $controller);
        }else{
            file_put_contents("module/$path/Controllers/" . $name . '.php', $controller);
        }

        $this->info('Controller created success !');
    }
}
