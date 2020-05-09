<?php
namespace SinoResponse;

class FormException extends SinoApiException{
 	public $code;
 	public $message;
 	public $data;
 	public $validations;
 	public function __construct($code = -1, $message = '', $data = array(), $validations = ''){
 		$this->code = $code;
 		$this->message = $message;
 		$this->data = $data;
 		$this->validations = $validations;
    }
 	public function getSinoStat(){
 		return 422;
 	}
 	public function toArray(){
 		$array = parent::toArray();
 		$array['validations'] = $this->validations;
 		return $array;
 	}
 }