<?php namespace App\Http\Middleware;
use Closure;
class CheckCred{
    public function handle($request, Closure $next)
    {
        $key = "19bb33194fa895a75aba8e7a7acb902dfa82347d1a2640a8b49f05b6ee8a9e545194ff042ac2185afb3e6259df6b4fd688c83f46601f04a7b37ef04b53468c29";
        $token = $request->header('UserToken');
        if(!$token){
            $token = $request->get('token');
        }
        if($token){
            $decoded = \JWT::decode($token, $key);
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