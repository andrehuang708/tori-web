<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'email', 'password', 'role', 'status', 'last_login', 'last_ip',
        'remember_token', 'token_expires', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['password'])) {
            return $data;
        }
        
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }
    
    /**
     * Get admin by email
     */
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Verify admin credentials
     */
    public function verifyCredentials($email, $password)
    {
        $admin = $this->where('email', $email)->first();
        
        if (!$admin) {
            return false;
        }
        
        if (!password_verify($password, $admin['password'])) {
            return false;
        }
        
        return $admin;
    }
    
    /**
     * Update last login info
     */
    public function updateLastLogin($adminId)
    {
        return $this->update($adminId, [
            'last_login' => date('Y-m-d H:i:s'),
            'last_ip' => service('request')->getIPAddress()
        ]);
    }
    
    /**
     * Set remember token
     */
    public function setRememberToken($adminId, $token, $expiry)
    {
        return $this->update($adminId, [
            'remember_token' => $token,
            'token_expires' => $expiry
        ]);
    }
    
    /**
     * Clear remember token
     */
    public function clearRememberToken($adminId)
    {
        return $this->update($adminId, [
            'remember_token' => null,
            'token_expires' => null
        ]);
    }
}
