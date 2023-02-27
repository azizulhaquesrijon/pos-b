<?php


namespace Module\Dokani\Services\Reports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class AstmExportService implements FromView, ShouldAutoSize
{


    public $exportData = [];

    public function __construct($data)
    {
        $this->exportData = $data;
    }

    public function view(): View
    {
        $data = $this->exportData;

        return view($data['file_path'] . request('export_type'), $data);
    }



    /**
     * customize excel sheet
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // Company Heding
                $cellRange2 = 'A2:W2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(24);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(18);

                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ'];

                foreach ($columns as $key => $column) {
                    if ($key == 0)
                        $event->sheet->getColumnDimension($column)->setWidth(6);
                    else
                        $event->sheet->getColumnDimension($column)->setWidth(20);
                }
            }
        ];
    }
}
