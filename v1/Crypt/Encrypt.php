<?php

namespace Scrowsdk\V1\Crypt;

class Crypt
{
    private $encryptMethod = "AES-256-CBC";
    private $key;
    private $iv;
    public function __construct()
    {
        $mykey = "VNATfbChXqGFeHUYJXbVavVwaKZqRChXqGFeHUChXqGFeHU";
        $myiv = 'PWD_';
        $this->key = substr(hash('sha256', $mykey), 0, 32);
        $this->iv = substr(hash('sha256', $myiv), 0, 16);
    }
    public function encryptAES(string $value): string
    {
        return openssl_encrypt($value, $this->encryptMethod, $this->key, 0, $this->iv);
    }
    public function decryptAES(string $base64Value): string
    {
        return openssl_decrypt($base64Value, $this->encryptMethod, $this->key, 0, $this->iv);
    }
}
