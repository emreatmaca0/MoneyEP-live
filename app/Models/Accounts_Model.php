<?php

namespace App\Models;

class Accounts_Model extends \CodeIgniter\Model
{

    protected $table = 'Accounts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id','name', 'type', 'currency', 'amount', 'user_id'];
}