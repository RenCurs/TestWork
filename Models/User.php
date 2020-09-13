<?php
namespace Models;

use Validators\UserValidator;
use Models\Model;
use Service\DIContainer;
use Traits\PropertiesObject;

class User extends Model
{
    use PropertiesObject;

    private $id;
    private $username;
    private $email;
    private $password;
    private $isAdmin = 0;
    private $validator;

    protected static $table = 'users';
    protected static $class = self::class;

    protected $fillable = ['id','username', 'email', 'password'];

    public function __construct(UserValidator $validator = null)
    {
        parent::__construct();
        $this->validator = $validator;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getPasswordHash()
    {
        return $this->password;
    }

    protected function getIsAdmin()
    {
        return $this->isAdmin;
    }

    protected function rules():array
    {
        return [
            'username'=>['unique', 'minLen'=> 4],
            'password'=>['minLen'=> 4],
        ];
    }

    private function verifyPassword( string $password, string $passwordHash)
    {
        return password_verify($password, $passwordHash);
    }

    public function create(array $userData)
    {
        $this->validator->validate($userData, $this->rules());
    
        if(empty($this->validator->getErrors()))
        {
            $this->username = trim($userData['username']);
            $this->email = trim($userData['email']);
            $this->password = password_hash(trim($userData['password']), PASSWORD_DEFAULT);
            return $this->save();
        }
        return $this->validator->getErrors();
    }

    public function login(array $data)
    {
        $userData['email'] = htmlspecialchars(trim($data['email']));
        $userData['password'] = trim($data['password']);

        $user = $this->find('email', $userData['email']);

        if(!is_null($user))
        {
            if($this->verifyPassword($userData['password'], $user->getPasswordHash()))
            {
                $_SESSION['user'] = $user->username;
                $_SESSION['admin'] = $user->getIsAdmin();
                return true;
            }
        }
        return false;
    }

    public function save()
    {
        $params = $this->getPropertiesObject();
        $sql = 'INSERT INTO ' . $this->table .' (id, username, email, password) VALUES(:id, :username, :email, :password)';
        $this->db->query($sql, $params, $this->class);
        return true;
    }

    public  function update(){}
}