<?php

namespace App\Models;

class Debts_Model extends \CodeIgniter\Model
{
    protected $table = 'Debts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id','name', 'type', 'currency', 'amount', 'date', 'user_id'];
}