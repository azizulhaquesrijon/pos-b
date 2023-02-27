<?php

use App\Models\User;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Branch;
use Module\Dokani\Models\Account;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\AccountType;


function redirectIfError($error, $with_input = null)
{
    if (request()->dev == 1) {
        throw $error;
    }
    if ($with_input) {
        return redirect()->back()->withInput(request()->except('image'))->withError($error->getMessage());
    }
    return redirect()->back()->withError($error->getMessage());
}


function dokanId(){

    return auth()->user()->type == 'owner' ? auth()->id() : auth()->user()->dokan_id;
}


function account(){

    return Account::dokani()->pluck('name','id');
}

function accountInfo(){

    return Account::dokani()->get();
}

function cashBalance(){

    return Account::dokani()->where('name', 'Cash')->pluck('balance')->first();
}

function customerPoint(){

    return Customer::dokani()->where('is_default', 1)->pluck('point')->first();
}

function customerDue(){

    return Customer::dokani()->where('is_default', 1)->pluck('balance')->first();
}

function customerId(){

    return Customer::dokani()->where('is_default', 1)->pluck('id')->first();
}

function defaultCustomer(){

    return Customer::dokani()->where('is_default', 1)->first();
}




function hasBranching()
{
    return auth()->user()->is_multiple_branch ? true : false;
    return auth()->user()->is_multiple_branch || auth()->id() == 1 ? true : false;
}

function hasBranchSystem()
{
    if(auth()->user()->type == "owner"){
        return auth()->user()->is_multiple_branch ? true : false;
    }else{
        $user = User::find(auth()->user()->dokan_id);
        return $user->is_multiple_branch ? true : false;
    }
}



function dokanBranches()
{
    return Branch::dokani()->pluck('name', 'id');
}


function dokanBranchesAll()
{
    return Branch::dokani()->get(['id', 'dokan_id', 'name']);
}



function fdate($value, $format = null)
{
    if ($value == '') {
        return '';
    }

    if ($format == null) {
        $format = 'd/m/Y';
    }

    return \Carbon\Carbon::parse($value)->format($format);
}


function getInWord($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Taka = implode('', array_reverse($str));
    $poysa = ($decimal) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' poysa' : '';

    return ($Taka ? $Taka . 'taka ' : '') . $poysa . 'only' ;
}







function ProfitCalculate($date = null, $dranch = null){
    $grand_profit_total = 0;

    $reports = Sale::dokani()
            ->with('details', 'sale_return_exchange.sale_return', 'sale_return_exchange.sale_exchage')
            ->withCount(['details as total_qty' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->when($date, function($q) use($date){
                $q->where('date', $date);
            })
            ->where('branch_id', $dranch)
            ->latest()->get();

    foreach ($reports as $key => $item){
        $sale_cost = $total_return_price = $total_return_cost = $total_exchange_cost = $total_exchange_price = 0;
        foreach ($item->details as $detail){
            $sale_cost += $detail->buy_price * $detail->quantity;
        }
        $total_sale = $item->payable_amount - $item->total_vat ?? 0;

        foreach ($item->sale_return_exchange ?? [] as $key => $return_exchange) {
            $total_return_price += $return_exchange->return_grand_total ?? 0;
            $total_return_cost += $return_exchange->total_return_cost ?? 0;
            $total_exchange_cost += $return_exchange->total_exchange_cost ?? 0;
            $total_exchange_price += $return_exchange->exchange_subtotal ?? 0;
        }
        $total_sale_cost = number_format($sale_cost, 2, '.', '') - number_format($total_return_cost, 2, '.', '');
        $total_sale_price = number_format($total_sale, 2, '.', '') - number_format($total_return_price, 2, '.', '') ?? 0;

        $total_sale_profit = number_format($total_sale_price, 2, '.', '') - number_format($total_sale_cost, 2, '.', '') ?? 0;

        $total_exchange_profit = number_format($total_exchange_price, 2, '.', '') - number_format($total_exchange_cost, 2, '.', '') ?? 0;

        $total_profit = $total_sale_profit + $total_exchange_profit ?? 0;

        $grand_profit_total += $total_profit;
    }

    return $grand_profit_total;
}




