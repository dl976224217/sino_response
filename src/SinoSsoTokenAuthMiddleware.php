<?php

namespace SinoResponse;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
// use Symfony\Component\HttpKernel\Exception\HttpException;
use SinoResponse\AuthException;
use SinoResponse\SystemException;

 class SinoSsoTokenAuthMiddleware extends Middleware{

 	public function handle($request, Closure $next, ...$guards)
    {
   
        $ssoUserInfo = $this->authenticate($request, $guards);
        $request->input('X-Sino-Sso-UserInfo',$ssoUserInfo);

        return $next($request);
    }

    protected function authenticate($request, array $guards)
    {
    	$token = $request->header('X-Sino-Sso-Token');
    	if(empty($token)){
    		throw new AuthException(401,'Token不存在');
    	}

    	$ssoUserInfo = $this->checkSinoSsoToken($token);


        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        // throw new AuthenticationException(
        //     'Unauthenticated.', $guards, $this->redirectTo($request)
        // );
        return $ssoUserInfo;
    }


    public function checkSinoSsoToken($token){
        $resultInfo = [];
        $requestHost = env('SSO_HOST');
        if(empty($requestHost)){
            throw new SystemException(500,'请在ENV文件中配置SSO_HOST,例:http://192.168.0.205:8080(测试)');
        }
    	$ssoGetUserInfoApiUrl = $requestHost.'/sso/getUserInfoByToken';
        $requestData = [
            'token'=>$token
        ];
        $params = json_encode($requestData);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $ssoGetUserInfoApiUrl);
        curl_setopt($ch,CURLOPT_TIMEOUT, 0);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type: application/json']);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        $resultInfo = json_decode($result,true);
        if($resultInfo['code'] == '998'){
            throw new AuthException(401,$resultInfo['message']);
        }
        return $resultInfo;
    }
 }