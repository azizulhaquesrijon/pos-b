<?php

namespace Module\Dokani\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Module\Dokani\Models\Product;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\ProductLedger;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\Unit;
use Module\Dokani\Services\Reports\ExportService;
use Module\Dokani\Services\Reports\InventoryReportService;

class InventoryController extends Controller
{


    public function index(Request $request)
    {
        $data['categories'] = Category::dokani()->pluck('name','id');
        $data['units']      = Unit::dokani()->pluck('name','id');
        $data['products']   = Product::dokani()->pluck('name','id');
        $data['reports']    = (new InventoryReportService())->inventory();


        if ($request->filled('export_type')) {
            $filename = 'Inventory ' . fdate(date('Y-m-d'), 'Y_m_d');
            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports/inventory', $data);
    }



    public function productLedger(Request $request)
    {

        $data['reports']    = (new InventoryReportService())->productLedger($request);
        $data['products']    = Product::dokani()->latest()->pluck('name','id');
        $data['units']    = Unit::dokani()->latest()->pluck('name','id');

        $data['grand_totals'] = ProductLedger::dokani()->with('product.stocks')->get();

        if ($request->filled('export_type')) {
            $filename = 'Product Ledger ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }


        return view('reports.product-ledger', $data);
    }


    public function alertInventory(Request $request)
    {
        $data['categories'] = Category::dokani()->pluck('name','id');
        $data['units'] = Unit::dokani()->pluck('name','id');
        $data['products'] = Product::dokani()->latest()->pluck('name','id');
        // $data['reports'] = (new InventoryReportService())->alertInventory();


        $data['reports'] = (new InventoryReportService())->alertInventory()->map(function ($item) {

            if ($item->available_qty <= $item->alert_qty) {
                return (object) [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'available_qty' => $item->available_qty ?? 0,
                    'alert_qty'     => $item->alert_qty,
                    'status'        => 'Low Qty',
                ];
            }
        })->filter(function ($item) {
            return $item !== null;
        });


        if ($request->filled('export_type')) {
            $filename = 'Alert Inventory ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }


        return view('reports.alert-inventory', $data);
    }





}
