<?php


namespace App\Traits;


use Kreait\Firebase\Factory;

trait FirebaseConnection
{
    public function getConnection(){
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/Firebase-realtime.json');
        return $factory->createDatabase();

        // $factory = (new Factory)->withServiceAccount(__DIR__ . '/Firebase-realtime.json');
        // return $factory->createDatabase();
        // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/Firebase-realtime.json');
        // $firebase = (new Factory)
        //     ->withServiceAccount($serviceAccount)
        //     ->withDatabaseUri('https://dokani-2ba87.firebaseio.com')
        //     ->create();
    }
}
