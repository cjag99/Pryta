<?php

/**
 * Clase abstracta para implementar los métodos de acceso a la base de datos.
 * 
 * @package src\Model\Repositories
 */
abstract class Repository
{
    /**
     * Constructor del repositorio.
     *
     * Recibe el nombre de la tabla y una conexión a la base de datos.
     *
     * @param string $table_name El nombre de la tabla asociada a este repositorio.
     * @param PDO $connection La conexión a la base de datos.
     */
    public function __construct(
        protected string $table_name,
        protected PDO $connection
    ) {}
    /**
     * Crea un nuevo registro en la base de datos con la información proporcionada en el objeto $entity.
     * 
     * @param object $entity El objeto que contiene la información a insertar en la base de datos.
     * @return bool True si se pudo insertar el registro, false en caso contrario.
     */
    abstract public function create(object $entity): bool;

    /**
     * Devuelve una lista con todos los registros de la tabla asociada a este repositorio.
     * 
     * @return array|null Un array con todos los registros de la tabla o null si no hay registros.
     */
    abstract public function readAll(): array|null;

    /**
     * Devuelve un registro de la tabla asociada a este repositorio con el id proporcionado.
     * 
     * @param int $id El id del registro a buscar.
     * @return object|null El registro con el id proporcionado o null si no existe.
     */
    abstract public function readOne(int $id): object|null;

    /**
     * Actualiza un registro en la base de datos con la información proporcionada en el objeto $entity.
     * 
     * @param object $entity El objeto que contiene la información a actualizar en la base de datos.
     * @return bool True si se pudo actualizar el registro, false en caso contrario.
     */
    abstract public function update(object $entity): bool;

    /**
     * Elimina un registro de la tabla asociada a este repositorio con el id proporcionado.
     * 
     * @param int $id El id del registro a eliminar.
     * @return void
     */
    abstract public function delete(int $id): void;

    /**
     * Devuelve el nombre de la tabla que se utiliza en este repositorio.
     * @return string El nombre de la tabla.
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }
}
