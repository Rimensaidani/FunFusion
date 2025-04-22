<?php
class User
{
    private ?int $id;
    private ?string $username;
    private ?string $email;
    private ?int $phone;
    private ?DateTime $birth_date;
    private ?string $role;
    private ?string $password;
    

    function __construct(?int $id,?string $username,?string $email,?int $phone,?DateTime $birth_date,?string $role,?string $password)
    {
        $this->id=$id;
        $this->username=$username;
        $this->email=$email;
        $this->phone=$phone;
        $this->birth_date=$birth_date;
        $this->role=$role;
        $this->password=$password;
    }

    

    public function getId():?int {return $this->id;}
    public function setId(int $id):void{$this->id=$id;}

    public function getUsername():?string{return $this->username;}
    public function setUsername(string $username):void{$this->username=$username;}

    public function getEmail():?string{return $this->email;}
    public function setEmail(string $email):void{$this->email=$email;}

    public function getPhone():?int{return $this->phone;}
    public function setPhone(int $phone):void{$this->phone=$phone;}

    public function getBirth_date():?DateTime{return $this->birth_date;}
    public function setBirth_date(DateTime $birth_date):void{$this->birth_date=$birth_date;}

    public function getRole():?string{return $this->role;}
    public function setRole(string $role):void{$this->role=$role;}

    public function getPassword():?string{return $this->password;}
    public function setPassword(string $password):void{$this->password=$password;}



    public function getUserByUsername($username)
    {
        $db = config::getConnexion();
        try 
        {
            $query = $db->prepare("SELECT * FROM users WHERE username = :username");
            $query->bindParam(':username', $username);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } 
        catch(Exception $e)
        {
            die('Error: '.$e->getMessage());        
        }
    }

    

    


}



?>