<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		try {
		   $user = JWTAuth::parseToken()->authenticate();

		   if(isset($user) && !empty($user)){
			//    $permission = RoleHasPermission::where([ 'role_id'])->first()
			// 	echo "Hi";
			// 	print_r($user);
		   }else{
				return response()->json(['status' => "You don't have access."], 401);
		   }
		   
 		} catch (Exception $e) {
        	  if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
		    return response()->json(['status' => 'Token is Invalid'], 403);
		  }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
			return response()->json(['status' => 'Token is Expired'], 401);
		  }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException){
			return response()->json(['status' => 'Token is Blacklisted'], 400);
		  }else{
		        return response()->json(['status' => 'Authorization Token not found'], 404);
		  }
		}
            return $next($request);
	}
}
