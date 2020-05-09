<?php

namespace SinoResponse;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use SinoResponse\AuthException;
// use Symfony\Component\HttpKernel\Exception\HttpException;

 class SinoTokenAuthMiddleware extends Middleware{

 	public function handle($request, Closure $next, ...$guards)
    {
        $userInfo = $this->authenticate($request, $guards);
        $request->input('X-Sino-UserInfo',$userInfo);
        return $next($request);
    }

    protected function authenticate($request, array $guards)
    {
    	$token = $request->header('X-Sino-Token');
    	if(empty($token)){
    		throw new AuthException(401,'Token不存在');
    	}

    	$userInfo = $this->checkUserCenterToken($token);


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
        return $userInfo;
    }


    public function checkUserCenterToken($token){
        $resultInfo = [];
        $requestHost = env('USER_CENTER_HOST');
        if(empty($requestHost)){
            throw new SystemException(500,'请在ENV文件中配置USER_CENTER_HOST,例:http://o-test-sino-usersv2-api.meetsocial.cn(测试)');
        }
    	$ssoGetUserInfoApiUrl = $requestHost.'/usersv2/getuserinfo';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $ssoGetUserInfoApiUrl);
        curl_setopt($ch,CURLOPT_TIMEOUT, 0);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type: application/json','X-SINO-TOKEN: '.$token]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        // curl_setopt($ch,CURLOPT_POST, true);
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        $resultInfo = json_decode($result,true);
        if($resultInfo['code'] != 0){
            throw new AuthException(401,$resultInfo['message']);
        }
        return $resultInfo;
    }
 }