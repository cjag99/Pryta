<?php

declare(strict_types=1);
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/User.php";
require_once "./src/Model/Repositories/Repository.php";
/**
 * Clase que representa un repositorio de usuarios.
 *
 * Hereda de {@link Repository} y se encarga de realizar operaciones CRUD sobre la tabla de usuarios.
 *
 * @package Pryta\Model\Repositories
 */
class UserRepository extends Repository
{
    /**
     * Constructor: recibe la conexión PDO (inyección de dependencias).
     * @param string $table_name Nombre de la tabla
     * @param PDO $connection Conexión a la base de datos
     */
    public function __construct(PDO $connection)
    {
        return parent::__construct("user", $connection);
    }


    /**
     * Lee todas las filas de la tabla de usuarios.
     *
     * @return array|null Un array asociativo con todos los usuarios o null si no hay resultados.
     */
    public function readAll(): array|null
    {
        $command = $this->connection->prepare("SELECT * FROM user");
        $command->execute();
        $data = $command->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    /**
     * Lee una fila de la tabla de usuarios por su id.
     *
     * @param int $userID Identificador del usuario a leer.
     *
     * @return ?object Un objeto de la clase User si se encuentra, null en caso contrario.
     */
    public function readOne(int $userID): ?object
    {
        // Preparar consulta para buscar por id y devolver la fila como asociado
        $command = $this->connection->prepare("SELECT * FROM user WHERE id = :userID");
        $command->bindParam(':userID', $userID);
        $command->execute();
        $data = $command->fetch(PDO::FETCH_ASSOC);
        // Si no existe devolvemos null, si existe mapeamos a la entidad User
        if (!$data) {
            return null;
        } else {
            return User::fromDatabase(
                $data['id'],
                $data['username'],
                $data['name'],
                $data['surname'],
                $data['passwd'],
                $data['role'],
                $data['email'],
                $data['verified'] === 1 ? true : false,
                $data['active'] === 1 ? true : false,
                $data['team_id'] ?? null
            );
        }
    }

    /**
     * Lee todas las filas de la tabla de usuarios con solo id, nombre y apellido.
     *
     * @return array|null Un array asociativo con todos los usuarios o null si no hay resultados.
     */
    public function readIdNames(): array|null
    {
        $command = $this->connection->prepare("SELECT id, name, surname FROM $this->table_name");
        $command->execute();
        $data = $command->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    /**
     * Lee todas las filas de la tabla de usuarios que pertenecen a un equipo en particular con solo id, nombre y apellido.
     *
     * @param int $teamId Identificador del equipo al que pertenecen los usuarios.
     *
     * @return array|null Un array asociativo con todos los usuarios del equipo o null si no hay resultados.
     */
    public function readIdNamesByTeamId(int $teamId): array|null
    {
        $command = $this->connection->prepare("SELECT id, name, surname FROM $this->table_name WHERE team_id = :teamId");
        $command->bindParam(':teamId', $teamId);
        $command->execute();
        $data = $command->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    /**
     * Devuelve un usuario por su nombre completo (nombre y apellido).
     *
     * @param string $fullName Nombre completo del usuario a buscar.
     *
     * @return ?User La entidad User si se encuentra, null en caso contrario.
     */
    public function getByFullName($fullName)
    {
        // Separa el nombre completo en nombre y apellido (solo la primera separación)
        [$name, $surname] = explode(' ', $fullName, 2);
        $command = $this->connection->prepare(
            "SELECT * FROM user WHERE name = :name AND surname = :surname"
        );
        $command->execute([
            'name' => $name,
            'surname' => $surname
        ]);
        $data = $command->fetch(PDO::FETCH_ASSOC);
        // Devuelve null si no hay coincidencia o la entidad User si existe
        return (!$data)
            ? null
            : new User(
                $data['id'],
                $data['username'],
                $data['name'],
                $data['surname'],
                $data['email'],
                $data['password'],
                $data['active'] ?? true,
                $data['verified'] ?? false,
                $data['team_id'] ?? null
            );
    }

    /**
     * Comprueba si existe un usuario en la base de datos con el id indicado.
     *
     * @param User $user La entidad User a comprobar.
     *
     * @return bool True si existe al menos una fila con el id indicado, false en caso contrario.
     */
    public function userExists(User $user): bool
    {
        // Comprueba existencia por id (devuelve true si hay al menos una fila)
        $command = $this->connection->prepare(
            "SELECT COUNT(*) FROM user WHERE id = :userId"
        );
        $command->execute(['userId' => $user->getId()]);
        return $command->fetchColumn() > 0;
    }

    /**
     * Inserta un nuevo usuario en la tabla `user`.
     * Devuelve true si la inserción tuvo éxito, false en caso contrario.
     *
     * @param User $user La entidad User a insertar.
     *
     * @return bool True si la inserción tuvo éxito, false en caso contrario.
     */
    public function create(object $user): bool
    {
        // Inserta un nuevo usuario en la tabla `user`.
        // Devuelve true si la inserción tuvo éxito, false en caso contrario.
        $sql = "INSERT INTO user
            (id, username, name, surname, passwd, role, email, verified, active, team_id)
            VALUES
            (:id, :username, :name, :surname, :passwd, :role, :email, :verified, :active, :team_id)";

        $stmt = $this->connection->prepare($sql);

        $teamId = $user->getTeamId();
        $params = [
            'id'           => $user->getId(),
            'username'     => $user->getUsername(),
            'name'         => $user->getName(),
            'surname'      => $user->getSurname(),
            'passwd'       => $user->getPasswd(),
            'role'         => $user->getRole(),
            'email'        => $user->getEmail(),
            'verified'     => $user->isVerified() ? 1 : 0,
            'active'       => $user->isActive() ? 1 : 0,
            'team_id'  => ($user->getTeamId() !== null) ? $user->getTeamId() : null,
        ];

        return $stmt->execute($params);
    }
    /**
     * Comprueba credenciales de un usuario y devuelve la entidad User asociada si son válidos.
     *
     * @param string $username Nombre de usuario a comprobar.
     * @param string $passwd Contraseña a comprobar.
     *
     * @return User|false La entidad User asociada si las credenciales son válidas, false en caso contrario.
     */
    public function login(string $username, string $passwd): User|false
    {
        // Buscar usuario por nombre de usuario
        $command = $this->connection->prepare(
            "SELECT * FROM user WHERE username = :username"
        );
        $command->execute([
            'username' => $username,
        ]);
        $data = $command->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            // No existe usuario con ese username
            return false;
        }
        // Verificar contraseña usando password_verify contra el hash almacenado
        if (password_verify($passwd, $data['passwd'])) {
            // Construir la entidad User con los datos recuperados
            return new User(
                $data['id'],
                $data['username'],
                $data['name'],
                $data['surname'],
                $data['passwd'],
                $data['role'],
                $data['email'],
                $data['verified'] === '1' ? true : false,
                $data['active'] === '1' ? true : false,
                $data['team_id'] ?? null
            );
        } else {
            // Contraseña errónea
            return false;
        }
    }

    /**
     * Actualiza un usuario existente en la tabla `user`.
     *
     * @param User $entity La entidad User a actualizar.
     *
     * @return bool True si la actualización tuvo éxito, false en caso contrario.
     */
    public function update(object $entity): bool
    {
        $sql = "UPDATE user
            SET username = :username,
                name = :name,
                surname = :surname,
                passwd = :passwd,
                role = :role,
                email = :email,
                active = :active,
                verified = :verified,
                team_id = :team_id
            WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'id'           => $entity->getId(),
            'username'     => $entity->getUsername(),
            'name'         => $entity->getName(),
            'surname'      => $entity->getSurname(),
            'passwd'       => $entity->getPasswd(),
            'role'         => $entity->getRole(),
            'email'        => $entity->getEmail(),
            'active'       => $entity->isActive() ? 1 : 0,
            'verified'     => $entity->isVerified() ? 1 : 0,
            'team_id'      => $entity->getTeamId() ?? null
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un usuario de la tabla `user`.
     *
     * @param int $id El ID del usuario a eliminar.
     */
    public function delete(int $id): void
    {

        $sql = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        header("Location: index.php?controller=dashboard&action=list");
    }

    /**
     * Actualiza la información personal de un usuario en la tabla `user`.
     *
     * @param object $entity La entidad User a actualizar.
     *
     * @return bool True si la actualización tuvo éxito, false en caso contrario.
     */
    public function updateProfile(object $entity): bool
    {
        $sql = "UPDATE user
            SET username = :username,
                name = :name,
                surname = :surname,
                email = :email,
                passwd = :passwd
            WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'id'           => $entity->getId(),
            'username'     => $entity->getUsername(),
            'name'         => $entity->getName(),
            'surname'      => $entity->getSurname(),
            'email'        => $entity->getEmail(),
            'passwd'       => $entity->getPasswd(),
        ]);
        return $stmt->rowCount() > 0;
    }
}
