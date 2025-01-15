<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password'];

    public function insert_user($data) {
        return $this->insert($data);
    }

    public function get_user($username) {
        return $this->where('username', $username)->first();
    }
}
