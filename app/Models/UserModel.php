<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password', 'phone_number', 'salt', 'first_name', 'last_name', 'email_notifications', 'sms_notifications', 'force_password_change'];

    /**
     * Default password assigned by an admin reset.
     * Customer must change it on next login (see force_password_change).
     */
    public const DEFAULT_PASSWORD = 'P@ssword123';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'first_name' => 'permit_empty|max_length[50]',
        'last_name' => 'permit_empty|max_length[50]',
        'phone_number' => 'permit_empty|max_length[20]'
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'first_name' => [
            'max_length' => 'First name cannot exceed 50 characters'
        ],
        'last_name' => [
            'max_length' => 'Last name cannot exceed 50 characters'
        ],
        'phone_number' => [
            'max_length' => 'Phone number cannot exceed 20 characters'
        ]
    ];

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function createUser($data)
    {
        return $this->insert($data);
    }

    public function verifyUser($email, $password)
    {
        $user = $this->getUserByEmail($email);
        
        if ($user) {
            $hashedPassword = md5($password . $user['salt']);
            if ($hashedPassword === $user['password']) {
                return $user;
            }
        }
        
        return false;
    }

    /**
     * Get all users with pagination
     */
    public function getAllUsers($limit = 25, $offset = 0)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll($limit, $offset);
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Search users
     */
    public function searchUsers($search, $limit = 25, $offset = 0)
    {
        return $this->groupStart()
                   ->like('first_name', $search)
                   ->orLike('last_name', $search)
                   ->orLike('email', $search)
                   ->orLike('phone_number', $search)
                   ->groupEnd()
                   ->orderBy('created_at', 'DESC')
                   ->findAll($limit, $offset);
    }

    /**
     * Count total users
     */
    public function countTotalUsers()
    {
        return $this->countAllResults();
    }

    /**
     * Count search results
     */
    public function countSearchResults($search)
    {
        return $this->groupStart()
                   ->like('first_name', $search)
                   ->orLike('last_name', $search)
                   ->orLike('email', $search)
                   ->orLike('phone_number', $search)
                   ->groupEnd()
                   ->countAllResults();
    }

    /**
     * Update user
     */
    public function updateUser($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        return $this->delete($id);
    }

    /**
     * Reset a customer's password to the default password and flag the
     * account so the customer must set a new password on next login.
     *
     * NOTE: uses the project's existing md5(password . salt) scheme so
     * verifyUser() keeps working (see Auth::register / verifyUser).
     */
    public function resetPasswordToDefault($id)
    {
        $salt = md5(uniqid(rand(), true));

        return $this->update($id, [
            'salt'                  => $salt,
            'password'              => md5(static::DEFAULT_PASSWORD . $salt),
            'force_password_change' => 1,
        ]);
    }

    /**
     * Set a new customer password (same md5+salt scheme) and clear the
     * force-change flag, restoring full access.
     */
    public function setNewPassword($id, string $newPassword)
    {
        $salt = md5(uniqid(rand(), true));

        return $this->update($id, [
            'salt'                  => $salt,
            'password'              => md5($newPassword . $salt),
            'force_password_change' => 0,
        ]);
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $total = $this->countAllResults();
        $thisMonth = $this->where('MONTH(created_at)', date('m'))
                          ->where('YEAR(created_at)', date('Y'))
                          ->countAllResults();
        
        return [
            'total' => $total,
            'this_month' => $thisMonth
        ];
    }
}
