<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StringEncryption
{

    public function __construct()
    {
        
    }

    public function encryptString($string, $key)
    {
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $ciphertext = openssl_encrypt($string, 'aes-256-cbc', $key, 0, $iv);
        return rtrim(strtr(base64_encode($iv . $ciphertext), '+/', '-_'), '=');
    }

    public function decryptString($string, $key)
    {
        $data = base64_decode(strtr($string, '-_', '+/'));
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $iv_size);
        $ciphertext = substr($data, $iv_size);
        return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, 0, $iv);
    }
}
