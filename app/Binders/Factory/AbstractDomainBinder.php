<?php

namespace App\Binders\Factory;

use App\Domains\Shared\Application\Enum\DomainEnum;
use Exception;
use Illuminate\Contracts\Foundation\Application;

abstract class AbstractDomainBinder implements DomainBinder
{
    private DomainEnum $domainEnum;
    private string $domainsPath;

    /**
     * @throws Exception
     */
    public function __construct(DomainEnum $domainEnum)
    {
        $this->setDomainsPath();
        $this->setDomain($domainEnum);
        $instance = $this;

        app()->singleton('binder', function () use ($instance) {
            return $instance;
        });
    }

    private function setDomain(DomainEnum $domainEnum)
    {
        $this->domainEnum = $domainEnum;
    }

    public function getDomain(): string
    {
       return $this->domainEnum->value;
    }

    public function getAllDomainsPath(): string
    {
        return base_path($this->domainsPath);
    }

    /**
     * @throws Exception
     */
    public function setDomainsPath(): void
    {
        $path = pathByNamespace('Domains');
        if (!$path) {
            throw new Exception('File composer.json does not contain Domains root namespace in the psr-4 section');
        }

        $this->domainsPath = $path;
    }

    public function getCurrentDomainPath(string $path = ''): string
    {
        return base_path("{$this->domainsPath}{$this->getDomain()}/$path");
    }

    public function bindFoldersWithDefaultStructure(Application $application)
    {
        $application->useAppPath($this->getCurrentDomainPath('Application'));
        $application->useBootstrapPath($this->getCurrentDomainPath('Framework/bootstrap'));
        $application->useConfigPath($this->getCurrentDomainPath('Framework/config'));
        $application->useStoragePath($this->getCurrentDomainPath('Framework/storage'));
        $application->useEnvironmentPath($this->getCurrentDomainPath('Framework'));
        $application->useDatabasePath($this->getCurrentDomainPath('Framework/database'));
        //useLangPath
        //usePublicPath
    }
}