<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/User.php";

/**
 * Repositorio para operaciones sobre la tabla `users`.
 *
 * Métodos principales:
 * - getById: devuelve un usuario por su id o null si no existe.
 * - getByFullName: busca por nombre y apellido.
 * - insert: inserta un nuevo usuario (devuelve true/false).
 * - login: busca por username y verifica la contraseña.
 */
class UserRepository
{
    private PDO $connection;
    /**
     * Constructor: recibe la conexión PDO (inyección de dependencias).
     * @param PDO $connection Conexión a la base de datos
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getById($userID)
    {
        // Preparar consulta para buscar por id y devolver la fila como asociado
        $command = $this->connection->prepare("SELECT * FROM users WHERE id = :userID");
        $command->bindParam(':userID', $userID);
        $command->execute();
        $data = $command->fetch(PDO::FETCH_ASSOC);
        // Si no existe devolvemos null, si existe mapeamos a la entidad User
        if (!$data) {
            return null;
        } else {
            return new User(
                $data['id'],
                $data['username'],
                $data['name'],
                $data['surname'],
                $data['email'],
                $data['password'],
                $data['active']
            );
        }
    }

    public function getByFullName($fullName)
    {
        // Separa el nombre completo en nombre y apellido (solo la primera separación)
        [$name, $surname] = explode(' ', $fullName, 2);
        $command = $this->connection->prepare(
            "SELECT * FROM users WHERE name = :name AND surname = :surname"
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
                $data['active']
            );
    }

    public function userExists(User $user)
    {
        // Comprueba existencia por id (devuelve true si hay al menos una fila)
        $command = $this->connection->prepare(
            "SELECT COUNT(*) FROM users WHERE id = :userId"
        );
        $command->execute(['userId' => $user->getId()]);
        return $command->fetchColumn() > 0;
    }

    public function insert(User $user): bool
    {
        // Inserta un nuevo usuario en la tabla `users`.
        // Devuelve true si la inserción tuvo éxito, false en caso contrario.
        $sql = "INSERT INTO users
            (id, username, name, surname, passwd, role, email, verified, active)
            VALUES
            (:id, :username, :name, :surname, :passwd, :role, :email, :verified, :active)";

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
            'active'       => $user->isActive() ? 1 : 0
        ]);
    }
    public function login(string $username, string $passwd): User|false
    {
        // Buscar usuario por nombre de usuario
        $command = $this->connection->prepare(
            "SELECT * FROM users WHERE username = :username"
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
                $data['active']
            );
        } else {
            // Contraseña errónea
            return false;
        }
    }
}
