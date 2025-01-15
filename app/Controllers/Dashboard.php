<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    use ResponseTrait;

    protected $session;
    protected $bookmarkModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = \Config\Services::session();
        $this->bookmarkModel = new \App\Models\Bookmark_model();
    }

    public function index($page = 1)
{
    if (!$this->session->get('logged_in')) {
        return redirect()->to('login');
    }

    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    $bookmarks = $this->bookmarkModel->where('user_id', $this->session->get('user_id'))
                                     ->findAll($perPage, $offset);

    $total = $this->bookmarkModel->where('user_id', $this->session->get('user_id'))->countAllResults();

    $data = [
        'session' => $this->session,
        'bookmarks' => $bookmarks,
        'pager' => service('pager'),
        'total' => $total,
        'perPage' => $perPage,
        'currentPage' => $page
    ];

    return view('dashboard_view', $data);
}


    public function addBookmark()
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

        return redirect()->to('dashboard');
    }
    public function searchBookmark($tag)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }

        $bookmarks = $this->bookmarkModel->where('user_id', $this->session->get('user_id'))
                                         ->like('tags', $tag)
                                         ->findAll();

        return view('dashboard_view', ['session' => $this->session, 'bookmarks' => $bookmarks]);
    }

    public function editBookmark($id)
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

        return redirect()->to('dashboard');
    }

    public function deleteBookmark($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }

        $deleted = $this->bookmarkModel->delete($id);

        if (!$deleted) {
            return $this->failServerError('Failed to delete bookmark');
        }

        return redirect()->to('dashboard');
    }
}

