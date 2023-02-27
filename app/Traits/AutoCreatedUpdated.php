<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\App;

trait AutoCreatedUpdated
{

    public static function boot()
    {
        parent::boot();

        if (!App::runningInConsole()) {
            /**
             * Auto create created_by field when create anything through model
             */
            static::creating(function ($model) {
                $model->fill([
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'dokan_id'   => auth()->user()->type == 'owner' ? auth()->id() : auth()->user()->dokan_id,
                ]);
            });

            /**
             * Auto update updated_by field when update anything of the model data
             */
            static::updating(function ($model) {
                $model->fill([
                    'updated_by' => auth()->id()
                ]);
            });
        }
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }



    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
