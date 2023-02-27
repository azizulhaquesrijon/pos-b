<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = [];


    public function scopeDokani($query)
    {

        return $query->where('dokan_id', auth()->user()->type == 'owner' ? auth()->id() : auth()->user()->dokan_id);
    }



    public function scopeSearchByField($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, request()->$filed_name);
        });
    }


    
    public function scopeSearchByBranch($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->when(request()->$filed_name == 'Main', function ($qr) use ($filed_name) {
                $qr->where($filed_name, NULL);
            })->when(request()->$filed_name != 'Main', function ($qr) use ($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
            });
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


    public function scopeLikeSearch($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, 'like', '%' . request()->$filed_name . '%');
        });
    }



    public function scopeDateFilter($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function ($qr) use ($filed_name) {
            $qr->where($filed_name, '>=', (request('from') ?? request('from_date')));
        })
        ->when(request()->filled('to') | request()->filled('to_date'), function ($qr) use ($filed_name) {
            $qr->where($filed_name, '<=', (request('to') ?? request('to_date')));
        });
    }


    public function scopeDateFilterGroupBy($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function ($qr) use ($filed_name) {
            $qr->where($filed_name, '>=', (request('from') ?? request('from_date')))->groupBy('date')
                ->selectRaw('date,SUM(payable_amount) as payable_amount, SUM(total_vat) as total_vat');
        })
            ->when(request()->filled('to') | request()->filled('to_date'), function ($qr) use ($filed_name) {
                $qr->where($filed_name, '<=', (request('to') ?? request('to_date')));
            });
    }





    public function scopeFilterDate($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function ($qr) use ($filed_name) {
            $qr->where($filed_name, '>=', (request('from') ?? request('from_date')));
        })
            ->when(request()->filled('to') | request()->filled('to_date'), function ($qr) use ($filed_name) {
                $qr->where($filed_name, '<=', (request('to') ?? request('to_date')));
            });
    }


    public function scopeSearchDateFrom($query, $filed_name, $from = null)
    {
        if ($from == null) {
            $from = 'from';
        }

        $query->when(request()->filled($from), function ($qr) use ($filed_name, $from) {
            $qr->where($filed_name, '>=', request()->$from);
        });
    }


    public function scopeSearchDateTo($query, $filed_name, $to = null)
    {
        if ($to == null) {
            $to = 'to';
        }

        $query->when(request()->filled($to), function ($qr) use ($filed_name, $to) {
            $qr->where($filed_name, '<=', request()->$to);
        });
    }


    public function scopeSearchFromRelation($query, $relation, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($relation, $filed_name) {
            $qr->whereHas($relation, function ($q) use ($filed_name) {
                $q->where($filed_name, request()->$filed_name);
            });
        });
    }


    public function scopeSearchCount($query, $relation, $filed_name, $request_data = null)
    {
        $request_data = $request_data ?? $filed_name;

        $query->withCount([$relation => function ($q) use ($filed_name, $request_data) {
            $q->where($filed_name, request()->$request_data);
        }]);
    }

    public function scopeSortby($query)
    {
        $query->when(request()->filled('sort_by_key'), function ($query) {

            $order_by = 'asc';

            if (strpos(request()->sort_by_key, 'desc') !== false) {
                $order_by = 'desc';
            }

            $filed_name = str_replace('_desc', '', request()->sort_by_key);

            return $query->orderBy($filed_name, $order_by);
        });
    }


    public function scopeSearchByIds($query, $filed_name, $filter = null)
    {
        $filter = $filter ? $filter : $filed_name;

        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name, $filter) {

            if (request()->$filter) {
                $ids = request()->$filter;
                $qr->whereIn($filed_name, $ids);
            } else {
                $qr->where($filed_name, request()->$filed_name);
            }
        });
    }




    // public function image($query = null)
    // {
    //     // if (file_exists($query->image)) {
    //     //     return '<img class="rounded" width="100" height="100" src="' . asset($query->image) . '" alt="">';
    //     // }
    //     return '<img class="rounded" src="' . asset('assets/images/avatars/user.jpg') . '" alt="">';
    // }
}
