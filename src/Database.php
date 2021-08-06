<?php

declare(strict_types=1);
namespace App;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;
use Throwable;

require_once("Exceptions/StorageException.php");

class Database
{
    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);

            $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
            $user = $config['user'];
            $password = $config['password'];
            $connection = new PDO($dsn,$user,$password);
            dump($connection);
        } catch (PDOException $e) {
            throw new StorageException('Connection error');

        }


    }
   private function validateConfig(array $config): void
    {
        if (empty($config['database'])
            || empty($config['host'])
            || empty($config['user'])
            || empty($config['password'])
        )   {
            throw new ConfigurationException('Storage configuration error');
        }
    }
}