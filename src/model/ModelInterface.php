<?php


interface ModelInterface
{
    public function get(int $id): array;

    public function list(string $sortBy, string $sortOrder): array;

    public function create(array $data): void;

    public function edit(int $id, array $data): void;

    public function delete(int $id): void;
}
