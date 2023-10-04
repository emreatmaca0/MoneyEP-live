<?php

namespace App\Models;

class Expenses_Model extends \CodeIgniter\Model
{
    protected $table = 'Expenses';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id','date', 'type', 'currency', 'amount', 'category', 'debt','account','description','dd', 'user_id'];
}