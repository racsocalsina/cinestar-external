<?php


namespace App\Http\Controllers\Debugger\Payu;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayuWebCheckoutController extends Controller
{
    public function __construct()
    {
        if (!env('APP_DEBUG'))
            return abort(403);
    }

    public function index()
    {
        $data = [
            'api_key'        => '4Vj8eK4rloUd272L48hsrarnUA',
            'merchant_id'    => '508029',
            'reference_code' => Carbon::now()->timestamp,
            'amount'         => '10',
            'currency'       => 'PEN',
        ];

        $data['signature'] = $this->generateSignature($data);

        return view('debugger.payu.web-checkout', [
            'data' => $data
        ]);
    }

    private function generateSignature(array $data)
    {
        $value = "{$data['api_key']}~{$data['merchant_id']}~{$data['reference_code']}~{$data['amount']}~{$data['currency']}";
        return md5($value);
    }

    public function response(Request $request)
    {
        dd($request);
    }

    public function confirmation(Request $request)
    {
        dd($request);
    }
}
