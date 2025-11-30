<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password', 'phone_number', 'salt', 'first_name', 'last_name', 'email_notifications', 'sms_notifications'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

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
}
