<?php

declare(strict_types=1);

namespace App\model;

use App\Exception\StorageException;
use App\Exception\NotFoundException;
use ModelInterface;
use PDO;
use Throwable;

require_once('src/model/AbstractModel.php');
require_once('src/model/ModelInterface.php');

class NoteModel extends AbstractModel implements ModelInterface
{

    public function get(int $id): array
    {
        try {
            $query = "SELECT * FROM notes WHERE id = $id";
            $result = $this->conn->query($query);
            $note = $result->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać notatki', 400, $e);
        }

        if (!$note) {
            throw new NotFoundException("Notatka o id: $id nie istnieje");
        }

        return $note;
    }

    public function list(string $sortBy, string $sortOrder): array
    {
        try {
            if (!in_array($sortBy, ['created', 'title'])) {
                $sortBy = 'title';
            }

            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortOrder = 'desc';
            }

            $query = "SELECT id, title, created FROM notes ORDER BY $sortBy $sortOrder";

            $result = $this->conn->query($query);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać danych o notatkach', 400, $e);
        }
    }

    public function create(array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $created = $this->conn->quote(date('Y-m-d H:i:s'));

            $query = "
        INSERT INTO notes(title, description, created)
        VALUES($title, $description, $created)
      ";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się utworzyć nowej notatki', 400, $e);
        }
    }

    public function edit(int $id, array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);

            $query = "
        UPDATE notes
        SET title = $title, description = $description
        WHERE id = $id
      ";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się zaktualizować notetki', 400, $e);
        }
    }

    public function delete(int $id): void
    {
        try {
            $query = "DELETE FROM notes WHERE id = $id LIMIT 1";
            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się usunąć notatki', 400, $e);
        }
    }
}
