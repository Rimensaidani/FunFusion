<?php
require_once __DIR__.'/../config.php';
require_once 'user.php'; 

class UserModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion(); 
    }

    public function updateUser($user, $id)
    {
        $sql = "UPDATE user SET 
                    username = :username,
                    email = :email,
                    phone = :phone,
                    birth_date = :birth_date,
                    role = :role,
                    password = :password
                WHERE id = :id";

        $query = $this->pdo->prepare($sql);

        try {
            $birthDateStr = $user->getBirth_date()->format('Y-m-d');

            $query->execute([
                'id' => $id,
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'birth_date' => $birthDateStr,
                'role' => $user->getRole(),
                'password' => $user->getPassword(),
            ]);

            return true; 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getUserById($id)
{
    $sql = "SELECT * FROM user WHERE id = :id";
    $query = $this->pdo->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}


}
?>
