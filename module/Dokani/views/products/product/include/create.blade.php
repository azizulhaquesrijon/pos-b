
<form method="POST" action="{{ route('dokani.products.store') }}" class="form-horizontal"
    enctype="multipart/form-data">
    @csrf

    <div class="row mt-3">

        <!-- Right Side -->
        <div class="col-sm-6">





            <!-- Name -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">
                    Name<sup class="text-danger">*</sup> :
                </label>
                <div class="col-md-9 col-sm-9">
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                        placeholder="Type Product Name" required />
                </div>
            </div>




            <!-- Category -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">
                    Category :
                </label>
                <div class="col-md-9 col-sm-9">
                    <select name="category_id" class="form-control select2"
                        data-placeholder="--Select Category--">
                        <option value=""></option>
                        @foreach ($categories as $key => $item)
                            <option value="{{ $key }}" {{ $key == old('category_id') ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>



            <!-- Unit -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">
                    Unit<sup class="text-danger">*</sup> :
                </label>
                <div class="col-md-9 col-sm-9">
                    <select name="unit_id" class="form-control select2" data-placeholder="--Select Unit--" required>
                        <option value=""></option>
                        @foreach ($units as $key => $item)
                            <option value="{{ $key }}" {{ $key == old('unit_id') ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>





            <!-- Barcode -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">Barcode<sup class="text-danger">*</sup> :</label>
                <div class="col-md-9 col-sm-9">
                    <div class="input-group">
                        <input type="text" id="product_barcode" class="form-control" name="barcode"
                            value="{{ old('barcode') }}" placeholder="Enter Barcode " required>
                        <span class="input-group-addon" id="barcode_generate">
                            <i class="fa fa-barcode"></i>
                        </span>
                    </div>
                </div>
            </div>




            <!-- Brand -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">
                    Brand :
                </label>
                <div class="col-md-9 col-sm-9">
                    <select name="brand_id" class="form-control select2" data-placeholder="--Select Brand--">
                        <option></option>
                        @foreach ($brands as $key => $item)
                            <option value="{{ $key }}" {{ $key == old('brand_id') ? 'selected' : '' }}>
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
                    <input class="form-control" type="text" name="description" value="{{ old('description') }}"
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
                        <input class="form-check-input" type="radio" name="product_type" value="1" id="finish" required>
                        <label class="form-check-label" for="finish">Finish</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="product_type" value="2" id="raw_material" required>
                        <label class="form-check-label" for="raw_material">Raw Material</label>
                      </div>

                </div>
            </div>




            <!-- Image -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">Image<small>(300X200)</small>
                    :</label>
                <div class="col-md-9 col-sm-9">
                    <input type="file" name="image" class="ace-file-upload" onchange="readURL(this)"/>
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
                    <input type="text" name="purchase_price" class="form-control only-number"
                        placeholder="Type Purchase Price">
                </div>
            </div>



            <!-- Sell Price -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">
                    Sell Price<sup class="text-danger">*</sup> :
                </label>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="sell_price" class="form-control only-number" placeholder="Type Sell Price">

                </div>
            </div>



            <!-- Opening Qty -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">Opening Qty :</label>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="opening_stock" value="0" class="form-control opening_stock only-number"
                        placeholder="Type Opening Qty">

                </div>
            </div>


            <!-- Expiry Date -->
            <div class="form-group expiry" style="display:none;">
                <label class="control-label col-sm-3 col-sm-3">Expiry Date :</label>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="expiry_at" class="form-control date-picker" id="date"
                           placeholder="Expiry Date" autocomplete="off">

                </div>
            </div>



            <!-- Alert Qty -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">Alert Qty :</label>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="alert_qty" value="0" class="form-control only-number" placeholder="Type Alert Qty">

                </div>
            </div>



            <!-- VAT -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">VAT % :</label>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="vat" class="form-control only-number" placeholder="Type Vat" value="0">

                </div>
            </div>






            <!-- Image -->
            <div class="form-group">
                <label class="control-label col-sm-3 col-sm-3">Preview Image :</label>
                <div class="col-md-9 col-sm-9">
                    <img class="img-thumbnail" id="blah" width="220" src="{{ asset('assets/images/default.png') }}">
                </div>
            </div>





        </div>

    </div>

    <!-- Action -->
    <div class="form-group">
        <div class="text-center col-md-2 col-md-offset-10 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add New
            </button>
        </div>
    </div>
</form>
