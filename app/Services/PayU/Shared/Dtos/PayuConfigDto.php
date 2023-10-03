<?php


namespace App\Services\PayU\Shared\Dtos;

use App\Enums\BusinessName;
use App\Helpers\FunctionHelper;

class PayuConfigDto
{
    private $apiKey;
    private $apiLogin;
    private $accountId;
    private $merchantId;
    private $test;
    private $url_transaction_process;
    private $url_queries;

    public function __construct($businessName)
    {
        $this->setData($businessName);
    }

    private function setData($businessName)
    {
        $businessName = trim(strtolower($businessName));
        $config = FunctionHelper::getValueSystemConfigurationByKey(null, true);

        if($businessName == trim(strtolower(BusinessName::TOP_RANK)))
        {
            $this->apiKey = $config['payu_top_rank_api_key'];
            $this->apiLogin = $config['payu_top_rank_api_login'];
            $this->accountId = $config['payu_top_rank_account_id'];
            $this->merchantId = $config['payu_top_rank_merchant_id'];
        } else if($businessName == trim(strtolower(BusinessName::STAR_PLAZA))) {
            $this->apiKey = $config['payu_star_plaza_api_key'];
            $this->apiLogin = $config['payu_star_plaza_api_login'];
            $this->accountId = $config['payu_star_plaza_account_id'];
            $this->merchantId = $config['payu_star_plaza_merchant_id'];
        }

        $this->test = $config['payu_test'];
        $this->url_transaction_process = $config['payu_url_transaction_process'];
        $this->url_queries = $config['payu_url_queries'];
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return mixed
     */
    public function getApiLogin()
    {
        return $this->apiLogin;
    }

    /**
     * @return mixed
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @return mixed
     */
    public function getUrlTransactionProcess()
    {
        return $this->url_transaction_process;
    }

    /**
     * @return mixed
     */
    public function getUrlQueries()
    {
        return $this->url_queries;
    }

}
