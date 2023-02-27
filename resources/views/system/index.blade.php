
@extends('layouts.master')
@section('title','System Setting')
@section('page-header')
    <i class="fa fa-empire"></i> System Setting
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-timepicker.min.css') }}" />
    <style>
        .bg-dark{
            background-color: #ededed;
        }
    </style>

@stop


@section('content')


    @include('partials._alert_message')


    <div class="row mt-5">
        <form class="form-horizontal" action="{{ route('system-setting.store') }}" method="post">
            @csrf

            <div class="col-sm-8 col-sm-offset-2">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-dark">Title</th>
                        <th class="bg-dark">Value</th>
                    </tr>

                    @foreach($systemSettings as $key => $systemSetting)

                        <tr>
                            <th>
                                @if ($systemSetting->key == "general_store_reference_no_change")

                                    General Store (Reference) Title Change

                                @elseif ($systemSetting->key == "out_work_date_picker")

                                    OutWork DatePicker All Open

                                @elseif ($systemSetting->key == "employee_summary_gross_salary_get")

                                    Employee Summary Gross Salary Get

                                @elseif ($systemSetting->key == "finger_id_get")

                                    Finger Print Id Get Attendance Sheet

                                @elseif ($systemSetting->key == "employee_list_card_no")

                                    Set Employee List Card No

                                @elseif ($systemSetting->key == "custom_employee_full_id")

                                    Custom Employee Full Id

                                @elseif ($systemSetting->key == "employee_login_option")

                                    Admin & Employee Login Option

                                @elseif ($systemSetting->key == "employee_attendance_chart")

                                    Employee Attendance Chart


                                @elseif ($systemSetting->key == "line")

                                    Employee Line Number
                                
                                @elseif ($systemSetting->key == "line_caption")

                                    Employee Line Caption
                                
                                @elseif ($systemSetting->key == "employee_group")

                                    Employee Group
                                
                                @elseif ($systemSetting->key == "mfs")

                                    Empployee MFS
                                

                                @elseif ($systemSetting->key == "employee_signature")

                                    Employee Signature

                                @elseif ($systemSetting->key == "dashboard")

                                    Would you prefer which Dashboard?

                                @elseif ($systemSetting->key == "topbar_background_color")

                                    Topbar Background Color(color name/code)

                                @elseif ($systemSetting->key == "topbar_text_color")

                                    Topbar Text Color(color name/code)

                                @elseif ($systemSetting->key == "login_background_image")

                                    Login Background Image

                                @elseif ($systemSetting->key == "default_login_for")

                                    Default Login For

                                @elseif ($systemSetting->key == "employee_general_shift")

                                    Employee General Shift

                                @elseif ($systemSetting->key == "leave_recommender_required")

                                    Leave Recommender Required

                                @elseif ($systemSetting->key == "hierarchy_wise_employee_ordering")

                                    Employee Hierarchy Wise Ordering

                                @elseif ($systemSetting->key == "late_time_count_from")

                                    Late Time Count From

                                @elseif ($systemSetting->key == "employee_facilities")

                                    Employee Facility

                                @endif
                            </th>
                            <td>

                                @if ($systemSetting->key == "general_store_reference_no_change")

                                    <input type="text" class="form-control" name="key[general_store_reference_no_change]" value="{{ $systemSetting->value }}">

                                @elseif ($systemSetting->key == "out_work_date_picker")

                                   <label> <input type="radio" name="key[out_work_date_picker]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                   <label> <input type="radio" name="key[out_work_date_picker]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>

                                @elseif ($systemSetting->key == "employee_summary_gross_salary_get")

                                    <label><input type="radio" name="key[employee_summary_gross_salary_get]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_summary_gross_salary_get]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>

                                @elseif ($systemSetting->key == "finger_id_get")

                                    <label><input type="radio" name="key[finger_id_get]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[finger_id_get]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>

                                @elseif ($systemSetting->key == "employee_list_card_no")

                                    <label><input type="radio" name="key[employee_list_card_no]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_list_card_no]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>

                                @elseif ($systemSetting->key == "custom_employee_full_id")

                                    <label><input type="radio" name="key[custom_employee_full_id]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[custom_employee_full_id]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>

                                @elseif ($systemSetting->key == "employee_login_option")

                                    <label><input type="radio" name="key[employee_login_option]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_login_option]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>


                                @elseif ($systemSetting->key == "employee_attendance_chart")

                                    <label><input type="radio" name="key[employee_attendance_chart]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_attendance_chart]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>



                                @elseif ($systemSetting->key == "line")

                                    <label><input type="radio" name="key[line]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[line]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>

                                @elseif ($systemSetting->key == "line_caption")

                                    <input type="text" class="form-control" name="key[line_caption]" value="{{ $systemSetting->value }}">

                                @elseif ($systemSetting->key == "employee_group")

                                <label><input type="radio" name="key[employee_group]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                <label><input type="radio" name="key[employee_group]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>
                                
                                @elseif ($systemSetting->key == "mfs")

                                <label><input type="radio" name="key[mfs]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                <label><input type="radio" name="key[mfs]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>

                                @elseif ($systemSetting->key == "employee_signature")

                                    <label><input type="radio" name="key[employee_signature]" {{ $systemSetting->value == 1 ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_signature]" {{ $systemSetting->value == 0 ? "checked" : "" }} value="0"> No</label>


                                @elseif ($systemSetting->key == "dashboard")

                                    <label><input type="radio" name="key[dashboard]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> Default</label>
                                    <label><input type="radio" name="key[dashboard]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> Company Wise</label>


                                @elseif ($systemSetting->key == "topbar_background_color")

                                    <input type="text" class="form-control" name="key[topbar_background_color]" value="{{ $systemSetting->value }}">

                                @elseif ($systemSetting->key == "topbar_text_color")

                                    <input type="text" class="form-control" name="key[topbar_text_color]" value="{{ $systemSetting->value }}">


                                @elseif ($systemSetting->key == "login_background_image")

                                    <label><input type="radio" name="key[login_background_image]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[login_background_image]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> No</label>


                                @elseif ($systemSetting->key == "default_login_for")

                                    <label><input type="radio" name="key[default_login_for]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> Employee</label>
                                    <label><input type="radio" name="key[default_login_for]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> Admin</label>


                                @elseif ($systemSetting->key == "employee_general_shift")

                                    <label><input type="radio" name="key[employee_general_shift]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_general_shift]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> No</label>


                                @elseif ($systemSetting->key == "leave_recommender_required")

                                    <label><input type="radio" name="key[leave_recommender_required]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[leave_recommender_required]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> No</label>


                                @elseif ($systemSetting->key == "hierarchy_wise_employee_ordering")

                                    <label><input type="radio" name="key[hierarchy_wise_employee_ordering]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[hierarchy_wise_employee_ordering]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> No</label>



                                @elseif ($systemSetting->key == "late_time_count_from")

                                    <label><input type="radio" name="key[late_time_count_from]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> In End Time</label>
                                    <label><input type="radio" name="key[late_time_count_from]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> In Start Time</label>


                                @elseif ($systemSetting->key == "employee_facilities")

                                    <label><input type="radio" name="key[employee_facilities]" {{ $systemSetting->value == '1' ? "checked" : "" }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_facilities]" {{ $systemSetting->value == '0' ? "checked" : "" }} value="0"> No</label>

                                    

                                @endif

                            </td>
                        </tr>

                    @endforeach
                    <tr>
                        <th class="bg-dark text-center" colspan="2">
                            <button class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
                        </th>
                    </tr>

                </table>
            </div>
        </form>
    </div>


@endsection




@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


    

    <!--  Select Box Search-->
    <script type="text/javascript">
        jQuery(function($){

            if (!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect: true});
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function () {
                        $('.chosen-select').each(function () {
                            var $this = $(this);
                            $this.next().css({'width': $this.parent()});
                        })
                    }).trigger('resize.chosen');

            }
        })
    </script>

    <script>
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }

    </script>

@stop
