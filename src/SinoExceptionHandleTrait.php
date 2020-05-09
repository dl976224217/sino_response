<?php
namespace SinoResponse;

use Exception;
use Throwable;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;;
// use SinoException\NotFoundException;
// use SinoException\AuthException;
// use SinoException\OtherException;
// use SinoException\SystemException;

trait SinoExceptionHandleTrait{

	public function render($request, Exception $e){

		if($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            $e = new NotFoundException(404,$e->getMessage());
        } elseif ($e instanceof MethodNotAllowedHttpException){
            $e = new NotFoundException(405,$e->getMessage());
        } elseif ($e instanceof AuthorizationException) {
            $e = new AuthException(401,$e->getMessage());
        } elseif ($e instanceof TokenMismatchException) {
            $e = new OtherException(419,$e->getMessage());
        } elseif ($e instanceof SuspiciousOperationException) {
            $e = new NotFoundException(404,'Bad hostname provided.');
        } elseif (!$e instanceof SinoApiException) {
        	// dd($e);
            $e = new SystemException($e->getCode(),$e->getMessage());
        }

		return response($e->toJson());
	}

}