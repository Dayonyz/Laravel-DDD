<?php

namespace App\Domains\Shared\Domain\ValueObject;

use App\Domains\Shared\Domain\Assertions;

abstract class Url
{
    use Assertions;

    protected ?string $url;
    protected ?string $default;

    public function __construct(?string $url = null, ?string $default = null)
    {
        $this->setUrl($url);
        $this->setDefault($default);
    }

    protected function setDefault(?string $default): void
    {
        if (!$this->url && empty($this->default)) {
            throw new \InvalidArgumentException('Default url value can not be empty');
        }

        if ($default) {
            $this->assertIsValidUrl($default);
        }

        $this->default = $default;
    }

    protected function setUrl(?string $url): void
    {
        if ($url) {
            $this->assertIsValidUrl($url);
            $this->assertArgumentMaxLength($url, 1024, 'Url maximal length is 1024 characters');
        }

        $this->url = $url;
    }

    public function toString(): string
    {
        return $this->url ? : $this->default;
    }
}