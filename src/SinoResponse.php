<?php
namespace SinoResponse;

use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\Response;
use Psr\Http\Message\ResponseInterface;

class SinoResponse extends Response implements ResponseInterface{

	private $code;
	private $message;

    public function __construct(string $type,$data,$code=0, string $message='')
    {
    	
    	$this->code = $code;
    	$this->message = $message;
    	$allowTypeAry = array(
        	'entity'=>null,
        	'list'=>[],
        	'map'=>new stdClass()
        );
    	if(!array_key_exists($type, $allowTypeAry)){
    		throw new HttpException("响应类型不存在");
    	}
    	$returnData = array(
        	"sinostat"=>0, 
        	"code"=>$this->code,
        	"message"=>$this->message
        );
    	if(!empty($data)){
    		$returnData['data'] = $data;
    	}else{
			$returnData['data'] = $allowTypeAry[$type];
    	}
        parent::__construct('1.1', [], new Stream(json_encode($returnData)),200);
    }

}