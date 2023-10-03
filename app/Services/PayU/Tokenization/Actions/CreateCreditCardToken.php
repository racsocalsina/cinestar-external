<?php


namespace App\Services\PayU\Tokenization\Actions;


use App\Services\PayU\Shared\Dtos\PayuConfigDto;
use App\Services\PayU\Shared\Enums\ProcessType;
use App\Services\PayU\Shared\Helpers\PostRequestHelper;
use App\Services\PayU\Tokenization\Dtos\CreateCreditCardTokenDto;

class CreateCreditCardToken
{
    private CreateCreditCardTokenDto $dto;
    private PayuConfigDto $config;

    private $jsonData;
    private $jsonResponse;
    private $extraData = [];

    public function execute(CreateCreditCardTokenDto $dto)
    {
        $this->dto = $dto;
        $this->buildJsonData();
        $this->postRequest();
        return $this->getReturnData();
    }

    private function buildJsonData()
    {
        $this->config = new PayuConfigDto($this->dto->getBusinessName());

        $data = array(
            'language'        => 'es',
            'command'         => 'CREATE_TOKEN',
            'merchant'        => array(
                'apiKey'   => $this->config->getApiKey(),
                'apiLogin' => $this->config->getApiLogin()
            ),
            'creditCardToken' => array(
                'payerId'              => $this->dto->getPayerId(),
                'name'                 => $this->dto->getName(),
                'identificationNumber' => $this->dto->getIdentificationNumber(),
                'paymentMethod'        => $this->dto->getPaymentMethod(),
                'number'               => $this->dto->getNumber(),
                'expirationDate'       => $this->dto->getExpirationDate(),
            )
        );

        $this->jsonData = json_encode($data);
    }

    private function postRequest()
    {
        $this->jsonResponse = PostRequestHelper::postRequest(ProcessType::TOKENIZATION, $this->config->getUrlTransactionProcess(), $this->jsonData);
        $this->setExtraData();

        return $this->jsonResponse;
    }

    private function setExtraData(): void
    {
        $responseData = json_decode($this->jsonResponse);

        $this->extraData['token'] = $responseData->creditCardToken->creditCardTokenId;
        $this->extraData['masked_number'] = $responseData->creditCardToken->maskedNumber;
    }

    private function getReturnData()
    {
        return $this->extraData;
    }
}
