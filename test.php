<?php 
use Scrowsdk\V1\Http\Response;
use Scrowsdk\V1\Crypt\Crypt;
use Scrowsdk\V1\Http\Request;
use Scrowsdk\V1\Src\Auth;

include_once('V1/App/Config.php');
include('V1/Http/Response.php');
include('V1/Http/Request.php');
include('V1/Crypt/Encrypt.php');
include('V1/Src/Auth.php');

$Response = new Response();
$Crypt = new Crypt();
$Request  = new Request($Response);
$Auth   = new Auth($Response);

$data = array('name' => 'john doe', 'email' => 'demo@example.com', 'password' => "1792127910@Tom", 'gender' => 'Male', 'phone' => '+23494257994', 'acc_type' => '2', 'currency' => 'NGN');

$val = $Auth->signUp($data);


