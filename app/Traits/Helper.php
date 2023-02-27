<?php
namespace App\Traits;
use Module\HRM\Models\Attendance\Holiday;
use Carbon\Carbon;

trait Helper{

    public static function getDayNames()
    {
        return ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    }

    public static function weekly_holiday($company,$month){

        $daysName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $weeklyHoliday = Holiday::where('company_id',$company)->where('day_type',3)->get();

        $year = date('Y',strtotime($month));
        $month = date('m',strtotime($month));
        $count = 0;

        foreach ($weeklyHoliday as $dayNum) {
            foreach ($daysName as $key => $name){
                if ($dayNum->start == $key){
                    $day = $name;
                    // $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                    $days      = Carbon::parse($year . '-' . $month)->addMonth(1)->subDay(1)->format('d');
                    $date = new \DateTime($year . '-' . $month . '-01');

                    for ($i = 1; $i <= $days; $i++) {
                        if ($date->format('l') == $day) {
                            $count++;
                        }
                        $date->modify('+1 day');
                    }
                }
            }
        }

        return $count ;
    }

    public static function another_holiday($company,$month)
    {
        $lastDay = Carbon::parse($month)->lastOfMonth();
        $holidays = Holiday::where('company_id',$company)->whereIN('day_type',[1,2])->whereBetween('start',[$month.'-01',$lastDay])->get();
        $count = 0;
        $countDay = 0;
        $totalDiffDay = 0;
        $diffDay = [];

        foreach ($holidays as $holiday){
            if ($holiday->day_type == 2){
                for ($i=$holiday->start;$i<=$holiday->end;$i++){
                    $diffDay[] = [$i,];
                }
            }
            if ($holiday->day_type == 1){
                $countDay += 1;
            }
            $count = count($diffDay)+$countDay;
        }

        return $count;


    }

    public static function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

}
