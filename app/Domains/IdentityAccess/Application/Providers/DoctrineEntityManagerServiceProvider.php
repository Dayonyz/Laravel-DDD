<?php

namespace App\Domains\IdentityAccess\Application\Providers;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\ServiceProvider;

class DoctrineEntityManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(EntityManagerInterface::class , function() {
            $connectionName = config('doctrine.connection');
            $connection = config("database.connections.$connectionName");

            if (!$connection)
                throw new \Exception("Invalid connection name '$connectionName'");

            $doctrineDbParams = [
                'driver' => config('doctrine.driver'),
                'host' => $connection['host'],
                'user' => $connection['username'],
                'password' => $connection['password'],
                'dbname' => $connection['database'],
                'port' => $connection['port'],
            ];

            $paths = config('doctrine.mappings-paths');

            $config = ORMSetup::createXMLMetadataConfiguration($paths, true);

            $connection = DriverManager::getConnection($doctrineDbParams, $config);
            return new EntityManager($connection, $config);
        });
    }
}
