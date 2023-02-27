@extends('layouts.master')

@section('title', 'Edit Customer')

@section('page-header')
    <i class="fa fa-edit"></i> Edit Customer
@stop

@push('style')

    <style>



    </style>

@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.customers.index') }}" class="">
                            <i class="fa fa-list-alt"></i> Customer List
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.customers.update', $customer->id) }}"
                            class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')





                            <!-- IS Customer  -->
                                <div class="form-group" id="refer_by">
                                    <label class="control-label col-sm-3 col-sm-3" for="sales_price"></label>
                                    <div class="col-md-5 col-sm-5">
                                        <label>
                                            <input name="is_customer" value="1" type="radio" class="ace" {{ $customer->is_customer == 1 ? 'checked' : '' }}>
                                            <span class="lbl"> Customer</span>
                                        </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input value="0" name="is_customer" type="radio" class="ace" {{ $customer->is_customer == 0 ? 'checked' : '' }}>
                                            <span class="lbl"> Target Client</span>
                                        </label>
                                    </div>
                                </div>

                            <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Name<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="name" autocomplete="off"
                                        value="{{ old('name', $customer->name) }}" placeholder="Type Name" required />
                                </div>
                            </div>





                            <!-- Mobile -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Mobile :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control only-number" type="text" name="mobile"
                                        value="{{ old('mobile', $customer->mobile) }}" placeholder="Enter Mobile" />
                                </div>
                            </div>




                            <!-- Address -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Address :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="address"
                                        value="{{ old('address', $customer->address) }}" placeholder="Type address" />
                                </div>
                            </div>

                                <input type="hidden" name="balance" value="{{ $customer->balance }}">





                            <!-- Opening Due -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Opening Due :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="opening_due"
                                        value="{{ old('opening_due', $customer->opening_due) }}"
                                        placeholder="Opening Due" readonly/>
                                </div>
                            </div>

                            <!-- Customer Discount -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Customer Discount :</label>
                                <div class="col-md-3 col-sm-3">
                                    <input class="form-control" type="number" name="discount" value="{{ old('discount', $customer->discount) }}" placeholder="Customer Discount" />
                                </div>
                                <span class="col-md-3" style="height: 32px;width: 23px;padding: 1px;background: #d5d5d5;font-size: 20px;text-align: center;margin-left: -36px;text-align: center;margin-top: 1px;">%</span>
                            </div>



                            @if($customer->refer_by_user_id != null || $customer->refer_by_customer_id != null)
                                <!-- Refer By  -->
                                <div class="form-group" id="refer_by">
                                    <label class="control-label col-sm-3 col-sm-3" for="sales_price">Refer By :</label>
                                    <div class="col-md-5 col-sm-5">
                                        @if($customer->refer_by_user_id != null && $customer->refer_by_customer_id == null)
                                        <label>
                                            <input name="refer_by" value="user" type="radio" class="ace refer" checked>
                                            <span class="lbl"> User</span>
                                        </label>
                                        @elseif($customer->refer_by_user_id == null && $customer->refer_by_customer_id != null)

                                        <label>
                                            <input value="customer" name="refer_by" type="radio" class="ace refer" checked>
                                            <span class="lbl"> Customer</span>
                                        </label>
                                        @endif
                                    </div>
                                </div>
                            @endif


                            @if($customer->refer_by_user_id != null && $customer->refer_by_customer_id == null)
                                <!-- Refer User -->
                                <div class="form-group" id="user_refer" style="display: block">
                                    <label class="control-label col-sm-3 col-sm-3" for="sales_price">Refer User :</label>
                                    <div class="col-md-5 col-sm-5">
                                        <select name="refer_by_user_id" id="refer_by_user_id" class="form-control chosen-select"
                                                data-placeholder="-- Select Refer User --">
                                            <option value=""></option>
                                            @foreach($users as $item)
                                                <option value="{{ $item->id }}" {{ $customer->refer_by_user_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @elseif($customer->refer_by_user_id == null && $customer->refer_by_customer_id != null)

                                <!-- Refer Customer -->
                                <div class="form-group" id="customer_refer" style="display: block">
                                    <label class="control-label col-sm-3 col-sm-3" for="sales_price">Refer Customer :</label>
                                    <div class="col-md-5 col-sm-5">
                                        <select name="refer_by_customer_id" id="refer_by_customer_id" class="form-control chosen-select"
                                                data-placeholder="-- Select Refer Customer --">
                                            <option value=""></option>
                                            @foreach($customers as $item)
                                                <option value="{{ $item->id }}" {{ $customer->refer_by_customer_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif


                            @if($areas->count() > 0)
                                <!-- Area -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3" for="sales_price">Area :</label>
                                        <div class="col-md-5 col-sm-5">
                                            <select name="cus_area_id" id="" class="form-control chosen-select"
                                                    data-placeholder="-- Select Area --">
                                                <option value=""></option>
                                                @foreach($areas as $item)
                                                    <option value="{{ $item->id }}" {{ $customer->cus_area_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            @endif




                            @if($categories->count() > 0)
                                <!-- Area -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3" for="sales_price">Category :</label>
                                        <div class="col-md-5 col-sm-5">
                                            <select name="cus_category_id" id="" class="form-control chosen-select"
                                                    data-placeholder="-- Select Category --">
                                                <option value=""></option>
                                                @foreach($categories as $item)
                                                    <option value="{{ $item->id }}" {{ $customer->cus_category_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            @endif





                            <!-- Image -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Image :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input type="file" name="image" style="width:100% !important" id="id-input-file-3"
                                        class="form-control" />
                                </div>
                            </div>




                                <!-- Remark -->
                                <div class="form-group">
                                    <label class="control-label col-sm-3 col-sm-3">Remark :</label>
                                    <div class="col-md-5 col-sm-5">
                                        <textarea class="form-control" type="text" name="remark"
                                                  placeholder="Type Remark">{{ old('remark', $customer->remark) }}</textarea>
                                    </div>
                                </div>









                            <!-- Action -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-primary col-md-12">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('js')


    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>


    <script>

        $('#refer_by').change(function(){

            let value = $("input[type=radio][name=refer_by]:checked").val()

            if (value == 'user'){
                $('#user_refer').show()
                $('#customer_refer').hide()
            }
            else if (value == 'customer') {
                $('#user_refer').hide()
                $('#customer_refer').show()
            }
        });
    </script>

@endsection
