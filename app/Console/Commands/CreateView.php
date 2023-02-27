<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateView extends Command
{
    private $database;


    protected $signature = 'module:views {name} {module?}';



    protected $description = 'Module View Creating Command';





    public function handle()
    {

        $name = $this->argument('name');

        $module = $this->argument('module');


        if ($module == '') {
            $module = $this->ask('Module Name: ');
        }


        $view_root = "module/$module/views/$name";

        $create_view = "$view_root/create.blade.php";

        if (!is_dir($view_root)) {

            mkdir($view_root, 0755, true);
        }

        if (!file_exists($create_view)) {
            $this->database = env('DB_DATABASE');
            $model_name = 'customers';
            $columns = DB::select("SELECT COLUMN_NAME, DATA_TYPE from INFORMATION_SCHEMA.COLUMNS where
            table_schema = '$this->database' and table_name = '$model_name'");

            $tableHead = "<tr>\n<td class='text-center'>SL</td>\n";
            $tableBody = '<td>{{ $loop->iteration }}</td>';
            foreach ($columns as $key => $item) {
                if ($item->DATA_TYPE != 'bigint') {
                    $name = str_replace('_', ' ', Str::ucfirst($item->COLUMN_NAME));
                    $tableHead .= "<td class='text-center'>" . $name . "</td>\n";
                    $tableBody .= '<td class="text-center">{{ $item->' . $item->COLUMN_NAME . ' }}</td>\n';
                }
            }
            $tableHead .= "<td class='text-center'>Actions</td>\n</tr>";

            $stub = file_get_contents(base_path('app/Console/stubs/views/index.stub'));
            $stub = str_replace('#tableHead', $tableHead, $stub);
            $stub = str_replace('#table_body', $tableBody, $stub);
            $stub = str_replace('_models_', '$' . $model_name, $stub);
            $stub = str_replace('#titlename', ucfirst($model_name), $stub);

            file_put_contents($create_view, $stub);
        }

        $this->info($tableHead);


        $this->info("Module View $name Created");
    }
}
