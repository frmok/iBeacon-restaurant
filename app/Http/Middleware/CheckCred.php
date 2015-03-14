<?php namespace App\Http\Middleware;
use Closure;
class CheckCred{
    public function handle($request, Closure $next)
    {
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
        
        //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjIsImlhdCI6MTQyNjE2ODk2NywiZXhwIjoxNDI2NDI4MTY3fQ.pXa1JedOqooEJLfxim81cEkniWvi_2G51IZUYRMJcHc
    }
}
?>