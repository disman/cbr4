<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cbc {

    /*
     * inisialisasi inisial vektor
     */
    private static $iv = NULL;

    /*
     * geser karakter 1 bit ke kiri
     */
    public static function left_wrap($chr)
    {
        $tmp = decbin(ord($chr));
        $tmplen = strlen($tmp);
        $binlen = 8 - $tmplen;
        
        /*
         * mengisi array kosong dengan biner
         */
        for($i = 0; $i <= 7; $i++){
            if($i < $binlen){
                $arr[$i] = 0;   
            } else {
                $arr[$i] = $tmp[$i-$binlen];
            }   
        }

        /*
         * proses penggeseran bit
         */
        for($i = 0; $i <= 7; $i++){
            if($i == 7){
                $bin[7] = $arr[0];
            } else {
                $bin[$i] = $arr[$i+1];
            }
        }
        $bin = implode("", $bin);
        $chr = chr(bindec($bin));
        return $chr;
    }

    /*
     * fungsi enkripsi data
     */
    public static function cbc_en($str, $key)
    {
        for ($i = 0; $i < strlen($str); $i++) {
            if ($i == 0) {
                $str[$i] = chr(ord($str[$i]) ^ ord(Cbc::$iv));
            } else {
                $str[$i] = chr(ord($str[$i]) ^ ord($str[$i-1]));
            }
            $str[$i] = chr(ord($str[$i]) ^ ord(Modul::key_gen($str, $key)));
            $str[$i] = Cbc::left_wrap($str[$i]);
        }
        return $str;
    }

    /*
     * fungsi dekripsi data
     */
    public static function cbc_de($str, $key)
    {
        $plaint = "";
        $xor_result = array();
        $array_decimal = Cbc::hexa_to_decimal($str);
        for ($i = 0; $i < sizeof($array_decimal); $i++) {
            $decimal_char = $array_decimal[$i];
            $tmp[$i] = $decimal_char;
            $biner = Cbc::right_wrap($decimal_char);
            $decimal_char = bindec($biner);
            if ($i == 0) {
                $xor_result[$i] = $decimal_char ^ ord(Cbc::$iv);
            } else {
                $xor_result[$i] = $decimal_char ^ ($tmp[$i-1]);
            }
            $xor_result[$i] = $xor_result[$i] ^ ord(Modul::key_gen($str, $key));
            $str = $xor_result[$i]; 
            $plaint = $plaint . (chr($str));
        }  
        return $plaint;
    }

    /*
     * geser karakter 1 bit ke kanan
     */
    public static function right_wrap($decimal)
    {
        $str_result = null;
        $bin = decbin($decimal);
        $str_bin = (string)$bin;
        $str_bin_move_right ="";
        $last_char = "0";
        $str_length = strlen($str_bin);
        $last_char = $str_bin[$str_length -1];
        $str_bin_move_right = substr($str_bin, 0, -1);
        $str_append_binner = Cbc::append_biner_8_bit($str_bin_move_right, $str_length);
        $str_result = $last_char . $str_append_binner;
        $int_result = (int)$str_result;
        return $int_result;
    }

    /*
     * menambahkan biner 8 bit
     */
    public static function append_biner_8_bit($str)
    {
        $result = "";
        switch (strlen($str)) {
                case 0 :
                $result = "0000000";
                break;
                case 1:
                $result = "000000" . $str;
                break;
                case 2 :
                $result = "00000" . $str;
                break;
                case 3 :
                $result = "0000". $str;
                break;
                case 4:
                $result = "000". $str;
                break;
                case 5:
                $result = "00". $str;
                break;
                case 6:
                $result = "0".$str;
                break;
                case 7 :
                $result = $str;
                break;
        }
        return $result;
    }

    /*
     * konversi hexa ke desimal
     */
    public static function hexa_to_decimal($str)
    {
        $index = 0;
        $result = array();
        for ($i = 0; $i < strlen($str); $i++) {
            if ($i % 2 == 1) {
                $strhexa = $str[$i-1] . $str[$i];
                $decimal = hexdec($strhexa);
                $result[$index] = $decimal; 
                ++$index;
            }
        }
        return $result;
    }

}