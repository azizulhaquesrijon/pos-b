@extends('layouts.master')

@section('title', 'Edit Product')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Edit Product
@stop



@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.products.index', $slugs))
                            <a href="{{ route('dokani.products.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Product List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.products.update', $product->id) }}"
                            class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mt-3">

                                <!-- Right Side -->
                                <div class="col-sm-6">





                                    <!-- Name -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Name<sup class="text-danger">*</sup> :
                                        </label>
                                        <div class="col-md-9 col-sm-9">
                                            <input class="form-control" type="text" name="name"
                                                value="{{ old('name', $product->name) }}" placeholder="Type Product Name"
                                                required />
                                        </div>
                                    </div>




                                    <!-- Category -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Category :
                                        </label>
                                        <div class="col-md-9 col-sm-9">
                                            <select name="category_id" class="form-control chosen-select"
                                                data-placeholder="--Select Category--">
                                                <option value=""></option>
                                                @foreach ($categories as $key => $item)
                                                    <option value="{{ $key }}"
                                                        {{ $key == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <!-- Unit -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Unit :
                                        </label>
                                        <div class="col-md-9 col-sm-9">
                                            <select name="unit_id" class="form-control chosen-select"
                                                data-placeholder="--Select Unit--">
                                                <option value=""></option>
                                                @foreach ($units as $key => $item)
                                                    <option value="{{ $key }}"
                                                        {{ $key == old('unit_id', $product->unit_id) ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>





                                    <!-- Barcode -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">Barcode :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <div class="input-group">
                                                <input type="text" id="product_barcode" class="form-control"
                                                    name="barcode" value="{{ old('barcode', $product->barcode) }}"
                                                    placeholder="Enter Barcode ">
                                                <span class="input-group-addon" id="barcode_generate">
                                                    <i class="fa fa-barcode"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Brand :
                                        </label>
                                        <div class="col-md-9 col-sm-9">
                                            <select name="brand_id" class="form-control chosen-select" data-placeholder="--Select Brand--">
                                                <option></option>
                                                @foreach ($brands as $key => $item)
                                                    <option value="{{ $key }}" {{ $key == $product->brand_id ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>




                                    <!-- Description -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Description :
                                        </label>
                                        <div class="col-md-9 col-sm-9">
                                            <input class="form-control" type="text" name="description" value="{{ old('description',$product->description) }}"
                                                   placeholder="Type Product Description"/>
                                        </div>
                                    </div>




                                    <!-- Product Type -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Product Type<sup class="text-danger">*</sup> :
                                        </label>
                                        <div class="col-md-9 col-sm-9" style="display: flex;justify-content: space-evenly">

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="product_type" value="1" id="finish" {{ $product->product_type == 1 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="finish">Finish</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="product_type" value="2" id="raw_material" {{ $product->product_type == 2 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="raw_material">Raw Material</label>
                                            </div>

                                        </div>
                                    </div>






                                    <!-- Image -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">Image<small>(300X200)</small>
                                            :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="file" name="image" class="ace-file-upload"
                                                onchange="readURL(this)" />
                                        </div>
                                    </div>





                                </div>

                                <!-- Left Side -->
                                <div class="col-sm-6">



                                    <!-- Purchase Price -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Buy Price<sup class="text-danger">*</sup> :
                                        </label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" name="purchase_price"
                                                value="{{ old('purchase_price', $product->purchase_price) }}"
                                                class="form-control only-number" placeholder="Type Purchase Price">
                                        </div>
                                    </div>



                                    <!-- Sell Price -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">
                                            Sell Price<sup class="text-danger">*</sup> :
                                        </label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" name="sell_price"
                                                value="{{ old('sell_price', $product->sell_price) }}"
                                                class="form-control only-number" placeholder="Type Sell Price">

                                        </div>
                                    </div>



                                    <!-- Opening Qty -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">Opening Qty :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" value="{{ $product->opening_stock }}"
                                                class="form-control only-number" placeholder="Type Opening Qty" name="opening_stock" readonly>

                                        </div>
                                    </div>

                                    <!-- Expiry Date -->
                                    @if($product->opening_stock > 0)
                                        <div class="form-group expiry">
                                            <label class="control-label col-sm-3 col-sm-3">Expiry Date :</label>
                                            <div class="col-md-9 col-sm-9">
                                                <input type="text" name="expiry_at" class="form-control date-picker" id="date" readonly
                                                       placeholder="Expiry Date" value="{{ optional($product->stocks)->expiry_at }}" autocomplete="off">

                                            </div>
                                        </div>
                                    @endif


                                    <!-- Alert Qty -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">Alert Qty :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" name="alert_qty"
                                                value="{{ old('alert_qty', $product->alert_qty) }}"
                                                class="form-control only-number" placeholder="Type Alert Qty">

                                        </div>
                                    </div>


                                    <!-- VAT -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">VAT :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" name="vat" class="form-control only-number" placeholder="Type Vat" value="{{ old('alert_qty', $product->vat) }}">

                                        </div>
                                    </div>



                                    <!-- Image -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 col-sm-3">Privew Image :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <img class="img-thumbnail" id="blah" width="220"
                                                src="{{ file_exists($product->image) ? asset($product->image) : asset('assets/images/default.png') }}">
                                        </div>
                                    </div>





                                </div>

                            </div>

                            <!-- Action -->
                            <div class="form-group">
                                <div class="text-center col-md-2 col-md-offset-10 mt-3">
                                    <button type="submit" class="btn btn-primary">
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
        function barcode_generate(length) {
            var result = '';
            var characters = '0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
        $('#barcode_generate').on('click', function() {
            $('#product_barcode').val(barcode_generate(5));
        })



        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".ace-file-upload").change(function() {
            readURL(this);
        });
    </script>
@endsection
