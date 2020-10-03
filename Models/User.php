<?php
namespace Models;

use Validators\UserValidator;
use Service\QueryBuilder;
use Models\Model;

use Traits\PreparedPropertiesObject;

class User extends Model
{
    use PreparedPropertiesObject;

    private $id;
    private $username;
    private $email;
    private $password;
    private $isAdmin = 0;
    private $validator;

    protected static $dependency = [];
    protected static $table = 'users';
    protected static $class = self::class;

    protected $fillable = ['username', 'email', 'password'];

    public function __construct(UserValidator $validator, QueryBuilder $qb)
    {
        parent::__construct();
        self::$dependency['validator'] = $validator;
        self::$dependency['qb'] = $qb;
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

    public function getFillable() 
    {
        return $this->fillable;
    }
    
    protected function rules():array
    {
        return [
            'username'=>['unique', 'minLen'=> 4],
            'password'=>['minLen'=> 4],
        ];
    }

    private function verifyPassword(string $password, string $passwordHash)
    {
        return password_verify($password, $passwordHash);
    }

    public function create(array $userData)
    {
        self::$dependency['validator']->validate($userData, $this->rules());
    
        if(empty(self::$dependency['validator']->getErrors()))
        {
            $this->username = trim($userData['username']);
            $this->email = trim($userData['email']);
            $this->password = password_hash(trim($userData['password']), PASSWORD_DEFAULT);
            return $this->save();
        }
        return self::$dependency['validator']->getErrors();
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
        $result = self::$dependency['qb']->insert(self::getTable())->values($this)->execute();
        return true;
    }

    public  function update(){}
}