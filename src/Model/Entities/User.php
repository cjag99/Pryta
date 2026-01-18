<?php

declare(strict_types=1);
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
    private int $id;
    private string $username;
    private string $name;
    private string $surname;
    private string $passwd;
    private string $role;
    private string $email;
    private bool $verified;
    private bool $active;
    private ?int $team_id;
    /**
     * Constructor.
     *
     * @param int $id
     * @param string $username
     * @param string $name
     * @param string $surname
     * @param string $passwd Contraseña ya hasheada
     * @param string $role Rol del usuario
     * @param string $email
     */
    public function __construct(
        int $id,
        string $username,
        string $name,
        string $surname,
        string $passwd,
        string $role = UserRole::SOFTWARE_ENGINEER->value,
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
        $this->role = $role;
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

    /**
     * Devuelve el nombre de usuario (username) del usuario.
     *
     * @return string Username del usuario
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Cambia el nombre de usuario (username) del usuario.
     *
     * @param string $username Nuevo nombre de usuario
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Devuelve el nombre del usuario.
     *
     * @return string El nombre del usuario
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $name El nombre del usuario
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Devuelve el apellido del usuario.
     *
     * @return string El apellido del usuario
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * Establece el apellido del usuario.
     *
     * @param string $surname El apellido del usuario
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Devuelve la contraseña hasheada del usuario.
     *
     * @return string La contraseña hasheada del usuario
     */
    public function getPasswd(): string
    {
        return $this->passwd;
    }

    /**
     * Establece la contraseña hasheada del usuario.
     *
     * @param string $passwd La contraseña del usuario en texto plano
     */
    public function setPasswd(string $passwd): void
    {
        $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
    }

    /**
     * Devuelve el rol del usuario como texto.
     *
     * @return string El rol del usuario (valor de la enumeración UserRole)
     */
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

    /**
     * Devuelve el correo electrónico del usuario.
     *
     * @return string El correo electrónico del usuario
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Establece el correo electrónico del usuario.
     *
     * @param string $email El correo electrónico del usuario
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Devuelve true si el usuario ha sido verificado, false en caso contrario.
     *
     * @return bool Estado de verificación del usuario
     */
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

    /**
     * Devuelve el ID del equipo al que pertenece el usuario,
     * o null si no pertenece a ningún equipo.
     *
     * @return int|null El ID del equipo al que pertenece el usuario
     */
    public function getTeamId(): ?int
    {
        return $this->team_id;
    }

    /**
     * Cambia el equipo al que pertenece el usuario.
     *
     * Solo Superadmin puede hacerlo.
     *
     * @param int|null $team_id El ID del equipo al que se asigna el usuario, o null para no asignar equipo
     *
     * @throws UserException si el usuario no es Superadmin
     */
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'name' => $this->getName(),
            'surname' => $this->getSurname(),
            'role' => $this->getRole(),
            'email' => $this->getEmail(),
            'verified' => $this->isVerified(),
            'active' => $this->isActive(),
            'team_id' => $this->getTeamId()
        ];
    }

    public static function fromDatabase(
        int $id,
        string $username,
        string $name,
        string $surname,
        string $hashedPassword,
        string $role,
        string $email,
        bool $verified = false,
        bool $active = true,
        ?int $team_id = null
    ): self {
        return new self(
            $id,
            $username,
            $name,
            $surname,
            $hashedPassword, // ya viene hasheado
            $role,
            $email,
            $verified,
            $active,
            $team_id
        );
    }
}
