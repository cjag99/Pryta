<?php
require_once "./src/Model/Entities/UserRole.php";
require_once "./src/Model/Entities/UserException.php";

/**
 * Representa un usuario en la aplicación.
 *
 * Propiedades principales:
 * - id, username, name, surname, email, passwd
 * - role: texto del rol (usar UserRole::X->value)
 * - verified / active: flags de estado
 *
 * Notas de seguridad:
 * - Algunos setters sensibles solo pueden ser ejecutados por un Superadmin
 *   (se lanzará UserException en caso contrario).
 */
class User
{
    private $id;
    private $username;
    private $name;
    private $surname;
    private $passwd;
    private $role = "";
    private $email;
    private $verified;
    private $active;
    private ?int $team_id;
    /**
     * Constructor.
     *
     * @param int $id
     * @param string $username
     * @param string $name
     * @param string $surname
     * @param string $passwd Contraseña ya hasheada
     * @param string $email
     */
    public function __construct(
        int $id,
        string $username,
        string $name,
        string $surname,
        string $passwd,
        string $email,
        bool $verified = false,
        bool $active = true,
        ?int $team_id = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->surname = $surname;
        $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
        $this->email = $email;
        $this->verified = $verified;
        $this->active = $active;
        $this->team_id = $team_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Cambia el id del usuario. Solo Superadmin puede hacerlo.
     *
     * @throws UserException si el usuario no es Superadmin
     */
    public function setId(int $id): void
    {
        $this->ensureSuperadmin('cambiar el id');
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getPasswd(): string
    {
        return $this->passwd;
    }

    public function setPasswd(string $passwd): void
    {
        $this->passwd = $passwd;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Establece el rol del usuario (texto). Solo Superadmin puede hacerlo.
     *
     * @param string $role
     * @throws UserException si el usuario no es Superadmin
     */
    public function setRole(string $role): void
    {
        $this->ensureSuperadmin('cambiar el rol');
        $this->role = $role;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * Marca al usuario como verificado o no. Solo Superadmin.
     *
     * @param bool $verified
     * @throws UserException si el usuario no es Superadmin
     */
    public function setVerified(bool $verified): void
    {
        $this->ensureSuperadmin('cambiar estado de verificación');
        $this->verified = $verified;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Activa o desactiva la cuenta del usuario. Solo Superadmin.
     *
     * @param bool $active
     * @throws UserException si el usuario no es Superadmin
     */
    public function setActive(bool $active): void
    {
        $this->ensureSuperadmin('cambiar estado activo');
        $this->active = $active;
    }

    public function getTeamId(): ?int
    {
        return $this->team_id;
    }

    public function setTeamId(?int $team_id): void
    {
        $this->ensureSuperadmin('cambiar este usuario de equipo');
        $this->team_id = $team_id;
    }

    /**
     * Comprueba que este usuario tenga rol Superadmin.
     * Lanza UserException si no es así.
     *
     * @param string $action Descripción de la acción permitida solo a Superadmin (para el mensaje)
     * @throws UserException
     */
    private function ensureSuperadmin(string $action = 'realizar esta acción'): void
    {
        if ($this->role !== UserRole::SUPERADMIN->value) {
            throw new UserException("Solo superadmin puede {$action}.");
        }
    }

    /**
     * Verifica una contraseña en texto plano contra la contraseña hasheada almacenada.
     *
     * @param string $passwd Contraseña en texto plano
     * @return bool True si coincide
     */
    public function verifyPassword($passwd): bool
    {
        return password_verify($passwd, $this->getPasswd());
    }
}
