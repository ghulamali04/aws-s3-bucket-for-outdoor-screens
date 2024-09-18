<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'users';
    
    protected $allowedFields = [
        'name',
        'email',
        'role',
        'password',
        'created_at',
        'updated_at',
    ];
}