<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Constants\Strings;

class IsRequestEncrypted
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //dd(env('ENCRYPTION_SECRET_KEY'));
        
        if($request->getContent())
        {
            
            $encryptMethod = "AES-128-CBC";
            $secretIV = hex2bin(env('ENCRYPTION_INITIALIZATION_VECTOR'));
            $secretKey = hex2bin(env('ENCRYPTION_SECRET_KEY'));

            $encryptedData = $request->getContent();
            $decryptData = openssl_decrypt($encryptedData, $encryptMethod, $secretKey, OPENSSL_ZERO_PADDING, $secretIV);
            $decryptData = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $decryptData);

            if($decryptData){

                $decodedData =  json_decode($decryptData, 1);
                $request->replace($decodedData);

                return $next($request);
            }
        }

        return $this->errorResponse(Strings::ERROR_REQUEST_IS_NOT_ENCRYPTED, 403);
    }

}
