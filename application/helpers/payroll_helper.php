<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('number_to_words')) {

    function number_to_words($number)
    {
        $number = number_format($number, 2, '.', '');
        $parts = explode('.', $number);

        $whole = (int)$parts[0];
        $centavos = (int)$parts[1];

        $words = trim(convert_whole_number($whole));

        if ($centavos > 0) {
            $words .= " AND " . str_pad($centavos, 2, '0', STR_PAD_LEFT) . "/100";
        } else {
            $words .= " ONLY";
        }

        return strtoupper($words);
    }

    function convert_whole_number($number)
    {
        $ones = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen',
            17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'
        ];

        $tens = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty',
            5 => 'Fifty', 6 => 'Sixty', 7 => 'Seventy',
            8 => 'Eighty', 9 => 'Ninety'
        ];

        if ($number == 0) {
            return 'Zero';
        }

        $words = '';

        if ($number >= 1000000) {
            $millions = floor($number / 1000000);
            $words .= convert_whole_number($millions) . ' Million ';
            $number %= 1000000;
        }

        if ($number >= 1000) {
            $thousands = floor($number / 1000);
            $words .= convert_whole_number($thousands) . ' Thousand ';
            $number %= 1000;
        }

        if ($number >= 100) {
            $hundreds = floor($number / 100);
            $words .= $ones[$hundreds] . ' Hundred ';
            $number %= 100;
        }

        if ($number >= 20) {
            $words .= $tens[floor($number / 10)] . ' ';
            $number %= 10;
        }

        if ($number > 0) {
            $words .= $ones[$number] . ' ';
        }

        return trim($words);
    }
}