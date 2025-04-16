<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../Model/user.php';

class userController
{
    //read
    public function listUsers()
    {
        $sql="SELECT * from user";
        $db=config::getConnexion();
        try
        {
            $list=$db->query($sql);
            return $list;
        }
        catch(exception $e)
        {
            die('Error: '.$e->getMessage());
        }
    }


    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try 
        {
            $query->execute(['email' => $email]);
            return $query->fetch(PDO::FETCH_ASSOC); // or FETCH_OBJ if preferred
        } 
        catch (Exception $e) 
        {
        die('Error: ' . $e->getMessage());
        }
    }


    //create
    public function addUser($user)
    {
        $sql = "INSERT INTO user (username, email, phone, birth_date, role, password)
        VALUES (:username, :email, :phone, :birth_date, :role, :password)";
       
        $db=config::getConnexion();
        $query=$db->prepare($sql);
        try
        {
            $query->execute([
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'birth_date' => $user->getBirth_date()->format('Y-m-d'),
                'role' => $user->getRole(),
                'password' => $user->getPassword()
            ]);
        }
        catch(Exception $e)
        {
            die('Error: '.$e->getMessage());
        }
    }
    //update
    public function showUser($id)
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $db= config::getConnexion();
        $query=$db->prepare($sql);
        try
        {
            $query->execute(['id' => $id]);
            $user=$query->fetch(PDO::FETCH_ASSOC);
            return $user;
        }
        catch(exception $e)
        {
            die('Error: '.$e->getMessage());
        }
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
    
        $db = config::getConnexion();
        $query = $db->prepare($sql);
    
        try 
        {

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
        } 
        catch (Exception $e) 
        {
            die('Error: ' . $e->getMessage());
        }
    }
    

    //delete
    public function deleteUser($idd)
    {
        $sql="DELETE FROM user WHERE id=:id";
        $db=config::getConnexion();
        $query=$db->prepare($sql);
        $query->bindValue(':id',$idd);
        try
        {
            $query->execute();
        }
        catch(Exception $e)
        {
            die('Error: '.$e->getMessage());        
        }
    }
    

    //signin
    public function getUserByUsername($username)
{
    $sql = "SELECT * FROM user WHERE username = :username";
    $db = config::getConnexion();
    $query = $db->prepare($sql);
    try 
    {
        $query->execute(['username' => $username]);
        return $query->fetch(PDO::FETCH_ASSOC);
    } 
    catch (Exception $e) 
    {
        die('Error: ' . $e->getMessage());
    }
}

//dashboard
//affichage
public function getAllUsers()
{
    $sql = "SELECT * FROM user"; 
    $db = config::getConnexion();
    $query = $db->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}






}





?>
