<?php

namespace App\Binders\Factory;

use App\Binders\BinderDomainBinder;
use App\Binders\DealDomainBinder;
use App\Binders\SharedDomainBinder;
use App\Binders\IdentityAccessDomainBinder;
use App\Domains\Shared\Application\Enum\DomainEnum;

class DomainBinderFactory
{
    private DomainEnum $domain;

    private function __construct(DomainEnum $domain)
    {
        $this->setDomain($domain);
    }

    private function __clone(): void
    {
        // TODO: Implement __clone() method.
    }

    protected static function defaultBinder(): string
    {
        return SharedDomainBinder::class;
    }

    protected static function binders(): array
    {
        return [
            DomainEnum::IDENTITY_ACCESS->value => IdentityAccessDomainBinder::class,
            DomainEnum::DEAL->value => DealDomainBinder::class,
            DomainEnum::BINDER->value => BinderDomainBinder::class
        ];
    }

    private function setDomain(DomainEnum $domain): void
    {
        $this->domain = $domain;
    }

    public function makeBinder(): DomainBinder
    {
        $class = static::defaultBinder();

        if ($this->domainBinderExists() && isset(static::binders()[$this->domain->value])) {
            $class = static::binders()[$this->domain->value];
        }


        return new $class($this->domain);
    }

    private  function domainBinderExists(): bool
    {
        return file_exists(base_path("/app/Binders/{$this->domain->value}DomainBinder.php"));
    }

    public static function getInstance(DomainEnum $domain): static
    {
        return new static($domain);
    }
}