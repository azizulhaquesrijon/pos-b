@extends('layouts.master')
@section('title', 'Invoice No: ' . $stockTransfer->invoice_no . ' Receive Tranfer')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <h4 class="pl-2"><i class="far fa-receipt"></i> @yield('title')</h4>

                <ul class="breadcrumb mb-1">
                    <li><a href="{{ route('home') }}"><i class="ace-icon far fa-home-lg-alt"></i></a></li>
                    <li><a class="text-muted" href="{{ route('inv.purchases.index') }}">Purchase</a></li>
                    <li>{{ $stockTransfer->invoice_no }}</li>
                </ul>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <!-- PURCHASE APPROVE FORM -->
                    <form class="form-horizontal" action="{{ route('inv.stock-transfer-receive', $stockTransfer->id) }}" onsubmit="return confirm('Are You Sure to Receive This Purchase?')" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
                                        Warehouse ( From )<span class="label-required"></span>
                                    </span>
                                    <select name="from_warehouse_id" id="from_warehouse_id" data-placeholder="- Select -" tabindex="2" class="form-control select2" style="width: 100%" required>
                                        <option></option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ $stockTransfer->from_warehouse_id ==  $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
                                        Warehouse ( To )<span class="label-required"> </span>
                                    </span>
                                    <select name="to_warehouse_id" id="to_warehouse_id" data-placeholder="- Select -" tabindex="2" class="form-control select2" style="width: 100%" required>
                                        <option></option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ $stockTransfer->to_warehouse_id ==  $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Date
                                    </span>
                                    <input type="text" name="date" id="date" tabindex="3" class="form-control" value="{{ $stockTransfer->date }}" autocomplete="off" data-date-format="yyyy-mm-dd" readonly>
                                </div>
                            </div>
                        </div>

                    
                        <!-- PURCHASE TABLE -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="purchaseTable">
                                        <thead>
                                            <tr class="table-header-bg">
                                                <th width="25%">Product</th>
                                                <th width="15%">Variation</th>
                                                <th width="10%" class="text-center">Transfered Qty</th>
                                                <th width="10%" class="text-center">Already Received Qty</th>
                                                <th width="10%" class="text-center">Receiveable Qty</th>
                                                <th width="10%" class="text-center">Receive Now</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stockTransfer->stock_transfer_details as $stockTransferDetail)
                                                @if(number_format($stockTransferDetail->approved_quantity - $stockTransferDetail->received_quantity, 2, '.', '') > 0)
                                                    <tr>
                                                        <input type="hidden" name="stock_transfer_detail_id[]" value="{{ $stockTransferDetail->id }}">
                                                        <th width="25%">
                                                            <input type="hidden" class="product_is_variation" value="{{ $stockTransferDetail->product_variation_id != null ? 'true' : 'false' }}">
                                                            <select  class="form-control products" data-placeholder="- Select -" readonly required>
                                                                <option value="{{ $stockTransferDetail->product_id }}">{{ optional($stockTransferDetail->product)->name }} - {{ optional($stockTransferDetail->product)->code }}</option>
                                                            </select>
                                                        </th>
                                                    
                                                        
                                                        <th width="15%">
                                                            <select class="form-control product-variations" data-placeholder="- Select -" readonly>
                                                                <option value="{{ $stockTransferDetail->product_variation_id }}">{{ optional($stockTransferDetail->product_variation)->name }}</option>
                                                            </select>
                                                        </th>
                                                    
                                                        <th width="10%">
                                                            <input type="number" class="form-control text-center quantity" value="{{ number_format($stockTransferDetail->approved_quantity, 2, '.', '') }}" autocomplete="off" readonly required>
                                                        </th>
                                                        <th width="10%">
                                                            <input type="number" class="form-control text-center quantity" value="{{ number_format($stockTransferDetail->received_quantity, 2, '.', '') }}" autocomplete="off" readonly required>
                                                        </th>
                                                        <th width="10%">
                                                            <input type="number" class="form-control text-center quantity" value="{{ number_format($stockTransferDetail->approved_quantity - $stockTransferDetail->received_quantity, 2, '.', '') }}" autocomplete="off" readonly required>
                                                        </th>
                                                        <th width="10%">
                                                            <input type="number" name="received_quantity[]" class="form-control text-center approved-quantity" value="{{ number_format($stockTransferDetail->approved_quantity - $stockTransferDetail->received_quantity, 2, '.', '') }}" autocomplete="off" onkeyup="totalQuantity()"  required>
                                                        </th>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            



                            <div class="col-md-9">
                                <input type="file" name="attachment" id="attachment"/>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group mb-1 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Total Amount
                                    </span>
                                    <input type="text" class="form-control text-right" value="{{ number_format($stockTransfer->total_amount, 2, '.', '') }}" readonly required>
                                </div>

                                <div class="input-group mb-1 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Total Quantity
                                    </span>
                                    <input type="text" class="form-control text-right total-quantity" name="total_quantity" id="total_quantity" value="{{ number_format($stockTransfer->total_quantity, 2, '.', '') }}" readonly required>
                                </div>

                                <div class="input-group mb-1 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Transfer Cost
                                    </span>
                                    <input type="text" class="form-control text-right" name="subtotal" id="subtotal" value="{{ number_format($stockTransfer->transfer_cost, 2, '.', '') }}" readonly required>
                                </div>
                           

                                <div class="btn-group width-100">
                                    <button class="btn btn-sm btn-success" style="width: 70%"> <i class="fad fa-thumbs-up"></i> RECEIVE </button>
                                    <a class="btn btn-sm btn-info" style="width: 29%" href="{{ route('inv.purchases.index') }}"> <i class="fa fa-bars"></i> LIST </a>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection





@section('script')
    @include('purchases._inc.script')

    <script>
        function totalQuantity(){
            let totalQuantity = 0
            $('.approved-quantity').each(function(){
                totalQuantity += Number($(this).val() ?? 0);

            })

            $('.total-quantity').val(totalQuantity);

        }

    </script>
@endsection