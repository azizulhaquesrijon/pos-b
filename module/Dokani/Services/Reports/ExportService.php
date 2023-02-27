<?php


namespace Module\Dokani\Services\Reports;


use Maatwebsite\Excel\Facades\Excel;
//use PDF;

class ExportService
{




    /*
     |--------------------------------------------------------------------------
     | EXPORT DATA
     |--------------------------------------------------------------------------
    */
    public function exportData($data, $file_path, $filename)
    {

        if (strpos(request('export_type'), 'pdf') == true) {
           //
            $pdf = \PDF::loadview($file_path . request('export_type'), $data, [], [
                'margin_header'         => 10,
                'margin_footer'         => 5,
                'mode'                  => 'utf-8',
                'format'                => 'A4',
            ]);
            //dd($pdf);
            $pdf->getMpdf()->setFooter("{PAGENO} / {nb}");

            return $pdf->stream($filename . '.pdf');
        }


        if (strpos(request('export_type'), 'excel') == true) {

            $data['file_path'] = $file_path;
            $filename = $filename . '.xlsx';

            return Excel::download(new AstmExportService($data), $filename);
        }
    }
}
