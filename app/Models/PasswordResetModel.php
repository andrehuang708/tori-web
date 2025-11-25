<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['email', 'token', 'expires_at', 'created_at'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;
    
    /**
     * Create a new password reset token
     */
    public function createToken($email, $token, $expiresAt)
    {
        // Delete any existing tokens for this email
        $this->where('email', $email)->delete();
        
        // Create new token
        return $this->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt
        ]);
    }
    
    /**
     * Find a valid token
     */
    public function findToken($token)
    {
        return $this->where('token', $token)
                   ->where('expires_at >', date('Y-m-d H:i:s'))
                   ->first();
    }
    
    /**
     * Delete a token
     */
    public function deleteToken($token)
    {
        return $this->where('token', $token)->delete();
    }
    
    /**
     * Delete expired tokens
     */
    public function deleteExpiredTokens()
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
