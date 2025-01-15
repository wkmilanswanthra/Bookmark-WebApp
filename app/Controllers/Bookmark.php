<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class Bookmark extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = \Config\Services::session();
        $this->bookmarkModel = new \App\Models\Bookmark_model();
    }

    public function index()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }

        $userId = $this->session->get('user_id');
        if (!$userId) {
            return $this->failUnauthorized('User not logged in');
        }

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ?? 1;
        $tags = $this->request->getVar('tags');
        $query = $this->bookmarkModel->where('user_id', $userId);

        if ($tags) {
            $query->like('tags', $tags);
        }


        $bookmarks = $this->bookmarkModel
            ->where('user_id', $userId)
            ->paginate($perPage, 'default', $currentPage);

        $pager = $this->bookmarkModel->pager;


        return $this->respond([
            'bookmarks' => $bookmarks,
            'pager' => $pager,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'total' => $pager->getTotal()
        ]);
    }



    public function add()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }

        $data = [
            'user_id' => $this->session->get('user_id'),
            'title' => $this->request->getPost('title'),
            'url' => $this->request->getPost('url'),
            'tags' => $this->request->getPost('tags'),
        ];

        $rules = [
            'title' => 'required',
            'url' => 'required|valid_url',
        ];
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $bookmarkId = $this->bookmarkModel->insert($data);

        if ($bookmarkId === false) {
            return $this->failServerError('Failed to add bookmark');
        }

        return $this->respondCreated(['id' => $bookmarkId, 'message' => 'Bookmark added successfully']);
    }

    public function searchByTag($tag)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }

        $bookmarks = $this->bookmarkModel->where('user_id', $this->session->get('user_id'))
                                         ->like('tags', $tag)
                                         ->findAll();

        return $this->respond($bookmarks);
    }

    public function edit($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'url' => $this->request->getPost('url'),
            'tags' => $this->request->getPost('tags'),
        ];

        $rules = [
            'title' => 'required',
            'url' => 'required|valid_url',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $updated = $this->bookmarkModel->update($id, $data);

        if (!$updated) {
            return $this->failServerError('Failed to update bookmark');
        }

        return $this->respond(['message' => 'Bookmark updated successfully']);
    }

     public function delete($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }

        $bookmark = $this->bookmarkModel->where('id',$id);

        if (!$bookmark) {
            return $this->failNotFound('Bookmark not found');
        }

        $deleted = $this->bookmarkModel->where('id',$id)->delete($id);

        if (!$deleted) {
            return $this->failServerError('Failed to delete bookmark');
        }

        return $this->respondDeleted(['message' => 'Bookmark deleted successfully']);
    }

}

