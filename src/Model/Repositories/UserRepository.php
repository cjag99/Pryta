<?php
include_once "./src/Config/Database.php";
include_once "./src/Model/Entities/User.php";
class UserRepository{
    private PDO $connection;
public function __construct(PDO $connection) {
    $this->connection = $connection;
}

public function getById($userID) {
    $command = $this->connection->prepare("SELECT * FROM users WHERE id = :userID");
    $command->bindParam(':userID', $userID);
    $command->execute();
    $data = $command->fetch(PDO::FETCH_ASSOC);
    if (!$data){
        return null;
    } else{
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

public function getByFullName($fullName) {
    [$name, $surname] = explode(' ', $fullName, 2);
     $command = $this->connection->prepare(
        "SELECT * FROM users WHERE name = :name AND surname = :surname"
    );
    $command->execute([
        'name'=>$name,
        'surname'=>$surname
    ]);
    $data = $command->fetch(PDO::FETCH_ASSOC);
    return (!$data)
    ?null
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

public function userExists(User $user) {
    $command = $this->connection->prepare(
        "SELECT COUNT(*) FROM users WHERE id = :userId"
    );
    $command->execute(['userId'=>$user->getId()]);
    return $command->fetchColumn() > 0;
}

public function insert(User $user): bool
{
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
public function login(string $username, string $passwd): User|false {
    $command = $this->connection->prepare(
        "SELECT * FROM users WHERE username = :username"
    );
    $command->execute([
        'username'=>$username,
    ]);
    $data = $command->fetch(PDO::FETCH_ASSOC);
    if (!$data) {
        return false;
    }
    if (password_verify($passwd, $data['passwd'])) {
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
        return false;
    }
}

}