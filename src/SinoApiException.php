<?php
 namespace SinoResponse;

// use Doctrine\Instantiator\Exception;
use Exception;

 abstract class SinoApiException extends Exception{

 	public $code;
 	public $message;
 	public $data;
 	public function __construct($code = -1, $message = '', $data = array()){
 		$this->code = $code;
 		$this->message = $message;
 		$this->data = $data;
    }

    abstract public function getSinoStat();

 	public function toArray(){
 		return [
 			'sinostat'=> $this->getSinoStat(),
 			'code'=>$this->code,
 			'message'=>$this->message,
 			'data'=>$this->data
 		];
 	}
 	public function toJson(){
 		return json_encode($this->toArray());
 	}
 } 

// //认证异常(401)
//  class AuthException extends SinoApiException{
//  	public function getSinoStat(){
//  		return 401;
//  	}
//  }

 //资源不存在(404)
 // class NotFoundException extends SinoApiException{
 // 	public function getSinoStat(){
 // 		return 404;
 // 	}
 // }

 //表单验证失败(422)
 // class FormException extends SinoApiException{
 // 	public $code;
 // 	public $message;
 // 	public $data;
 // 	public $validations;
 // 	public function __construct($code = -1, $message = '', $data = array(), $validations = ''){
 // 		$this->code = $code;
 // 		$this->message = $message;
 // 		$this->data = $data;
 // 		$this->validations = $validations;
 //    }
 // 	public function getSinoStat(){
 // 		return 422;
 // 	}
 // 	public function toArray(){
 // 		$array = parent::toArray();
 // 		$array['validations'] = $this->validations;
 // 	}
 // }

 //其它请求异常(400)
 // class OtherException extends SinoApiException{
 // 	public function getSinoStat(){
 // 		return 400;
 // 	}
 // }

 //系统异常(500)
 // class SystemException extends SinoApiException{
 // 	public function getSinoStat(){
 // 		return 500;
 // 	}
 // }

 //外部异常(600)
 // class OutsideException extends SinoApiException{
 // 	public function getSinoStat(){
 // 		return 600;
 // 	}	
 // }

 