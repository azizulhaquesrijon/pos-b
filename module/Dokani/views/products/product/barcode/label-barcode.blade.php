<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Label Barcode Print</title>

    <style>

        @import url('https://fonts.googleapis.com/css2?family=BIZ+UDPMincho&display=swap');

        @media print {
            @page {
                /* size: 22cm 35cm; */
                size: 1.49in 1in;
            }

            .barcode--company-website {
                margin-bottom: 0px !important;
                padding-bottom: 0px !important;
            }

            .barcode--product_name {
                margin-top: 0px !important;
                margin-bottom: 0px !important;
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }
        }
        * {
            margin: 0px !important;
            padding: 0px !important;
            box-sizing: border-box !important;
            /* font-family: 'Alef', sans-serif; */
            font-family: 'BIZ UDPMincho', serif !important;
        }
        .main-body {
            /* padding-left: 10px; */
            /* flex-wrap: column; */
        }
        .main-body .variation-name {
            font-size: 10px;
            margin-bottom: 0px !important;
            display: inline-block;
            height: 15px;
            overflow: hidden;
        }
        .main-body .variation-name-opacity{
            opacity: 0
        }

        .page-header {
            display: none !important;
        }

        .btn {
            display: none !important;
        }

        .label-print {
            /* padding: 3px !important; */
            text-align: center !important;
            font-size: 10px;
            /* height: 0.98in !important; */
            /* line-height: 0.98in; */
            /* width: 1.552in !important; */
            overflow: hidden;
        }
        #allPrintList {
            text-align: center !important;
            font-size: 7px !important;
            width: 831.49606299px !important;
            height: 1322.8346457px !important;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            background-color: #efefef !important;
        }

        .all-print {
            width: calc(105.6px + 32.982677165px) !important;
            height: 67.2px !important;
        }
        .barcode--product_name {
            height: 21px !important;
            overflow: hidden !important;
            font-size: 10px !important;
            font-family: 'BIZ UDPMincho', serif !important;
            font-weight: 400 !important;
            margin-bottom: 2px !important;
            margin-top: 3px !important;
            padding-left: 2px;
        }
        .barcode--product_image {
            width: 80% !important;
            margin: 0px auto !important;
            height: 0.294in !important;
        }

        .barcode--product_barcode-and-mrp {
            font-size: 9px !important;
            line-height: 13px !important;
            font-family: 'BIZ UDPMincho', serif !important;
            font-weight: 400 !important;
        }

        .barcode--company-website {
            font-size: 10px !important;
            line-height: 13px !important;
            font-family: 'BIZ UDPMincho', serif !important;
            font-weight: 400 !important;
        }
        .variation-name{
            display: block !important;
            font-size: 10px;
            height: 12px !important;
            font-family: 'BIZ UDPMincho', serif !important;
            /* font-weight: 400 !important; */
        }
        #labelPrintList{
            font-size: 10px;
        }
        .main-body .page-break {
            width: 143.04px !important;
            /* width: 138.582677165px !important; */
            height: 96px !important;
            /* height: 75px !important; */
            text-align: center !important;
            margin: 0px !important;
            /* margin: 14px 6px !important; */
            display: block;
            page-break-inside: avoid;
            padding-top: 3px !important;
        }

    </style>

</head>
<body>

    <div class="main-body">
        <div class="page-break all-print">

            <p class="barcode--product_name">Product Name</p>
      
            <img class="barcode--product_image" src="data:image/png;base64, {{ DNS1D::getBarcodePNG('111111111' , "C128") }}" alt="barcode" />
            <p class="barcode--product_barcode-and-mrp">
                11111111111
            </p>
                <p class="barcode--company-website">POrice - 4680 BDT</p>
        </div>
    </div>


    <script>
        setTimeout(function(){
            window.print();
        }, 1000)
    </script>

</body>
</html>
