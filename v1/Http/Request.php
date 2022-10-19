<?php 
namespace Scrowsdk\V1\Http;

use Scrowsdk\V1\Http\Response;
use Scrowsdk\V1\Crypt\Crypt;
/**
 * 
 */
class Request
{

	/**
 	* 
 	* @var null|Response  
 	* 
 	*/
	protected ?Response $response = null;
	/**
 	* 
 	* @var string  
 	* 
 	*/
	private string $url = 'https://api.pandascrow.io/v1/index/';
	/**
 	* 
 	* @var array  
 	* 
 	*/
	protected array $body = [];
	/**
 	* 
 	* @var string  
 	* 
 	*/
	private string $method = '';
	/**
 	* 
 	* @var null|object 
 	* 
 	*/	
	protected $config = null;
	/**
 	* 
 	* @var array 
 	* 
 	*/	
	private array $headers = array();
	/**
 	* 
 	* @param Response 
 	* 
 	*/	
	function __construct(Response $Response)
	{
		$this->response = $Response;
		$this->config   = load_config('app_config');
	}
	/**
     * @return string
     */
    private function getMethod()
    {
        return $this->method;
    }
    /**
     * @param string 
     */
    protected function setMethod($method)
    {
        $this->method = $method;
    }
	/**
 	* 
 	* @param string 
 	* 
 	*/	
	protected function endpoint(string $endpoint)
	{
		$this->url .= $endpoint;	
	}
	/**
 	* 
 	* @return array 
 	* 
 	*/	
	private function rules()
	{
		return $rules = ['name' => ['required', 'fname'], 'password' => ['required', 'min', 'encrypt'], 'email' => ['required', 'email'], 'gender' => ['gender'], 'phone' => ['required', 'phone'], 'country' => ['required'], 'state' => ['alphabet'], 'address' =>['alphanumeric'], 'dob' => ['date'], 'acc_type' => ['digits'], 'currency' => ['required', 'currency'], 'ref_token' => ['alphanumeric']];	
	}
	/**
 	* 
 	* @param array 
 	* 
 	*/	
	protected function validate(array $data)
	{
		if (empty($data)) {
			return;
		}
		$validate_error = "";
		$rules = $this->rules();	
		foreach ($data as $k => $v) {
			if (! is_string($v)) {
				$validate_error = "invalid value at ".$k;
			}
			if (isset($rules[$k])) {
				foreach ($rules[$k] as $rule) {
					if ($rule === "required") {
						if ($v === "") {
							$validate_error = $k." is required";
						}
					}
					if ($rule === "min") {
						if (strlen($v) < 6) {
							$validate_error = $k."cannot be less than 6 characters";
						}
					}
					if ($rule === "encrypt") {
						$Crypt = new Crypt();
						$val = $Crypt->encryptAES($v);
						$data[$k] = $val;
                        
					}
					if ($rule === "fname") {
						if(preg_match("/^[a-zA-Z\s]+$/", $v) === false){
							$validate_error = "invalid fullname ".$k;
						}
					}
					if ($rule === "email") {
						if (filter_var($v, FILTER_VALIDATE_EMAIL) === false) {
							$validate_error = "invalid email";
						}
					}
					if ($rule === "gender") {
						if (! in_array($v, ["Male", "Female", "Custom"])) {
							$validate_error = $k." is not a valid gender";
						}
					}
					if ($rule === "phone") {
						if (preg_match("/^\\+[1-9][0-9]{7,14}$/", $v) === false) {
							$validate_error = $k." is not a valid phone number";
						}
					}
					if ($rule === "address") {
						if (preg_match("/[A-Za-z0-9\-\\,.]+/", $v) === false) {
							$validate_error = $k."is not a valid address";
						}
					}
					if ($rule === "alphanumeric") {
						if (preg_match("/^[a-zA-Z0-9]$/", $v) === false) {
							$validate_error = $k."should contain alphanumeric values";
						}
					}
					if ($rule === "digits") {
						if (preg_match("/^[0-9]$/", $v) === false) {
							$validate_error = $k."should contain alphanumeric values";
						}
					}
					if ($rule === "date") {
						if (preg_match("/^[0-9]{1,2}\\-[0-9]{1,2}\\-[0-9]{4}$/", $v) === false) {
							$validate_error = $k."should be a date y-m-d";
						}
					}
					if ($rule === "currency") {
						if (! in_array($v, ["NGN", "GHS", "USD", "ZAR"])) {
							$validate_error = $k." is not a supported currency";
						}
					}
				}
			}else{
				$validate_error = $k." is not a valid field";
			}
		}

		if ($validate_error == "") {
				$this->body = $data;
				return true;
			}else{
				$response = (object)[
										"status" => 409,
										"data" =>(object)[
													"message" => $validate_error,
													"documentation_url" => "https://pandascrow.readme.io/reference/signup-user"
												]
									];
				$this->response->setResponse($response);
				return false;			
			}	
	}
	/**
 	* 
 	* This function converts the request body to urlencoded string 
 	* 
 	*/	
	private function parse_body()
	{
		if (empty($this->getBody())) {
			return;
		}
		$url = "";
		foreach ($this->getBody() as $key => $value) {
			$url .= "$key=$value "; 
		}
		$url = rtrim($url);
		return urlencode($url);	
	}
	/**
     * @return string
     */
    private function getUrl()
    {
        $this->url.$get = ($this->getMethod() === 'GET') ? $this->parse_body() : '';
        return $this->url;
    }
	/**
 	* Lets you set a header for a request to the pandascrow api
 	* @param string
 	* @param string 
 	* 
 	*/	
	protected function addHeader(string $key, string $value)
	{
		$this->headers[] = "$key: $value";
	}
	/**
 	* Returns the body of a request
 	* @return array 
 	* 
 	*/	
	private function getBody()
	{
		return $this->body;
	}
	/**
 	* Returns headers of a request
 	* @return array 
 	* 
 	*/	
	private function getHeaders()
	{
		return $this->headers;
	}
	/**
 	* Handles the curl connection  
 	* 
 	*/	
	protected function curlHandler()
	{
        echo json_encode($this->getBody());
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getUrl());			 
		switch ($this->getMethod()) {
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->getBody()));
				break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->getMethod());
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->getBody()));
                break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->getMethod());
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->getBody()));
                break;
			default:
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->getMethod());
				break;
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$resp = curl_exec($ch);
		$err = curl_error($ch);
		$response = json_decode($resp);
		$this->response->setResponse($response);
		curl_close($ch);
	}
}