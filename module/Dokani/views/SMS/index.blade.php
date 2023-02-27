@extends('layouts.master')


@section('title', 'Send SMS')

@section('page-header')
    <i class="fa fa-info-circle"></i> Send SMS
@stop

@push('style')

    <style>
        .phone_box{
            border: 1px solid #d9d9d9;
            padding: 10px;
        }

        .scroll_phone {
            height:220px;
            overflow-y: scroll;
        }


        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #ffffff;
        }

        ::-webkit-scrollbar-thumb {
            background: #c2d9ec;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b9d9f4;
        }

        ::-webkit-scrollbar {
            height: 4px;
            width: 6px;
            border: 1px solid #f1f1f1;
        }



        .chosen-container-multi .chosen-choices li.search-choice {
            width: 32% !important;
            border: none !important;
            border-radius: 0px !important;
            background-color: #dcdcdc !important;
            background-size: 100% 0px !important;
            color: #000 !important;
        }

        .chosen-container-multi .chosen-choices {
            background: white !important;
        }


    </style>

@endpush


@section('content')

    <div class="row">
        <div class="col-sm-12">

        @include('partials._alert_message')
        <!-- heading -->
            <div class="widget-box">
                <div class="widget-header">
                    <h3 class="widget-title">
                        @yield('page-header')
                    </h3>

                </div>

                <div class="space"></div>
                <div class="widget-body">
                    <div class="widget-main">

                        <form class="form-horizontal" id="companyForm" action="{{ route('dokani.sms.manage.send') }}"
                              method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Phone Numbers :
                                </label>
                                <div class="col-md-5 col-sm-5">

                                        <div class="phone_box scroll_phone">

                                            <select name="mobiles[]" class="form-control chosen-select-100-percent" multiple>
                                                @if($check == 1)
                                                    @foreach($mobiles as $item)
                                                        <option value="{{ '88'.$item }}" selected>{{ '88'.$item }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach($mobiles as $item)
                                                        <option value="{{ '88'.$item->mobile }}" selected>{{ '88'.$item->mobile }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Message<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <textarea class="form-control" name="message"
                                              placeholder="Message Box" required>{{ old('message') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="btn-group" style="display: flex; float: right !important; width: 30%;margin-right: 15px">
                                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-success" style="width: 50%; border-radius: 0px !important; background-color: #1B6AAA !important; border-color: #1B6AAA;" data-dismiss="modal">
                                        <i class="ace-icon fa fa-backward"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-primary"
                                            style="width: 50%; background-color: #629B58 !important; border-color: #629B58 !important; border-radius: 0px !important;">
                                        <i class="fa fa-send"></i> Send
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


@endsection

