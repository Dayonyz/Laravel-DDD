<?php

namespace App\Domains\Shared\Domain\ValueObject;

use App\Domains\Shared\Domain\Assertions;

abstract class Address
{
    use Assertions;

    protected string $postCode;
    protected string $countryIso;
    protected string $city;
    protected string $addressLine1;
    protected ?string $addressLine2;

    public function __construct(
        string $postCode,
        string $countryIso,
        string $city,
        string $addressLine1,
        ?string $addressLine2 = null
    ){
        $this->setPostCode($postCode);
        $this->setCountryIso($countryIso);
        $this->setCity($city);
        $this->setAddressLine1($addressLine1);
        $this->setAddressLine2($addressLine2);
    }

    protected function setPostCode(string $postCode): void
    {
        $this->assertArgumentNotEmpty($postCode, 'Postcode can not be empty');
        $this->assertArgumentMinLength($postCode, 3, 'Post code minimal length is 3 characters');
        $this->assertArgumentMaxLength($postCode, 12, 'Post code maximal length is 12 characters');
        $this->postCode = $postCode;
    }

    public function postCode(): string
    {
        return $this->postCode;
    }

    protected function setCountryIso(string $countryIso): void
    {
        $this->assertArgumentNotEmpty($countryIso, 'Country can not be empty');
        $this->assertArgumentEqualsLength($countryIso, 2, 'Country code length must be 2 characters.');
        $this->countryIso = $countryIso;
    }

    public function countryIso(): string
    {
       return $this->countryIso;
    }

    protected function setCity(string $city): void
    {
        $this->assertArgumentNotEmpty($city, 'City can not be empty');
        $this->assertArgumentMinLength($city, 2, 'City name minimal length is 3 characters');
        $this->assertArgumentMaxLength($city, 256, 'City name minimal length is 256 characters');
        $this->city = $city;
    }

    public function city(): string
    {
        return $this->city;
    }

    protected function setAddressLine1(string $addressLine1): void
    {
        $this->assertArgumentNotEmpty($addressLine1, 'Address Line 1 can not be empty');
        $this->assertArgumentMinLength($addressLine1, 5, 'Address Line 1 minimal length is 5 characters');
        $this->addressLine1 = $addressLine1;
    }

    public function addressLine1(): string
    {
        return $this->addressLine1;
    }

    protected function setAddressLine2(?string $addressLine2): void
    {
        if (!empty($addressLine2)) {
            $this->assertArgumentMinLength($addressLine2, 5, 'Address Line 2 minimal length is 5 characters');
        }

        $this->addressLine2 = $addressLine2;
    }

    public function addressLine2(): ?string
    {
        return $this->addressLine2;
    }
}