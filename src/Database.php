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
    private $conn;

    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch (PDOException $e) {
            throw new StorageException('Connection error');
        }
    }

    public function createNote(array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $desc = $this->conn->quote($data['description']);
            $created = $this->conn->quote(date('Y-m-d H:i:s'));

            $query = "INSERT INTO notes (title, description, created) values ($title,$desc,$created )";
            dump($query);
            $this->conn->exec($query);

        }   catch(Throwable $e){
            throw new StorageException('Nie udało się utworzyć notatki',400,$e);

        }
    }
    public function getNotes(): array
    {
        try {
            $notes = [];
            $query = "SELECT id, title, created FROM notes";
            $result = $this->conn->query($query,PDO::FETCH_ASSOC);
            $notes = $result->fetchAll();
            return $notes;

        }   catch(Throwable $e){
            throw new StorageException('Nie udało się wyświetlić notatki',400,$e);

        }
    }

    public function getNote($id): array
    {
        try {
            $notes = [];
            $query = "SELECT id, title, description, created FROM notes where id = $id";
            $result = $this->conn->query($query,PDO::FETCH_ASSOC);
            $notes = $result->fetch();
            return $notes;

        }   catch(Throwable $e){
            throw new StorageException('Nie udało się wyświetlić notatki',400,$e);
        }

    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $user = $config['user'];
        $password = $config['password'];
        $this->conn = new PDO($dsn,$user,$password,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
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