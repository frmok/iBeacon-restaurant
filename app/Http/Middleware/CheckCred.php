<?php namespace App\Http\Middleware;
use Closure;
class CheckCred{
    public function handle($request, Closure $next)
    {
        //There are two ways to retrieve the token
        //1. From header
        //2. From query string
        $token = $request->header('UserToken');
        if(!$token){
            $token = $request->get('token');
        }
        if($token){
            $decoded = \JWT::decode($token, env('JWT_KEY'));
            \Auth::loginUsingId($decoded->uid);
            return $next($request);
        }else{
            $response['status'] = '401';
            $response['debug'] = 'Not Authorized';
            return \Response::json($response, 401);
        }
    }
}
?>