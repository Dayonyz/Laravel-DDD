<?php

namespace App\Domains\Shared\Application\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

abstract class CreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $database = Config::get('database.connections.pgsql.database');
        $host = Config::get('database.connections.pgsql.host');
        $port = Config::get('database.connections.pgsql.port');
        $username = Config::get('database.connections.pgsql.username');
        $password = Config::get('database.connections.pgsql.password');

        Config::set('database.connections.pgsql.database', 'postgres');

        try {
            $pdo = new \PDO("pgsql:host=$host;port=$port;dbname=postgres", $username, $password);
            $result = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$database'");

            if (!$result->fetch()) {
                $pdo->exec("CREATE DATABASE \"$database\" WITH OWNER \"$username\" ENCODING 'UTF8'");
                $this->info("Database '$database' created successfully.");
            } else {
                $this->info("Database '$database' already exists.");
            }
        } catch (\Exception $e) {
            $this->error("Failed to create database '$database': " . $e->getMessage());
            return 1;
        }

        Config::set('database.connections.pgsql.database', $database);

        return 0;
    }
}
