<?php


namespace App\Services\PayU\TransactionProcess\Actions;


use App\Helpers\FunctionHelper;
use App\Models\PaymentGateways\PaymentGatewayInfo;
use App\Services\PayU\Shared\Dtos\PayuConfigDto;
use App\Services\PayU\Shared\Enums\ProcessType;
use App\Services\PayU\Shared\Helpers\PostRequestHelper;
use App\Services\PayU\TransactionProcess\Dtos\AuthorizeAndCaptureDto;

class AuthorizationAndCapture
{
    private AuthorizeAndCaptureDto $dto;
    private PayuConfigDto $config;

    private $jsonRequest;
    private $jsonData;

    public function execute(AuthorizeAndCaptureDto $dto)
    {
        $this->dto = $dto;
        $this->buildJsonData();
        return $this->postRequest();
    }

    private function buildJsonData()
    {
        $this->config = new PayuConfigDto($this->dto->getBusinessName());

        $requestData = array(
            'language'    => 'es',
            'command'     => 'SUBMIT_TRANSACTION',
            'merchant'    => array(
                'apiKey'   => $this->config->getApiKey(),
                'apiLogin' => $this->config->getApiLogin()
            ),
            'transaction' => array(
                'order'             => array(
                    'accountId'        => $this->config->getAccountId(),
                    'referenceCode'    => $this->dto->getReferenceCode(),
                    'description'      => $this->dto->getDescription(),
                    'language'         => 'es',
                    'signature'        => $this->generateSignature(),
                    'additionalValues' => array(
                        'TX_VALUE' => array(
                            'value'    => $this->dto->getAmount(),
                            'currency' => 'PEN'
                        ),
                    ),
                    'buyer'            => array(
                        'merchantBuyerId' => $this->dto->getBuyerId(),
                        'fullName'        => $this->dto->getBuyerFullName(),
                        'emailAddress'    => $this->dto->getBuyerEmail(),
                        'contactPhone'    => $this->dto->getBuyerContactPhone(),
                        'dniNumber'       => $this->dto->getBuyerDocumentNumber(),
                    ),
                ),
                'payer'             => array(
                    'merchantPayerId' => $this->dto->getPayerId(),
                    'fullName'        => $this->dto->getPayerFullName(),
                    'emailAddress'    => $this->dto->getPayerEmail(),
                    'contactPhone'    => $this->dto->getPayerContactPhone(),
                    'dniNumber'       => $this->dto->getPayerDocumentNumber(),
                    'billingAddress'  => array(
                        'street1' => $this->dto->getPayerBillingAddressStreet1(),
                        'country' => 'PE',
                    )
                ),
                'creditCardTokenId' => $this->dto->getCCTokenId(),
                'creditCard' => array(
                    'number'             => $this->dto->getCCNumber(),
                    'securityCode'       => $this->dto->getCCSecurityCode(),
                    'expirationDate'     => $this->dto->getCCExpirationDate(),
                    'name'               => $this->dto->getCCName(),
                    'processWithoutCvv2' => "false",
                ),
                'type'              => 'AUTHORIZATION_AND_CAPTURE',
                'paymentMethod'     => $this->dto->getPaymentMethod(),
                'paymentCountry'    => 'PE',
                'deviceSessionId'   => $this->dto->getDeviceSessionId(),
                'ipAddress'         => FunctionHelper::getClientIpEnv(),
                'userAgent'         => $_SERVER['HTTP_USER_AGENT'],
            ),
            'test'        => $this->config->getTest()
        );

        if ($this->dto->getCCTokenId()) {
            unset($requestData['transaction']['creditCard']['number']);
            unset($requestData['transaction']['creditCard']['expirationDate']);
        } else {
            unset($requestData['transaction']['creditCardTokenId']);
        }

        $this->jsonData = json_encode($requestData);
        $this->saveRequest($requestData);
    }

    private function postRequest()
    {
        return PostRequestHelper::postRequest(ProcessType::TRANSACTION, $this->config->getUrlTransactionProcess(), $this->jsonData);
    }

    private function generateSignature()
    {
        $value = "{$this->config->getApiKey()}~{$this->config->getMerchantId()}~{$this->dto->getReferenceCode()}~{$this->dto->getAmount()}~PEN";
        return md5($value);
    }

    private function saveRequest(array $requestData)
    {
        if (isset($requestData['transaction']['creditCard'])) {
            $requestData['transaction']['creditCard']['number'] = "***";
            $requestData['transaction']['creditCard']['securityCode'] = "***";
            $requestData['transaction']['creditCard']['expirationDate'] = "***";
        }

        $this->jsonRequest = '[PGI_REQUEST] - ' . json_encode($requestData);

        PaymentGatewayInfo::where('reference_code', $this->dto->getReferenceCode())
            ->update([
                'request' => json_encode($requestData)
            ]);

    }
}
