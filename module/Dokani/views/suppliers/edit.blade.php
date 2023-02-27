@extends('layouts.master')

@section('title', 'Edit Supplier')

@section('page-header')
    <i class="fa fa-edit"></i> Edit Supplier
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
                        @if (hasPermission('dokani.suppliers.index', $slugs))
                            <a href="{{ route('dokani.suppliers.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Supplier List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.suppliers.update', $supplier->id) }}"
                            class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')


                            <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Name<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="name" autocomplete="off"
                                        value="{{ old('name', $supplier->name) }}" placeholder="Type Name" required />
                                </div>
                            </div>





                            <!-- Mobile -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Mobile :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control only-number" type="text" name="mobile"
                                        value="{{ old('mobile', $supplier->mobile) }}" placeholder="Enter Mobile" />
                                </div>
                            </div>




                            <!-- Address -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Address :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="address"
                                        value="{{ old('address', $supplier->address) }}" placeholder="Type address" />
                                </div>
                            </div>





                            <!-- Opening Due -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Opening Due :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="opening_due"
                                        value="{{ old('opening_due', $supplier->opening_due) }}"
                                        placeholder="Opening Due" readonly/>
                                </div>
                            </div>
                                <input type="hidden" name="balance" value="{{ $supplier->balance }}">




                            <!-- Image -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Image :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input type="file" name="image" style="width:100% !important" id="id-input-file-3"
                                        class="form-control" />
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


@endsection
