<?php

namespace App\Models;

class Revenues_Model extends \CodeIgniter\Model
{
    protected $table = 'Revenues';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id','date', 'type', 'currency', 'amount', 'category', 'debt','account','description','dd', 'user_id'];

}