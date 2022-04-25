<?php

namespace App\Service\PurchaseAddress;

use Symfony\Component\Validator\Constraints as Assert;

class PurchaseAddressInterface
{
    /**
     * @Assert\NotBlank
     */
    private $email;

    // First Address

    /**
     * @Assert\NotBlank
     */
    private $firstAddressFullname;

    /**
     * @Assert\NotBlank
     */
    private $firstAddressAddress;

    /**
     * @Assert\NotBlank
     */
    private $firstAddressPostalCode;

    /**
     * @Assert\NotBlank
     */
    private $firstAddressCity;

    /**
     * @Assert\NotBlank
     */
    private $firstAddressCountry = 'FR';

    /**
     * @Assert\NotBlank
     */
    private $firstAddressPhone;


    // Second Address

    /**
     * @Assert\NotBlank(groups={"hasBillingAddress"})
     */
    private $secondAddressFullname;

    /**
     * @Assert\NotBlank(groups={"hasBillingAddress"})
     */
    private $secondAddressAddress;

    /**
     * @Assert\NotBlank(groups={"hasBillingAddress"})
     */
    private $secondAddressPostalCode;

    /**
     * @Assert\NotBlank(groups={"hasBillingAddress"})
     */
    private $secondAddressCity;

    /**
     * @Assert\NotBlank(groups={"hasBillingAddress"})
     */
    private $secondAddressCountry = 'FR';

    /**
     * @Assert\Choice({true, false})
     */
    private $hasBillingAddress;

    /**
     * Get the value of firstAddressEmail
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of firstAddressEmail
     *
     * @return  self
     */
    public function setEmail(?string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of firstAddressFullname
     */
    public function getFirstAddressFullname()
    {
        return $this->firstAddressFullname;
    }

    /**
     * Set the value of firstAddressFullname
     *
     * @return  self
     */
    public function setFirstAddressFullname(?string $firstAddressFullname)
    {
        $this->firstAddressFullname = $firstAddressFullname;

        return $this;
    }

    /**
     * Get the value of firstAddressAddress
     */
    public function getFirstAddressAddress()
    {
        return $this->firstAddressAddress;
    }

    /**
     * Set the value of firstAddressAddress
     *
     * @return  self
     */
    public function setFirstAddressAddress(?string $firstAddressAddress)
    {
        $this->firstAddressAddress = $firstAddressAddress;

        return $this;
    }

    /**
     * Get the value of firstAddressPostalCode
     */
    public function getFirstAddressPostalCode()
    {
        return $this->firstAddressPostalCode;
    }

    /**
     * Set the value of firstAddressPostalCode
     *
     * @return  self
     */
    public function setFirstAddressPostalCode($firstAddressPostalCode)
    {
        $this->firstAddressPostalCode = $firstAddressPostalCode;

        return $this;
    }

    /**
     * Get the value of firstAddressCity
     */
    public function getFirstAddressCity()
    {
        return $this->firstAddressCity;
    }

    /**
     * Set the value of firstAddressCity
     *
     * @return  self
     */
    public function setFirstAddressCity(?string $firstAddressCity)
    {
        $this->firstAddressCity = $firstAddressCity;

        return $this;
    }

    /**
     * Get the value of firstAddressCountry
     */
    public function getFirstAddressCountry()
    {
        return $this->firstAddressCountry;
    }

    /**
     * Set the value of firstAddressCountry
     *
     * @return  self
     */
    public function setFirstAddressCountry(?string $firstAddressCountry)
    {
        $this->firstAddressCountry = $firstAddressCountry;

        return $this;
    }

    /**
     * Get the value of firstAddressPhone
     */
    public function getFirstAddressPhone()
    {
        return $this->firstAddressPhone;
    }

    /**
     * Set the value of firstAddressPhone
     *
     * @return  self
     */
    public function setFirstAddressPhone(?string $firstAddressPhone)
    {
        $this->firstAddressPhone = $firstAddressPhone;

        return $this;
    }

    /**
     * Get the value of secondAddressFullname
     */
    public function getSecondAddressFullname()
    {
        return $this->secondAddressFullname;
    }

    /**
     * Set the value of secondAddressFullname
     *
     * @return  self
     */
    public function setSecondAddressFullname(?string $secondAddressFullname)
    {
        $this->secondAddressFullname = $secondAddressFullname;

        return $this;
    }

    /**
     * Get the value of secondAddressAddress
     */
    public function getSecondAddressAddress()
    {
        return $this->secondAddressAddress;
    }

    /**
     * Set the value of secondAddressAddress
     *
     * @return  self
     */
    public function setSecondAddressAddress(?string $secondAddressAddress)
    {
        $this->secondAddressAddress = $secondAddressAddress;

        return $this;
    }

    /**
     * Get the value of secondAddressPostalCode
     */
    public function getSecondAddressPostalCode()
    {
        return $this->secondAddressPostalCode;
    }

    /**
     * Set the value of secondAddressPostalCode
     *
     * @return  self
     */
    public function setSecondAddressPostalCode(?string $secondAddressPostalCode)
    {
        $this->secondAddressPostalCode = $secondAddressPostalCode;

        return $this;
    }

    /**
     * Get the value of secondAddressCity
     */
    public function getSecondAddressCity()
    {
        return $this->secondAddressCity;
    }

    /**
     * Set the value of secondAddressCity
     *
     * @return  self
     */
    public function setSecondAddressCity(?string $secondAddressCity)
    {
        $this->secondAddressCity = $secondAddressCity;

        return $this;
    }

    /**
     * Get the value of secondAddressCountry
     */
    public function getSecondAddressCountry()
    {
        return $this->secondAddressCountry;
    }

    /**
     * Set the value of secondAddressCountry
     *
     * @return  self
     */
    public function setSecondAddressCountry(?string $secondAddressCountry)
    {
        $this->secondAddressCountry = $secondAddressCountry;

        return $this;
    }

    /**
     * Get the value of hasBillingAddress
     */
    public function getHasBillingAddress()
    {
        return $this->hasBillingAddress;
    }

    /**
     * Set the value of hasBillingAddress
     *
     * @return  self
     */
    public function setHasBillingAddress(bool $hasBillingAddress)
    {
        $this->hasBillingAddress = $hasBillingAddress;

        return $this;
    }
}
