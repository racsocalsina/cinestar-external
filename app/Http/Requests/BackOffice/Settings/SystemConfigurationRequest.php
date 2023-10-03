<?php


namespace App\Http\Requests\BackOffice\Settings;


use App\Rules\CheckMultiplesEmails;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SystemConfigurationRequest extends FormRequest
{
    use ApiResponser;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'system_days_to_delete'        => 'required|int',
            'send_email_internal_errors'   => 'required|boolean',
            'cinestar_support_email'       => ['required', 'string', new CheckMultiplesEmails()],
            'movietime_support_email'      => ['required', 'string', new CheckMultiplesEmails()],
            'system_support_emails'        => ['required', 'string', new CheckMultiplesEmails()],
            'fe_api_url_top_rank'          => 'required|url',
            'fe_token_top_rank'            => 'required|string',
            'fe_api_url_star_plaza'        => 'required|url',
            'fe_token_star_plaza'          => 'required|string',
            'payu_test'                    => 'required|boolean',
            'payu_url_transaction_process' => 'required|url',
            'payu_url_queries'             => 'required|url',
            'payu_star_plaza_api_key'      => 'required|string',
            'payu_star_plaza_api_login'    => 'required|string',
            'payu_star_plaza_account_id'   => 'required|string',
            'payu_star_plaza_merchant_id'  => 'required|string',
            'payu_top_rank_api_key'        => 'required|string',
            'payu_top_rank_api_login'      => 'required|string',
            'payu_top_rank_account_id'     => 'required|string',
            'payu_top_rank_merchant_id'    => 'required|string',
            'max_minutes_to_buy'           => 'required|int|min:1',
            'url_info_receipt'             => 'required|url',
            'accumulate_points'            => 'required|boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(['status' => 422, 'message' => $validator->errors()->first()], 422)
        );
    }

    public function attributes()
    {
        return [
            'system_days_to_delete'        => 'dias transcurridos para eliminar',
            'send_email_internal_errors'   => 'enviar emails de errores de la sede',
            'cinestar_support_email'       => 'correos de soporte (Cinestar)',
            'movietime_support_email'      => 'correos de soporte (Movietime)',
            'system_support_emails'        => 'correos del administrador del sistema',
            'fe_api_url_top_rank'          => 'dirección url de top rank',
            'fe_token_top_rank'            => 'token de top rank',
            'fe_api_url_star_plaza'        => 'dirección url de star plaza',
            'fe_token_star_plaza'          => 'token de star plaza',
            'payu_test'                    => 'modo de pruebas de PayU',
            'payu_url_transaction_process' => 'url transaction process DE PayU',
            'payu_url_queries'             => 'url queries de PayU',
            'payu_star_plaza_api_key'      => 'api key de star plaza',
            'payu_star_plaza_api_login'    => 'api login de de star plaza',
            'payu_star_plaza_account_id'   => 'account id de star plaza',
            'payu_star_plaza_merchant_id'  => 'merchant id de star plaza',
            'payu_top_rank_api_key'        => 'api key de top rank',
            'payu_top_rank_api_login'      => 'api login de top rank',
            'payu_top_rank_account_id'     => 'account id de top rank',
            'payu_top_rank_merchant_id'    => 'merchant id de top rank',
            'max_minutes_to_buy'           => 'minutos maximos para la compra de boletos',
            'url_info_receipt'             => 'link para ver comprobante electronico',
            'accumulate_points'             => 'acumular puntos',
        ];
    }
}
