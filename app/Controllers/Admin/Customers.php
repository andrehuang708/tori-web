<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Customers extends BaseController
{
    protected $userModel;
    protected $perPage = 25;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * List all customers (users)
     */
    public function index()
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('/admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }

        $search = $this->request->getGet('search') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $this->perPage;

        if (!empty($search)) {
            $users = $this->userModel->searchUsers($search, $this->perPage, $offset);
            $totalUsers = $this->userModel->countSearchResults($search);
        } else {
            $users = $this->userModel->getAllUsers($this->perPage, $offset);
            $totalUsers = $this->userModel->countTotalUsers();
        }

        $data = [
            'title' => 'Customers',
            'customers' => $users,
            'search' => $search,
            'pager' => [
                'current_page' => $page,
                'total_items' => $totalUsers,
                'per_page' => $this->perPage,
                'total_pages' => ceil($totalUsers / $this->perPage)
            ]
        ];

        return view('admin/customers/index', $data);
    }

    /**
     * View customer detail
     */
    public function detail($id = null)
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('/admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }

        if (!$id) {
            return redirect()->to('/admin/customers')->with('error', 'Customer ID is required');
        }

        $customer = $this->userModel->getUserById($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found');
        }

        $data = [
            'title' => 'Customer Detail - ' . $this->getFullName($customer),
            'customer' => $customer
        ];

        return view('admin/customers/detail', $data);
    }

    /**
     * Edit customer
     */
    public function edit($id = null)
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('/admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }

        if (!$id) {
            return redirect()->to('/admin/customers')->with('error', 'Customer ID is required');
        }

        $customer = $this->userModel->getUserById($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found');
        }

        $data = [
            'title' => 'Edit Customer - ' . $this->getFullName($customer),
            'customer' => $customer
        ];

        return view('admin/customers/edit', $data);
    }

    /**
     * Update customer
     */
    public function update($id = null)
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('/admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }

        if (!$id) {
            return redirect()->to('/admin/customers')->with('error', 'Customer ID is required');
        }

        $customer = $this->userModel->getUserById($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found');
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone_number' => $this->request->getPost('phone_number'),
            'email_notifications' => $this->request->getPost('email_notifications') ? 1 : 0,
            'sms_notifications' => $this->request->getPost('sms_notifications') ? 1 : 0,
        ];

        // Validate input
        if (!$this->validate($this->userModel->validationRules, $this->userModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            if ($this->userModel->updateUser($id, $data)) {
                return redirect()->to('/admin/customers/detail/' . $id)->with('success', 'Customer updated successfully');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update customer');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating customer: ' . $e->getMessage());
        }
    }

    /**
     * Delete customer
     */
    public function delete($id = null)
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('/admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }

        if (!$id) {
            return redirect()->to('/admin/customers')->with('error', 'Customer ID is required');
        }

        $customer = $this->userModel->getUserById($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found');
        }

        try {
            if ($this->userModel->deleteUser($id)) {
                return redirect()->to('/admin/customers')->with('success', 'Customer deleted successfully');
            } else {
                return redirect()->to('/admin/customers')->with('error', 'Failed to delete customer');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/customers')->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }

    /**
     * Get full name from user data
     */
    private function getFullName($user)
    {
        $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        return !empty($name) ? $name : 'User #' . $user['id'];
    }
}
