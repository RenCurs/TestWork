<?php 

namespace Paginators;

use Paginators\AbstractPaginator;
use Service\QueryBuilder;
use Models\Job;

class JobPaginator extends AbstractPaginator
{

    public function __construct(QueryBuilder $qb)
    {
        parent::__construct();
        $this->qb = $qb;
    }

    public function paginate(int $recordsPerPage)
    {
        $sort = '';
        if(!empty($_GET['page']))
        {
            $this->page = (int) $_GET['page'];
        }

        if($this->page === 0)
        {
            throw new \Exception('Page is not found');
        }

        if(!empty($_GET['sort']) && method_exists($this, $_GET['sort']))
        {
            $method = $_GET['sort'];
            $this->sort_name = $method;
            $sort = $this->$method();
        }

        $offset = ($this->page - 1) * $recordsPerPage;
        
        $allRecords = $this->qb->count(Job::getTable(), 'id')->execute('', true);
        $this->total_pages = ceil($allRecords / $recordsPerPage);

        $jobs = $this->qb->select(Job::getTable())->sort($sort)->limit($recordsPerPage)->offset($offset)->execute(Job::getClass());

        //$jobs = $this->qb->select($this->table)->limit($recordsPerPage)->offset($offset)->execute(Job::getClass());
        // $sql = 'SELECT * FROM ' . $this->table . ' '. $sort .' LIMIT ' . $recordsPerPage . ' OFFSET ' .$offset;
        // $jobs = $this->db->query($sql,[], 'Models\Job');

        return ['jobs'=> $jobs, 'paginator'=>$this];
    }

    public function username_up()
    {
        return 'ORDER BY username ASC';
    }

    public function username_down()
    {
        return 'ORDER BY username DESC';
    }

    public function email_up()
    {
        return 'ORDER BY email ASC';
    }

    public function email_down()
    {
        return 'ORDER BY email DESC';
    }

    public function text_up()
    {
        return 'ORDER BY text ASC';
    }

    public function text_down()
    {
        return 'ORDER BY text DESC';
    }

    public function getSort():string
    {
        return $this->sort_name;
    }

    public function getCurrPage():int
    {
        return $this->page;
    }

    public function getLast():int
    {
        return $this->total_pages;
    }

    public function getStart():int
    {
        return (($this->page - $this->links) > 0) ? $this->page - $this->links : 1;
    }

    public function getEnd():int
    {
        return (($this->page + $this->links) < $this->total_pages) ? $this->page + $this->links : $this->total_pages;
    }

}