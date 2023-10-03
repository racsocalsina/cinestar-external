<?php


namespace App\Services\PayU\Shared\Helpers;


use App\Services\PayU\Shared\Enums\ProcessType;
use App\Services\PayU\Shared\Exceptions\PayuException;

class PostRequestHelper
{
    public static function postRequest($processType, $url, $jsonData)
    {
        \Log::info("url " . $url);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            //CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
            CURLOPT_POSTFIELDS     => $jsonData
        ));
        $jsonResponse = curl_exec($curl);
        $curlClone = $curl;

        PostRequestHelper::checkErrors($processType, $jsonResponse, $curlClone);
        curl_close($curl);

        return $jsonResponse;
    }

    private static function checkErrors($processType, $jsonResponse, $curl)
    {
        if ($jsonResponse == false) {
            throw new PayuException("PayU no responde", 500);
        }

        if (!curl_errno($curl)) {
            $info = curl_getinfo($curl);
            $code = $info['http_code'];
            if ($code != 201 && $code != 200) {

                if ($code >= 500 && $code <= 599) {
                    throw new PayuException("Payu ha retornado un error de su servidor con estado {$code}", $code);
                }

                throw new PayuException(PostRequestHelper::getErrorMessageFromStringResponse($jsonResponse), $code);
            }
        }

        \Log::info($jsonResponse);

        PostRequestHelper::checkResponseData($processType, $jsonResponse);
    }


    private static function checkResponseData($processType, $jsonResponse): void
    {
        try {
            $responseData = json_decode($jsonResponse);

            if ($processType == ProcessType::TOKENIZATION) {
                if ($responseData->code != "SUCCESS")
                    throw new PayuException(self::getErrorMessageFromStringResponse($jsonResponse));
            } else {
                if ($responseData->code == "ERROR")
                    throw new PayuException(self::getErrorMessageFromStringResponse($jsonResponse));

                if (!isset($responseData->transactionResponse))
                    throw new PayuException(self::getErrorMessageFromStringResponse($jsonResponse));


                if (!isset($responseData->transactionResponse->state))
                    throw new PayuException(self::getErrorMessageFromStringResponse($jsonResponse));

                if ($responseData->transactionResponse->state != "APPROVED")
                    throw new PayuException(self::getErrorMessageFromStringResponse($jsonResponse));
            }
        } catch (\Exception $exception) {
            throw new PayuException($exception->getMessage(), $exception->getCode());
        }
    }

    private static function getErrorMessageFromStringResponse($stringResponse): string
    {
        $responseData = json_decode($stringResponse);

        if (isset($responseData->error))
            return $responseData->error;

        if (isset($responseData->transactionResponse))
            if (isset($responseData->transactionResponse->responseMessage))
                return $responseData->transactionResponse->responseMessage;

        if (isset($responseData->transactionResponse))
            if (isset($responseData->transactionResponse->paymentNetworkResponseErrorMessage))
                return $responseData->transactionResponse->paymentNetworkResponseErrorMessage;

        if (isset($responseData->transactionResponse))
            if (isset($responseData->transactionResponse->responseCode))
                return self::getErrorMessageFromResponseCode($responseData->transactionResponse->responseCode);

        return 'Se ha producido un error con PayU';
    }

    private static function getErrorMessageFromResponseCode($responseCode): string
    {
        switch ($responseCode) {
            case "PAYMENT_NETWORK_REJECTED":
                return 'Transacción rechazada por entidad financiera';
            case "ENTITY_DECLINED":
                return 'Transacción rechazada por el banco';
            case "INSUFFICIENT_FUNDS":
                return 'Fondos insuficientes';
            case "INVALID_CARD":
                return 'Tarjeta inválida';
            case "CONTACT_THE_ENTITY":
                return 'Contactar entidad financiera';
            case "BANK_ACCOUNT_ACTIVATION_ERROR":
                return 'Débito automático no permitido';
            case "BANK_ACCOUNT_NOT_AUTHORIZED_FOR_AUTOMATIC_DEBIT":
                return 'Débito automático no permitido';
            case "INVALID_AGENCY_BANK_ACCOUNT":
                return 'Débito automático no permitido';
            case "INVALID_BANK_ACCOUNT":
                return 'Débito automático no permitido';
            case "INVALID_BANK":
                return 'Débito automático no permitido';
            case "EXPIRED_CARD":
                return 'Tarjeta vencida';
            case "RESTRICTED_CARD":
                return 'Tarjeta restringida';
            case "INVALID_EXPIRATION_DATE_OR_SECURITY_CODE":
                return 'Fecha de expiración o código de seguridad inválidos';
            case "REPEAT_TRANSACTION":
                return 'Reintentar pago';
            case "INVALID_TRANSACTION":
                return 'Transacción inválida';
            case "EXCEEDED_AMOUNT":
                return 'El valor excede el máximo permitido por la entidad';
            case "ABANDONED_TRANSACTION":
                return 'Transacción abandonada por el pagador';
            case "CREDIT_CARD_NOT_AUTHORIZED_FOR_INTERNET_TRANSACTIONS":
                return 'Tarjeta no autorizada para comprar por internet';
            case "ANTIFRAUD_REJECTED":
                return 'Transacción rechazada por sospecha de fraude';
            case "BANK_FRAUD_REJECTED":
                return 'Transacción rechazada debido a sospecha de fraude en la entidad financiera';
            case "DIGITAL_CERTIFICATE_NOT_FOUND":
                return 'Certificado digital no encotnrado';
            case "BANK_UNREACHABLE":
                return 'Error tratando de cominicarse con el banco';
            case "ENTITY_MESSAGING_ERROR":
                return 'Error comunicándose con la entidad financiera';
            case "NOT_ACCEPTED_TRANSACTION":
                return 'Transacción no permitida al tarjetahabiente';
            case "PAYMENT_NETWORK_NO_CONNECTION":
                return 'No fue posible establecer comunicación con la entidad financiera';
            case "PAYMENT_NETWORK_NO_RESPONSE":
                return 'No se recibió respuesta de la entidad financiera';
            case "EXPIRED_TRANSACTION":
                return 'Transacción expirada';
            case "PENDING_TRANSACTION_REVIEW":
                return 'Transacción en validación manual';
            case "PENDING_TRANSACTION_CONFIRMATION":
                return 'Recibo de pago generado. En espera de pago';
            case "PENDING_TRANSACTION_TRANSMISSION":
                return 'Transacción no permitida';
            case "PENDING_PAYMENT_IN_ENTITY":
                return 'Recibo de pago generado. En espera de pago';
            case "PENDING_PAYMENT_IN_BANK":
                return 'Recibo de pago generado. En espera de pago';
            case "PENDING_AWAITING_PSE_CONFIRMATION":
                return 'En espera de confirmación de PSE';
            case "PENDING_NOTIFYING_ENTITY":
                return 'Recibo de pago generado. En espera de pago';
            default:
                return $responseCode;
        }
    }
}
