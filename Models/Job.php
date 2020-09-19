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
    protected static $dependency = [];

    public  function __construct(JobPaginator $paginator, JobValidator $validator, QueryBuilder $qb)
    {
        parent::__construct();
        self::$dependency['paginator'] = $paginator;
        self::$dependency['validator'] = $validator;
        self::$dependency['qb'] = $qb;
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

    public function getFillable() 
    {
        return $this->fillable;
    }
    public function paginate(int $recordsPerPage)
    {
        return self::$dependency['paginator']->paginate($recordsPerPage);
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
        self::$dependency['validator']->validate($data, $this->rules());

        if(empty(self::$dependency['validator']->getErrors()))
        {
            $this->username = trim($data['username']);;
            $this->email = trim($data['email']);
            $this->text = htmlspecialchars(trim($data['text']));

            return $this->save();
        }
        return self::$dependency['validator']->getErrors();
    }  

    public function save()
    {     
        if($this->id)
        {
            return  self::$dependency['qb']->update();
        }
        self::$dependency['qb']->insert(self::getTable(), $this->fillable, $this->getPropertiesObject())->execute(self::getClass());
        return true;
    }

    public function update(array $data = null)
    {
        if(!empty($data))
        {
           self::$dependency['qb']->update(self::getTable(), $data)->where(['id' => $this->id])->execute();
           return true;
        }
        self::$dependency['qb']->update(self::getTable(), $this)->where(['id' => $this->id])->execute();
        return true;
    }
}