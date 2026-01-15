<?php

abstract class Repository
{
    public function __construct(
        protected string $table_name,
        protected PDO $connection
    ) {}
    abstract public function create(object $entity): bool;
    abstract public function readAll(): array|null;
    abstract public function readOne(int $id): object|null;
    abstract public function update(object $entity): bool;
    abstract public function delete(int $id): void;

    public function getTableName(): string
    {
        return $this->table_name;
    }
}
