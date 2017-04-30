<?php

class RC4 {

    /*
     * inisialisasi vektor pada s-box
     */
    private static $S = array();
    
    /*
     * tukar nilai permutasi vektor pada box
     */
    private static function swap(&$v1, &$v2)
    {
        $v1 = $v1 ^ $v2;
        $v2 = $v1 ^ $v2;
        $v1 = $v1 ^ $v2;
    }
    
    /*
     * buat kunci
     */
    private static function KSA($kunci)
    {
        $idx = crc32($kunci);
        if (!isset(self::$S[$idx])) {
            $S = range(0, 255);
            $j = 0;
            $n = strlen($kunci);
            for ($i = 0; $i < 255; $i++) {
                $char = ord($kunci{$i % $n});
                $j = ($j + $S[$i] + $char) % 256;
                self::swap($S[$i], $S[$j]);
            }
            self::$S[$idx] = $S;
        }
        return self::$S[$idx];
    }
    
    /*
     * fungsi enkripsi data
     */
    public static function rc4_en($plainteks, $kunci)
    {
        $S = self::KSA($kunci);
        $n = strlen($plainteks);
        $i = $j = 0;
        $plainteks = str_split($plainteks, 1);
        for ($m = 0; $m < $n; $m++) {
            $i = ($i + 1) % 256;
            $j = ($j + $S[$i]) % 256;
            self::swap($S[$i], $S[$j]);
            $char = ord($plainteks{$m});
            $char = $S[($S[$i] + $S[$j]) % 256] ^ $char;   
            $plainteks[$m] = chr($char);
        }
        $plainteks = implode('', $plainteks);
        return $plainteks;
    }
    
    /*
     * fungsi dekripsi data
     */
    public static function rc4_de($plainteks, $kunci)
    {  
        $strbin='';
        $strhex='';
        for ($i=0; $i < strlen($plainteks); $i++) {
            $strhex .= $plainteks[$i];
            if ($i % 2 == 1) {
                $strbin .= chr(hexdec($strhex)); 
                $strhex = ''; 
            }
        } 
        return self::rc4_en($strbin, $kunci);
    }
    
}

?>