<?php

namespace Paginators;

use Service\Database;

abstract class AbstractPaginator
{
    protected $page = 1;
    protected $total_pages;
    protected $links = 3;
    protected $sort_name = '';
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    abstract public function paginate(int $recordsPerPage);

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