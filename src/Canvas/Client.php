<?php
 
namespace Canvas;
 
use GuzzleHttp\Client;
 
class CanvasClient extends GuzzleHttp\Client
{
	/**
	 * INI config settings
	 * @var array
	 */
	public static $apiConfig;
	
	/**
	 * ID of default account
	 * @var int
	 */
	public $defaultAccount;
	
	public function __construct($uri = null, $defaults = null)
	{
		if(is_null($defaults))
		{
			$defaults = array();
		}
		
		if(!isset($defaults['User-Agent']) || empty($defaults['User-Agent']))
		{
			$defaults['User-Agent'] = 'Canvas API Client';
		}
		$token = self::$apiConfig['apiKey'];
		$defauts['headers']['Authorization'] = "Bearer ".$token;
		parent::__construct(['base_uri'=> $uri, 'defaults' => $defaults]);
		

		$this->defaultAccount = self::$apiConfig['defaultAccount'];
	}
	
	public function setEndpoint($endpoint)
	{
		$this->setUri(self::$apiConfig['url'].'api/v1/'.$endpoint);
		return $this;
	}
	
	public function request($method = null)
	{
		$response = parent::request();
		if($response->isError())
		{
			$e = new CanvasClientException($response->getBody());
			$e->setResponse($response);
			$e->client = $this;
			throw $e;
		}
		
		// Do JSON decoding
		$contentType = $response->getHeader('Content-Type');
		if(stristr($contentType, 'application/json'))
		{
			// Decode as array, not stdClass
			return json_decode($response->getBody(), true);
		}
		else 
		{
			$e = new CanvasException( 'Invalid Content-type: '.$contentType."\n Body: ".$response->getBody() );
			$e->response = $response;
			throw $e;
		}
	}
}
