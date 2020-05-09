<?php
namespace SinoResponse;
class NotFoundException extends SinoApiException{
 	public function getSinoStat(){
 		return 404;
 	}
 }