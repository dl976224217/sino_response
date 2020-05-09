<?php
namespace SinoResponse;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use SinoResponse\SinoExceptionHandlerTrait;

class SinoExceptionHandleMiddleware extends Middleware{
	use SinoExceptionHandlerTrait;
	public function handle($request,$next){
		try{
			return $next($request);
		}catch(\Throwable $e){
			return $this->render($request,$e);
		}
	}

}