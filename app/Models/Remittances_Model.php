<?php

namespace App\Models;

class Remittances_Model extends \CodeIgniter\Model
{
    protected $table = 'Remittances';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id','date', 'type', 'currency', 'amount', 'commission', 'source_account','account','description','dd', 'user_id'];
}