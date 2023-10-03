<?php


namespace App\Http\Resources\BackOffice\Settings;


use Illuminate\Http\Resources\Json\JsonResource;

class SystemConfigurationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'system_days_to_delete'        => isset($this['system_days_to_delete']) ? $this['system_days_to_delete'] : null,
            'send_email_internal_errors'   => isset($this['send_email_internal_errors']) ? $this['send_email_internal_errors'] : null,
            'cinestar_support_email'       => isset($this['cinestar_support_email']) ? $this['cinestar_support_email'] : null,
            'movietime_support_email'      => isset($this['movietime_support_email']) ? $this['movietime_support_email'] : null,
            'system_support_emails'        => isset($this['system_support_emails']) ? $this['system_support_emails'] : null,
            'fe_api_url_top_rank'          => isset($this['fe_api_url_top_rank']) ? $this['fe_api_url_top_rank'] : null,
            'fe_token_top_rank'            => isset($this['fe_token_top_rank']) ? $this['fe_token_top_rank'] : null,
            'fe_api_url_star_plaza'        => isset($this['fe_api_url_star_plaza']) ? $this['fe_api_url_star_plaza'] : null,
            'fe_token_star_plaza'          => isset($this['fe_token_star_plaza']) ? $this['fe_token_star_plaza'] : null,
            'payu_test'                    => isset($this['payu_test']) ? $this['payu_test'] : null,
            'payu_url_transaction_process' => isset($this['payu_url_transaction_process']) ? $this['payu_url_transaction_process'] : null,
            'payu_url_queries'             => isset($this['payu_url_queries']) ? $this['payu_url_queries'] : null,
            'payu_star_plaza_api_key'      => isset($this['payu_star_plaza_api_key']) ? $this['payu_star_plaza_api_key'] : null,
            'payu_star_plaza_api_login'    => isset($this['payu_star_plaza_api_login']) ? $this['payu_star_plaza_api_login'] : null,
            'payu_star_plaza_account_id'   => isset($this['payu_star_plaza_account_id']) ? $this['payu_star_plaza_account_id'] : null,
            'payu_star_plaza_merchant_id'  => isset($this['payu_star_plaza_merchant_id']) ? $this['payu_star_plaza_merchant_id'] : null,
            'payu_top_rank_api_key'        => isset($this['payu_top_rank_api_key']) ? $this['payu_top_rank_api_key'] : null,
            'payu_top_rank_api_login'      => isset($this['payu_top_rank_api_login']) ? $this['payu_top_rank_api_login'] : null,
            'payu_top_rank_account_id'     => isset($this['payu_top_rank_account_id']) ? $this['payu_top_rank_account_id'] : null,
            'payu_top_rank_merchant_id'    => isset($this['payu_top_rank_merchant_id']) ? $this['payu_top_rank_merchant_id'] : null,
            'max_minutes_to_buy'           => isset($this['max_minutes_to_buy']) ? $this['max_minutes_to_buy'] : null,
            'url_info_receipt'             => isset($this['url_info_receipt']) ? $this['url_info_receipt'] : null,
            'accumulate_points'            => isset($this['accumulate_points']) ? $this['accumulate_points'] : null,
        ];
    }
}
