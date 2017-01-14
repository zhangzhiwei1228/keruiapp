<?php
/**
 * Class AES
 * @author hanj
 */
class AES {
    public static function encrypt($input, $key) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = AES::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, md5($key), $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);

        return $data;
    }

    private static function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private static function hex2bin($hexdata) {
        $bindata = '';
        $length = strlen($hexdata);
        for ($i = 0; $i < $length; $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    public static function decrypt($sStr, $sKey) {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');

        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        mcrypt_generic_init($td, md5($sKey), $iv);

        $decrypted_text = mdecrypt_generic($td, base64_decode($sStr));
        $rt = rtrim($decrypted_text);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $rt = AES::pkcs5_pad($rt, $size);
        $dec_s = strlen($rt);
        $padding = ord($rt[$dec_s - 1]);
        $rt = substr($rt, 0, -$padding);
        $rt = rtrim($rt);
        $rt = preg_replace('/(\}[^\]\}\{]*)$/', '}', $rt);
        return $rt;
    }
}