<?php

namespace App\Traits;

trait Trycatch
{

    public function load($data)
    {
        try {
            return response()->json([
                'data'      => $data,
                'message'   => 'Success',
                'status'    => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => 'Server Error',
                'status'    => true,
            ]);
        }
    }
}
