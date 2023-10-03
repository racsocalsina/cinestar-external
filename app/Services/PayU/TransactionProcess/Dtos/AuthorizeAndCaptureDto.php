<?php


namespace App\Services\PayU\TransactionProcess\Dtos;


class AuthorizeAndCaptureDto
{
    private $businessName;
    private $referenceCode;
    private $description;
    private $amount;

    private $buyerId;
    private $buyerFullName;
    private $buyerEmail;
    private $buyerContactPhone;
    private $buyerDocumentNumber;

    private $payerId;
    private $payerFullName;
    private $payerEmail;
    private $payerContactPhone;
    private $payerDocumentNumber;
    private $payerBillingAddressStreet1;

    private $paymentMethod;
    private $deviceSessionId;

    private $ccTokenId;
    private $ccSecurityCode;
    private $ccNumber;
    private $ccExpirationDate;
    private $ccName;

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
    public function getReferenceCode()
    {
        return  $this->referenceCode;
    }

    /**
     * @param mixed $referenceCode
     */
    public function setReferenceCode($referenceCode): void
    {
        $this->referenceCode = $referenceCode;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * @param mixed $buyerId
     */
    public function setBuyerId($buyerId): void
    {
        $this->buyerId = $buyerId;
    }

    /**
     * @return mixed
     */
    public function getBuyerFullName()
    {
        return $this->buyerFullName;
    }

    /**
     * @param mixed $buyerFullName
     */
    public function setBuyerFullName($buyerFullName): void
    {
        $this->buyerFullName = $buyerFullName;
    }

    /**
     * @return mixed
     */
    public function getBuyerEmail()
    {
        return $this->buyerEmail;
    }

    /**
     * @param mixed $buyerEmail
     */
    public function setBuyerEmail($buyerEmail): void
    {
        $this->buyerEmail = $buyerEmail;
    }

    /**
     * @return mixed
     */
    public function getBuyerContactPhone()
    {
        return $this->buyerContactPhone;
    }

    /**
     * @param mixed $buyerContactPhone
     */
    public function setBuyerContactPhone($buyerContactPhone): void
    {
        $this->buyerContactPhone = $buyerContactPhone;
    }

    /**
     * @return mixed
     */
    public function getBuyerDocumentNumber()
    {
        return $this->buyerDocumentNumber;
    }

    /**
     * @param mixed $buyerDocumentNumber
     */
    public function setBuyerDocumentNumber($buyerDocumentNumber): void
    {
        $this->buyerDocumentNumber = $buyerDocumentNumber;
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
    public function getPayerFullName()
    {
        return $this->payerFullName;
    }

    /**
     * @param mixed $payerFullName
     */
    public function setPayerFullName($payerFullName): void
    {
        $this->payerFullName = $payerFullName;
    }

    /**
     * @return mixed
     */
    public function getPayerEmail()
    {
        return $this->payerEmail;
    }

    /**
     * @param mixed $payerEmail
     */
    public function setPayerEmail($payerEmail): void
    {
        $this->payerEmail = $payerEmail;
    }

    /**
     * @return mixed
     */
    public function getPayerContactPhone()
    {
        return $this->payerContactPhone;
    }

    /**
     * @param mixed $payerContactPhone
     */
    public function setPayerContactPhone($payerContactPhone): void
    {
        $this->payerContactPhone = $payerContactPhone;
    }

    /**
     * @return mixed
     */
    public function getPayerDocumentNumber()
    {
        return $this->payerDocumentNumber;
    }

    /**
     * @param mixed $payerDocumentNumber
     */
    public function setPayerDocumentNumber($payerDocumentNumber): void
    {
        $this->payerDocumentNumber = $payerDocumentNumber;
    }

    /**
     * @return mixed
     */
    public function getPayerBillingAddressStreet1()
    {
        return $this->payerBillingAddressStreet1;
    }

    /**
     * @param mixed $payerBillingAddressStreet1
     */
    public function setPayerBillingAddressStreet1($payerBillingAddressStreet1): void
    {
        $this->payerBillingAddressStreet1 = $payerBillingAddressStreet1;
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

    /**
     * @return mixed
     */
    public function getDeviceSessionId()
    {
        return $this->deviceSessionId;
    }

    /**
     * @param mixed $deviceSessionId
     */
    public function setDeviceSessionId($deviceSessionId): void
    {
        $this->deviceSessionId = $deviceSessionId;
    }

    /**
     * @return mixed
     */
    public function getCCNumber()
    {
        return $this->ccNumber;
    }

    /**
     * @param mixed $ccNumber
     */
    public function setCCNumber($ccNumber): void
    {
        $this->ccNumber = $ccNumber;
    }

    /**
     * @return mixed
     */
    public function getCCExpirationDate()
    {
        return "20" . $this->ccExpirationDate;
    }

    /**
     * @param mixed $ccExpirationDate
     */
    public function setCCExpirationDate($ccExpirationDate): void
    {
        $this->ccExpirationDate = $ccExpirationDate;
    }

    /**
     * @return mixed
     */
    public function getCCName()
    {
        return $this->ccName;
    }

    /**
     * @param mixed $ccName
     */
    public function setCCName($ccName): void
    {
        $this->ccName = $ccName;
    }

    /**
     * @return mixed
     */
    public function getCCSecurityCode()
    {
        return $this->ccSecurityCode;
    }

    /**
     * @param mixed $ccSecurityCode
     */
    public function setCCSecurityCode($ccSecurityCode): void
    {
        $this->ccSecurityCode = $ccSecurityCode;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param mixed $paymentMethod
     */
    public function setPaymentMethod($paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
}
