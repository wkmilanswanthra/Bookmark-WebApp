<?php

namespace App\Models;

use CodeIgniter\Model;

class Bookmark_model extends Model
{
    protected $table = 'bookmarks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'url', 'tags'];

    public function getBookmarksByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function getBookmarkById($id)
    {
        return $this->where('id', $id)->first();
    }
    
}
?>
