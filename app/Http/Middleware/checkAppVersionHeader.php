<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use App\Models\Settings\Setting;
use Illuminate\Contracts\Auth\Guard;

class checkAppVersionHeader
{
    use ApiResponser;
    /**
     * The Guard implementation.
     *
     * @var Guard
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userAgent = $request->header('user-agent');
        $trade = $request->header('trade-name');
        $device = $request->header('device') ?? "android";
        $codeKey = strtolower($trade) . "_" . $device;

        if ($device == 'huawei' && strtolower($trade) == 'cinestar') {
            $device = 'gallery';
        }

        if (strpos($userAgent, "CFNetwork") === false && strpos($userAgent, "okhttp") !== false) {
            $actualVersionAppCode = Setting::where('code_key', $codeKey . '_version_code')->first();
            $headerVersionAppCode = $request->header($device . '-version-code');

            if (!isset($headerVersionAppCode) || $headerVersionAppCode < $actualVersionAppCode->config) {
                // \Log::info($request->header());
                \Log::info("Enviado mensaje para actualizar");
                return $this->errorResponse(['status' => 426, 'message' => "Por favor, actualice su aplicación para disfrutar de mejores funcionalidades."], 426);
            } else if($headerVersionAppCode > $actualVersionAppCode->config) {
                $actualVersionAppCode->update([
                    "config" => $headerVersionAppCode
                ]);
            }
        }

        return $next($request);
    }
}
