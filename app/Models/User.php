<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Module\Dokani\Models\ManageSmsApi;
use Module\Dokani\Models\Package;
use Module\Permission\Models\Permission;
use Module\Dokani\Models\BankInformation;
use Module\Dokani\Models\BusinessSetting;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Module\Dokani\Models\Branch;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function scopeDokani($query)
    {
        if (auth()->id() == 1) {
            return;
        }
        return $query->where('id', auth()->user()->type == 'owner' ? auth()->id() : auth()->user()->dokan_id);
    }

    public function businessProfile()
    {
        return $this->hasOne(BusinessSetting::class);
    }


    public function package()
    {
        return $this->hasOne(Package::class);
    }


    public function smsApi()
    {
        return $this->hasOne(ManageSmsApi::class,'dokan_id','id');
    }

    public function businessProfileByUser()
    {
        return $this->belongsTo(BusinessSetting::class, 'dokan_id','user_id');
    }

    // public function businessInfo()
    // {
    //     BusinessSetting::where('id', auth()->user()->type == 'owner' ? auth()->id() : auth()->user()->dokan_id)->first();
    //     return $this->belongsTo(BusinessSetting::class, 'dokan_id','user_id');
    // }



    public function bankAccount()
    {
        return $this->hasOne(BankInformation::class);
    }



    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->where('status', 1);
    }



    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }




    public function scopeSearchByField($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, request()->$filed_name);
        });
    }

    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {

            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
            });
        }

    }
}
