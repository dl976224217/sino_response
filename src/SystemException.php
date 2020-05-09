<?php
namespace SinoResponse;

 class SystemException extends SinoApiException{
 	public function getSinoStat(){
 		return 500;
 	}
 }