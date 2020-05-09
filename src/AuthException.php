<?php

namespace SinoResponse;

//认证异常(401)
 class AuthException extends SinoApiException{
 	public function getSinoStat(){
 		return 401;
 	}
 }