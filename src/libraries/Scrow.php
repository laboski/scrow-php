<?php
    class Pandascrow
    {
        //Api keys
        public $publicKey;
        public $secretKey;
        public $payref;
        protected $payhash;
        public $baseURL = 'http://api.pandascrow.io/v1/index';
        public function __construct(String $secretKey = '', String $baseURL = '')
        {
            $this->secretKey = $secretKey;
            $this->publicKey = $_ENV['PUBLIC_KEY'];
            $this->baseURL = $baseURL;
        }
    }
    