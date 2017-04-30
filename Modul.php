<?php

class Modul {

    private $plain;
    private $cipher;

    public function key_gen($str, $key)
    {
        $l = strlen($key);
        for ($i = $l; $i < strlen($str); $i++) {
            $key[$i] = $key[$i - $l];
        }
        return $key;
    }

    public function modul_en($str, $key)
    {
        $cipher = RC4::rc4_en($str, $key);
        $cipher = Cbc::cbc_en($cipher, $key);
        return $cipher;
    }

    public function modul_de($str, $key)
    {
        $plain = CBC::cbc_de($str, $key);
        $strhexa = bin2hex($plain);
        $plain = RC4::rc4_de($strhexa, $key);
        return $plain;
    }

}

?>