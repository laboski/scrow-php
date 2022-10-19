<?php 
namespace Scrowsdk\V1\Src;

use Scrowsdk\V1\Http\Response;
use Scrowsdk\V1\Http\Request;

/**
 * This class handles the sign up and login requests.
 */
class Auth extends Request
{

	function __construct(Response $Response)
	{
		parent::__construct($Response);
	}
	/**
	*
	*@param array
	*/
	public function signUp(array $data)
	{
		if ($this->validate($data)) {
			$this->addHeader('Accept', 'application/json');
			$this->addHeader('Content-Type', 'application/json');
			$this->addHeader('Authorization', $this->config->secret_key);
			$this->addHeader('Appid', $this->config->app_id);
			$this->setMethod('POST');
			$this->endpoint('user/auth/signup');
			$this->curlHandler();
		}		
		return $this->response->responseBody();
	}
}
