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


//statstics
public function getUserCountByAgeRange() 
{
    $db = config::getConnexion();
    $sql = "
        SELECT 
          CASE 
            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 24 THEN '18–24'
            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 25 AND 34 THEN '25–34'
            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 35 AND 44 THEN '35–44'
            WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 45 AND 54 THEN '45–54'
            ELSE '55+' 
          END AS age_range,
          COUNT(*) AS user_count
        FROM user
        GROUP BY age_range
        ORDER BY age_range;
    ";
    $query = $db->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

//password reset by email

public static function emailExists($email)
{
    $db = config::getConnexion();
    try {
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->rowCount() > 0;
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
}


public static function updatePasswordByEmail($email, $password)
{
    $db = config::getConnexion();
    try {
        $query = $db->prepare("UPDATE user SET password = :password WHERE email = :email");
        $query->bindParam(':password', $password);
        $query->bindParam(':email', $email);
        return $query->execute();
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
}











}
?>
