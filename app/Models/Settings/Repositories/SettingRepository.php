<?php


namespace App\Models\Settings\Repositories;

use App\Enums\GlobalEnum;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Models\Headquarters\Headquarter;
use App\Models\MovieTimes\MovieTime;
use App\Models\Settings\Setting;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SettingRepository implements SettingRepositoryInterface
{
    public function billboardDates()
    {
        return Setting::where('code_key', GlobalEnum::SETTINGS_CK_BILLBOARD_DATES)
            ->get(['config'])
            ->first();
    }

    public function getCommunitySystemVars($headquarterId)
    {
        return Setting::where('code_key', GlobalEnum::SETTINGS_CK_COMMUNITY)
            ->where('headquarter_id', $headquarterId)
            ->get(['config'])
            ->first();
    }

    public function billboardDatesNextReleases($id_movie)
    {
        $trade_name = Helper::getTradeNameHeader();
        $movieTime = MovieTime::whereDate('date_start', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->whereHas('headquarter', function ($query2) use ($trade_name) {
                return $query2->where('trade_name', $trade_name);
            });
        if (isset($id_movie)) {
            $movieTime->where('movie_id', $id_movie);
        }

        return $movieTime->get(['date_start'])->unique('date_start');
    }

    public function sync($body)
    {
        $codeKey = GlobalEnum::SETTINGS_CK_COMMUNITY;
        $action = $body['action'];
        $config = $body['data'] == null ? "{}" : $body['data'];

        if (is_array($config))
            $config = json_encode($config);

        $headquarter = Headquarter::where('api_url', $body['url'])->first();
        $data = Setting::where('code_key', $codeKey)->where('headquarter_id', $headquarter->id)->first();

        if ($action === GlobalEnum::ACTION_SYNC_INSERT || $action === GlobalEnum::ACTION_SYNC_UPDATE || $action === GlobalEnum::ACTION_SYNC_IMPORT) {

            $createdAt = now();

            if ($data)
                $createdAt = $data->created_at ? $data->created_at : now();

            DB::table('settings')
                ->updateOrInsert(
                    ['code_key' => $codeKey, 'headquarter_id' => $headquarter->id],
                    [
                        'code_key'       => $codeKey,
                        'headquarter_id' => $headquarter->id,
                        'config'         => $config,
                        'name'           => 'Variables del sistema de la bd de comunidad',
                        'created_at'     => $createdAt,
                        'updated_at'     => now()
                    ]
                );
        }
    }

    public function paymentGatewayResponse()
    {
        $data = Setting::where('code_key', GlobalEnum::SETTINGS_CK_PGR_TO_FREE)
            ->get(['config'])
            ->first();

        return $data ? $data->config : null;
    }

    public function getSystemConfiguration()
    {
        $data = Setting::where('code_key', GlobalEnum::SETTINGS_CK_SYSTEM_CONFIGURATION)
            ->get(['config'])
            ->first();

        return $data ? $data->config : null;
    }

    public function getErpSystemVars()
    {
        return Setting::with(['headquarter'])
            ->where('code_key', GlobalEnum::SETTINGS_CK_COMMUNITY)->get();
    }

    public function saveSystemConfiguration($body)
    {
        $codeKey = GlobalEnum::SETTINGS_CK_SYSTEM_CONFIGURATION;

        $data = Setting::where('code_key', $codeKey)->first();
        $createdAt = now();

        if ($data)
            $createdAt = $data->created_at ?? now();

        $config = json_encode([
            "system_days_to_delete"        => intval($body['system_days_to_delete']),
            "send_email_internal_errors"   => boolval($body['send_email_internal_errors']),
            "cinestar_support_email"       => FunctionHelper::removeWhiteSpaces($body['cinestar_support_email']),
            "movietime_support_email"      => FunctionHelper::removeWhiteSpaces($body['movietime_support_email']),
            "system_support_emails"        => FunctionHelper::removeWhiteSpaces($body['system_support_emails']),
            "fe_api_url_top_rank"          => FunctionHelper::removeWhiteSpaces($body['fe_api_url_top_rank']),
            "fe_token_top_rank"            => FunctionHelper::removeWhiteSpaces($body['fe_token_top_rank']),
            "fe_api_url_star_plaza"        => FunctionHelper::removeWhiteSpaces($body['fe_api_url_star_plaza']),
            "fe_token_star_plaza"          => FunctionHelper::removeWhiteSpaces($body['fe_token_star_plaza']),
            "payu_test"                    => boolval($body['payu_test']),
            "payu_url_transaction_process" => FunctionHelper::removeWhiteSpaces($body['payu_url_transaction_process']),
            "payu_url_queries"             => FunctionHelper::removeWhiteSpaces($body['payu_url_queries']),
            "payu_star_plaza_api_key"      => FunctionHelper::removeWhiteSpaces($body['payu_star_plaza_api_key']),
            "payu_star_plaza_api_login"    => FunctionHelper::removeWhiteSpaces($body['payu_star_plaza_api_login']),
            "payu_star_plaza_account_id"   => $body['payu_star_plaza_account_id'],
            "payu_star_plaza_merchant_id"  => $body['payu_star_plaza_merchant_id'],
            "payu_top_rank_api_key"        => FunctionHelper::removeWhiteSpaces($body['payu_top_rank_api_key']),
            "payu_top_rank_api_login"      => FunctionHelper::removeWhiteSpaces($body['payu_top_rank_api_login']),
            "payu_top_rank_account_id"     => $body['payu_top_rank_account_id'],
            "payu_top_rank_merchant_id"    => $body['payu_top_rank_merchant_id'],
            "max_minutes_to_buy"           => intval($body['max_minutes_to_buy']),
            "url_info_receipt"             => FunctionHelper::removeWhiteSpaces($body['url_info_receipt']),
            "accumulate_points"            => boolval($body['accumulate_points']),
        ]);

        DB::table('settings')
            ->updateOrInsert(
                ['code_key' => $codeKey],
                [
                    'code_key'   => $codeKey,
                    'config'     => $config,
                    'name'       => 'Variables del sistema',
                    'created_at' => $createdAt,
                    'updated_at' => now()
                ]
            );

        $data = Setting::where('code_key', GlobalEnum::SETTINGS_CK_SYSTEM_CONFIGURATION)
            ->get(['config'])
            ->first();

        return $data ? $data->config : null;
    }
}
