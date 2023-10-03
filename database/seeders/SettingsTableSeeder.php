<?php

namespace Database\Seeders;

use App\Models\Settings\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(['code_key' => 'billboard_dates'], [
            'code_key'  => 'billboard_dates',
            'name'      => 'Configuración de fechas de inicio y fin de cartelera.',
            'config'    => json_decode('{"start_day":5,"end_day":4}')
        ]);

        Setting::updateOrCreate(['code_key' => 'payment_gateway_response_to_free'], [
            'code_key'  => 'payment_gateway_response_to_free',
            'name'      => 'Datos para cuando los pagos son cajeados (gratis).',
            'config'    => json_decode('{"code":"SUCCESS","error":null,"transactionResponse":{"orderId":0,"transactionId":"","state":"APPROVED","paymentNetworkResponseCode":"","paymentNetworkResponseErrorMessage":null,"trazabilityCode":"","authorizationCode":"","pendingReason":null,"responseCode":"APPROVED","errorCode":null,"responseMessage":"Aprobado y completado con exito","transactionDate":null,"transactionTime":null,"operationDate":null,"referenceQuestionnaire":null,"extraParameters":{"BANK_REFERENCED_CODE":"CREDIT"},"additionalInfo":null}}')
        ]);

        Setting::updateOrCreate(['code_key' => 'system_configuration'], [
            'code_key'  => 'system_configuration',
            'name'      => 'Variables del sistema.',
            'config'    => json_decode('{}')
        ]);
    }
}
