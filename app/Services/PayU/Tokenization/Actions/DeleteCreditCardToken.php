<?php


namespace App\Services\PayU\Tokenization\Actions;


use App\Services\PayU\Shared\Dtos\PayuConfigDto;
use App\Services\PayU\Shared\Enums\ProcessType;
use App\Services\PayU\Shared\Helpers\PostRequestHelper;
use App\Services\PayU\Tokenization\Dtos\DeleteCreditCardDto;

class DeleteCreditCardToken
{
    private DeleteCreditCardDto $dto;
    private PayuConfigDto $config;

    private $jsonData;

    public function execute(DeleteCreditCardDto $dto)
    {
        $this->dto = $dto;
        $this->buildJsonData();
        return $this->postRequest();
    }

    private function buildJsonData()
    {
        $this->config = new PayuConfigDto($this->dto->getBusinessName());

        $data = array(
            'language'              => 'es',
            'command'               => 'REMOVE_TOKEN',
            'merchant'              => array(
                'apiKey'   => $this->config->getApiKey(),
                'apiLogin' => $this->config->getApiLogin()
            ),
            'removeCreditCardToken' => array(
                'payerId'           => $this->dto->getPayerId(),
                'creditCardTokenId' => $this->dto->getCCTokenId(),
            )
        );

        $this->jsonData = json_encode($data);
    }

    private function postRequest()
    {
        return PostRequestHelper::postRequest(ProcessType::TOKENIZATION, $this->config->getUrlTransactionProcess(), $this->jsonData);
    }

}
