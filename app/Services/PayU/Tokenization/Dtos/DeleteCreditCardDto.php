<?php


namespace App\Services\PayU\Tokenization\Dtos;


class DeleteCreditCardDto
{
    private $businessName;
    private $payerId;
    private $ccTokenId;

    /**
     * @return mixed
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param mixed $businessName
     */
    public function setBusinessName($businessName): void
    {
        $this->businessName = $businessName;
    }

    /**
     * @return mixed
     */
    public function getPayerId()
    {
        return $this->payerId;
    }

    /**
     * @param mixed $payerId
     */
    public function setPayerId($payerId): void
    {
        $this->payerId = $payerId;
    }

    /**
     * @return mixed
     */
    public function getCCTokenId()
    {
        return $this->ccTokenId;
    }

    /**
     * @param mixed $ccTokenId
     */
    public function setCCTokenId($ccTokenId): void
    {
        $this->ccTokenId = $ccTokenId;
    }
}
