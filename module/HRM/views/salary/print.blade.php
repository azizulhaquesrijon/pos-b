<!DOCTYPE html>
<html lang="en">

<head>
    <title>Salary Print View</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta name="description" content="Static &amp; Dynamic Tables" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />
    <!-- ace settings handler -->
    <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace-skins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />
    <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/chosen.min.css')}}" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 0 !important;
            }

            .d-none {
                display: none;
            }
        }
    </style>
    <script src="{{ asset('assets/js/ace-extra.min.js') }}"></script>

</head>

<body class="no-skin">

<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.loadState('main-container')
        } catch (e) {}
    </script>
    <div class="main-content">
        <div class="main-content-inner">
            <div class="space-50" style="margin-top: 50px;"></div>
            <div class="table-responsive">
                <table id="tblData" class="table table-striped table-bordered table-hover">

                    <caption class="text-center">
                        <h3><strong>{{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_name : optional(auth()->user()->businessProfileByUser)->shop_name }}</strong></h3>
                        <h4><strong>{{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_address : optional(auth()->user()->businessProfileByUser)->shop_address }}</strong></h4>
                        <h4><strong>Salary Sheet For The Month of {{ \Carbon\Carbon::parse($salary->date.'/01')->format('F') }} {{\Carbon\Carbon::parse($salary->date.'/01')->format('Y')}}</strong></h4>
                    </caption>
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Employee Name</th>
                        <th style="width:10%;">Designation</th>
                        <th style="width:7%;">Join Date</th>
                        <th style="width:5%;">TTL Days</th>
                        <th style="width:5%;">Off Day</th>
                        <th style="width:5%;">W. Days</th>
                        <th style="width:5%;">Attn Day</th>
                        <th style="width:5%;">Abs Days</th>
                        <th style="width:3%;">Leave</th>
                        <th style="width:5%;">Pay Days</th>
                        <th style="width:5%;">OT Hours</th>
                        <th style="width:5%;">OT Amount</th>
                        <th>Advance</th>
                        <th>Salary</th>
                        <th>Net Salary</th>
                        <th style="width:10%;">Signature</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($salaries as $key=> $salary)

                        <tr style="height: 40px;">
                            <td>{{$key+1}}</td>
                            <td class="em_id">{{$salary->employee->name}}
                            </td>
                            <td>{{$salary->employee->designation}} </td>
                            <td>{{$salary->employee->joining_date}} </td>
                            <td>{{$salary['total_days']}}</td>
                            <td class="off_day">{{$salary['total_off_days']}}</td>
                            <td class="wdays">{{$salary['working_days']}}</td>
                            <td class="present">{{$salary['total_present']}}</td>
                            <td class="absent">{{$salary['total_absent']}}</td>
                            <td class="leave">{{$salary['total_leave']}}</td>
                            <td class="pay_days">{{$salary['total_present']}}</td>
                            <td>{{$salary['ot_hours']}}</td>
                            <td class="ot_amounts text-right">{{$salary['ot_amounts'] ?? 0}}</td>
                            <td class="advance text-right">{{$salary['advance'] ?? $advance}}</td>
                            <td class="salary text-right">{{$salary->employee->salary}}</td>
                            <td class="net_salary text-right"><b>{{$salary['payable_salary']}}</b></td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="12" class="text-right">Total</td>
                        <td class="tamount text-right">0</td>
                        <td class="tadvance text-right">0</td>
                        <td class="tsalary text-right">0</td>
                        <td class="tnet_salary text-right">0</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
                <div class="space-2"></div>
                <div class="form-group" style="float: right;">
                    <div class="col-sm-4 d-none">
                        <a href="{{ route('dokani.salaries.index') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-backward"></i>
                            Back
                        </a>
                    </div>
                    <div class="col-sm-4 d-none">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="window.print();">
                            <i class="fa fa-print"></i>
                            Print
                        </button>
                    </div>
                </div>

                <div class="d-none mx-2 py-3" style="margin-left: 30px">
                    <a  href="javascript:void(0)" onclick="exportTableToExcel('tblData','Master ({{ $salary->date }})')">
                        <img src="{{ asset('assets/images/excel.png') }}" alt="" width="3%">
                    </a>
                    <a href="javascript:void(0)" onclick="Export('tblData', 'Salary ({{ $salary->date }})')">
                        <img src="{{ asset('assets/images/pdf.png') }}" alt="" width="2%">
                    </a>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
    <div class="footer d-none">
        <div class="footer-inner">
            <div class="footer-content">Developed By
                <span class="blue bolder">
                        <a href="https://www.smartsoftware.com.bd/" target="_blank"> Smart Software Ltd </a>
                    </span>
                &copy; 2020
            </div>
        </div>
    </div>
    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>

    <script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>


    <!-- ace scripts -->
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            sum('ot_amounts', 'tamount');
            sum('advance', 'tadvance');
            sum('salary', 'tsalary');
            sum('net_salary', 'tnet_salary');
        })

        function sum(e, field) {
            var total = 0;
            $('.' + e).each(function() {
                total += parseInt($(this).text())
            })
            $('.' + field).text(total);
        }
    </script>

    <script src="{{ asset('assets/custom_js/export-excel-file.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script>

        function Export(tableID,filename = 'Vat-Reports') {

            // Specify file name
            filename = filename?filename+'.pdf':'pdf_data.pdf';

            html2canvas(document.getElementById(tableID), {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download(filename);
                }
            });
        }

    </script>
</body>

</html>
