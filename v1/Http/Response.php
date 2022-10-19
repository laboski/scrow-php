<?php 
namespace Scrowsdk\V1\Http;

/**
 * 
 */
class Response
{
	public ?int $status = null;
	public $data = null;

	public function setResponse($response)
	{
		foreach ($response as $key => $value) {
			$this->$key = $value;
		}
	}

	public function responseBody()
	{
		return ($this->status == null && $this->data == null) ? (object)[] : (object)[
				'status' => $this->status,
				'data' => $this->data
			 ]; 
	}
}