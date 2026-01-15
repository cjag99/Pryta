<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/User.php";
require_once "./src/Model/Repositories/Repository.php";
/**
 * Repositorio para operaciones sobre la tabla `user`.
 *
 * Métodos principales:
 * - getById: devuelve un usuario por su id o null si no existe.
 * - getByFullName: busca por nombre y apellido.
 * - insert: inserta un nuevo usuario (devuelve true/false).
 * - login: busca por username y verifica la contraseña.
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


    public function readAll(): array|null
    {
        $command = $this->connection->prepare("SELECT * FROM user");
        $command->execute();
        $data = $command->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
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
                $data['email'],
                $data['password'],
                $data['active'] ?? true,
                $data['verified'] ?? false,
                $data['team_id'] ?? null
            );
        }
    }

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

    public function userExists(User $user): bool
    {
        // Comprueba existencia por id (devuelve true si hay al menos una fila)
        $command = $this->connection->prepare(
            "SELECT COUNT(*) FROM user WHERE id = :userId"
        );
        $command->execute(['userId' => $user->getId()]);
        return $command->fetchColumn() > 0;
    }

    public function create(object $user): bool
    {
        // Inserta un nuevo usuario en la tabla `user`.
        // Devuelve true si la inserción tuvo éxito, false en caso contrario.
        $sql = "INSERT INTO user
            (id, username, name, surname, passwd, role, email, verified, active, team_id)
            VALUES
            (:id, :username, :name, :surname, :passwd, :role, :email, :verified, :active, :team_id)";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            'id'           => $user->getId(),
            'username'     => $user->getUsername(),
            'name'         => $user->getName(),
            'surname'      => $user->getSurname(),
            'passwd'       => $user->getPasswd(),
            'role'         => $user->getRole(),
            'email'        => $user->getEmail(),
            'verified'     => $user->isVerified() ? 1 : 0,
            'active'       => $user->isActive() ? 1 : 0,
            'team_id'      => $user->getTeamId() ?? null
        ]);
    }
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
                $data['email'],
                $data['passwd'],
                $data['active'] ?? true,
                $data['verified'] ?? false,
                $data['team_id'] ?? null
            );
        } else {
            // Contraseña errónea
            return false;
        }
    }

    public function update(object $entity): bool
    {
        $sql = "UPDATE user
            SET username = :username,
                name = :name,
                surname = :surname,
                email = :email,
                active = :active,
                verified = :verified,
                team_id = :team_id
            WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            'id'           => $entity->getId(),
            'username'     => $entity->getUsername(),
            'name'         => $entity->getName(),
            'surname'      => $entity->getSurname(),
            'email'        => $entity->getEmail(),
            'active'       => $entity->isActive() ? 1 : 0,
            'verified'     => $entity->isVerified() ? 1 : 0,
            'team_id'      => $entity->getTeamId() ?? null
        ]);
    }

    public function delete(int $id): void
    {

        $sql = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
