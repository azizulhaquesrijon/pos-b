<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a trait with some default information';

    protected $type = 'Trait';

    protected function get_stub(){
        return __DIR__.'/stubs/trait.stub';
    }

    protected function getDefaultNamespace($rootNamespace){
        return $rootNamespace.'\Traits';
    }


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
    }
}
