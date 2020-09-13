<?php

namespace Models;

use Validators\JobValidator;
use Paginators\JobPaginator;
use Service\QueryBuilder;

use Traits\PropertiesObject;

class Job extends Model
{
    use PropertiesObject;

    private $id;
    private $username;
    private $email;
    public $text;
    private $isDone;
    private $isEdit;
    protected $fillable = ['id','username', 'email', 'text'];

    protected static $table = 'jobs';
    protected static $class = self::class;

    public  function __construct(JobPaginator $paginator = null, JobValidator $validator = null, QueryBuilder $qb = null)
    {
        parent::__construct();
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->qb = $qb;
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getUserName():string
    {
        return $this->username;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function getText():string
    {
        return $this->text;
    }

    public function isDone() 
    {
        return $this->isDone;
    }

    public function isEdit() 
    {
        return $this->isEdit;
    }

    public function paginate(int $recordsPerPage)
    {
        return $this->paginator->paginate($recordsPerPage);
    }

    public function rules():array
    {
        return [
            'username'=>['minLen'=> 4, 'unknown_symbols'=>'~[<,>,?]~'],
            'text'=>['minLen' => 10],
        ];
    }

    public function create(array $data)
    {
        $this->validator->validate($data, $this->rules());

        if(empty($this->validator->getErrors()))
        {
            $this->username = trim($data['username']);;
            $this->email = trim($data['email']);
            $this->text = htmlspecialchars(trim($data['text']));

            return $this->save();
        }
        return $this->validator->getErrors();
    }  

    public function save()
    {     
        if($this->id)
        {
            return  $this->qb->update();
        }

        $this->qb->insert(self::getTable(), $this->fillable, $this->getPropertiesObject())->execute(self::getClass());

        // $sql = 'INSERT INTO ' . $this->getTable() . ' (id, username, email, text) VALUES(:id, :username, :email, :text)';
        // $params = $this->getPropertiesObject();
        // $this->db->query($sql, $params, self::class);
        // $this->id = $this->db->getLastInsertId();

        return true;
    }

    public function update(array $data = null)
    {
        if(!empty($data))
        {
            foreach ($data as $key=>$value)
            {
               if(property_exists($this, $key))
               {
                   $this->$key = $value;
                   $update = 'SET ' . $key . '= :' . $key;
                   $params[':' . $key] = $value;
               }
            }
            $sql = 'UPDATE ' . $this->getTable(). ' ' . $update .' where id='. $this->id;
            $this->db->query($sql, $params , $this->getClass());
            return true;
        }
        $params = $this->getPropertiesObject();
        $sql = 'UPDATE '. $this->getTable() . ' SET username=:username, email=:email, text=:text where id=:id';
        $this->db->query($sql, $params , $this->getClass());
        return true;
    }
}