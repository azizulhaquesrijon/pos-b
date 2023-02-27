@extends('layouts.master')

@section('title', 'Account')


@section('css')

    <style>
        .balance_edit{

            display: block;
            background: #eaffff;
            padding: 2px;
            border-radius: 6px;
            font-size: 16px;
            border: 2px solid #d7d7d7;
            color: #125ca7;
            font-weight: bold;
        }
    </style>

@endsection

@section('content')


    <div class="row">


        <div class="col-sm-12">
            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title">
                        <i class="fa fa-info-circle"></i> Account
                    </h4>

                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.ac.accounts.create', $slugs))
                            <a href="{{ route('dokani.ac.accounts.create') }}">
                                <i class="ace-icon fa fa-plus"></i>
                                Create New
                            </a>
                        @endif
                    </span>
                </div>


                <div class="widget-body">
                    <div class="widget-main">


                        @include('partials._alert_message')

                        <div class="row">



                            <div class="col-sm-12">

                                <table class="table table-striped table-bordered" id="data-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        <th>Account Name</th>
                                        <th>Opening Balance</th>
                                        <th>Balance</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    @forelse ($accounts as $key => $item)

                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                {{ $item->opening_balance }}
                                            </td>
                                            <td>{{ $item->balance }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-corner">


                                                    <!-- edit -->
                                                    @if (hasPermission('dokani.ac.accounts.edit', $slugs))
                                                        <a href="{{ route('dokani.ac.accounts.edit', $item->id) }}"
                                                           role="button" class="btn btn-sm btn-success" title="Edit">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                        </a>
                                                    @endif

                                                <!-- delete -->
                                                    @if (hasPermission('dokani.ac.accounts.delete', $slugs))
                                                        <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.ac.accounts.destroy', $item->id) }}`)"
                                                                class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="30" class="text-center text-danger py-3"
                                                style="background: #eaf4fa80 !important; font-size: 18px">
                                                <strong>No records found!</strong>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('js')


    <!-- Datatable Script -->
    <script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/custom_js/custom-datatable.js') }}"></script>

    <script>

        $('.opening_balance-text').click(function() {

            let root = $(this).closest('td')

            root.find('.opening_balance-input').attr('type', 'text').focus()

            $(this).css({"display" : "none"})
        });

        $('.opening_balance-input').blur(function() {

            let root = $(this).closest('td')

            let old_value = root.find('.opening_balance-text').text()
            let new_value = $(this).val()

            if(old_value != new_value) {
                root.find('form').submit()
            } else {
                root.find('.opening_balance-text').css({"display" : "block"})
                $(this).attr('type', 'hidden')
            }

        })

    </script>

@stop

