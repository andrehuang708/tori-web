<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\PasswordResetModel;

class Auth extends \CodeIgniter\Controller
{
    protected $adminModel;
    protected $passwordResetModel;
    
    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->passwordResetModel = new PasswordResetModel();
    }
    
    /**
     * Display login form
     */
    public function login()
    {
        $session = \Config\Services::session();
        
        // If already logged in, redirect to dashboard
        if ($session->has('isAdminLoggedIn') && $session->get('isAdminLoggedIn') === true) {
            return redirect()->to('/admin/dashboard');
        }
        
        $data = [
            'title' => 'Admin Login',
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/auth/login', $data);
    }
    
    /**
     * Process login
     */
    public function processLogin()
    {
        // Validate form
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') == 1;
        
        // Get admin by email
        $admin = $this->adminModel->where('email', $email)->first();
        
        // Check if admin exists and password is correct
        if (!$admin || !password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password');
        }
        
        // Check if admin is active
        if ($admin['status'] != 'active') {
            return redirect()->back()->withInput()->with('error', 'Your account is not active. Please contact support.');
        }
        
        // Set session data
        $sessionData = [
            'admin_id' => $admin['id'],
            'admin_name' => $admin['name'],
            'admin_email' => $admin['email'],
            'admin_role' => $admin['role'],
            'isAdminLoggedIn' => true,
            'last_activity' => time(),
            'admin_ip' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ];
        
        session()->set($sessionData);
        
        // Update last login
        $this->adminModel->update($admin['id'], [
            'last_login' => date('Y-m-d H:i:s'),
            'last_ip' => $this->request->getIPAddress()
        ]);
        
        // Set remember me cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + (86400 * 30); // 30 days
            
            // Save token to database
            $this->adminModel->update($admin['id'], [
                'remember_token' => $token,
                'token_expires' => date('Y-m-d H:i:s', $expires)
            ]);
            
            // Set cookie
            $this->response->setCookie([
                'name' => 'remember_token',
                'value' => $token,
                'expire' => $expires,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }
        
        // Redirect to intended URL or dashboard
        $redirectUrl = session()->get('redirect_url') ?? '/admin/dashboard';
        session()->remove('redirect_url');
        
        return redirect()->to($redirectUrl)->with('success', 'Welcome back, ' . $admin['name'] . '!');
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        log_message('debug', 'Logout method called');
        
        // Clear remember token from database
        if (session()->has('admin_id')) {
            log_message('debug', 'Clearing remember token for admin_id: ' . session()->get('admin_id'));
            $this->adminModel->update(session()->get('admin_id'), [
                'remember_token' => null,
                'token_expires' => null
            ]);
        } else {
            log_message('debug', 'No admin_id found in session');
        }
        
        log_message('debug', 'Session data before destroy: ' . json_encode(session()->get()));
        
        // Destroy session
        session()->destroy();
        
        log_message('debug', 'Session destroyed');
        
        // Delete remember me cookie
        $this->response->deleteCookie('remember_token');
        
        log_message('debug', 'Redirecting to admin login');
        
        return redirect()->to('/admin/login')->with('success', 'You have been logged out successfully.');
    }
    
    /**
     * Display forgot password form
     */
    public function forgotPassword()
    {
        $data = [
            'title' => 'Forgot Password',
            'validation' => \Config\Services::validation()
        ];
        
        return $this->render('admin/auth/forgot-password', $data, 'auth');
    }
    
    /**
     * Process forgot password
     */
    public function processForgotPassword()
    {
        // Validate form
        if (!$this->validate(['email' => 'required|valid_email'])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        
        // Check if admin exists
        $admin = $this->adminModel->where('email', $email)->first();
        
        if ($admin) {
            // Generate token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save token to database
            $this->passwordResetModel->insert([
                'email' => $email,
                'token' => $token,
                'expires_at' => $expires,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Send reset password email
            $emailService = \Config\Services::email();
            
            $emailService->setTo($email);
            $emailService->setFrom(getenv('email_from'), getenv('site_name') . ' Admin');
            $emailService->setSubject('Reset Your Password');
            
            $resetLink = site_url("admin/reset-password/$token");
            $message = view('emails/reset_password', [
                'name' => $admin['name'],
                'reset_link' => $resetLink,
                'expires' => '1 hour'
            ]);
            
            $emailService->setMessage($message);
            
            if ($emailService->send()) {
                return redirect()->to('/admin/forgot-password')
                    ->with('success', 'Password reset link has been sent to your email.');
            } else {
                log_message('error', 'Failed to send reset password email to ' . $email);
                return redirect()->back()->with('error', 'Failed to send reset password email. Please try again.');
            }
        }
        
        // Always show success message even if email doesn't exist (for security)
        return redirect()->to('/admin/forgot-password')
            ->with('success', 'If your email exists in our system, you will receive a password reset link.');
    }
    
    /**
     * Display reset password form
     */
    public function resetPassword($token = null)
    {
        if (empty($token)) {
            return redirect()->to('/admin/forgot-password')->with('error', 'Invalid or expired reset token.');
        }
        
        // Check if token is valid and not expired
        $reset = $this->passwordResetModel->where('token', $token)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->first();
            
        if (!$reset) {
            return redirect()->to('/admin/forgot-password')->with('error', 'Invalid or expired reset token.');
        }
        
        $data = [
            'title' => 'Reset Password',
            'token' => $token,
            'validation' => \Config\Services::validation()
        ];
        
        return $this->render('admin/auth/reset-password', $data, 'auth');
    }
    
    /**
     * Process reset password
     */
    public function processResetPassword()
    {
        $token = $this->request->getPost('token');
        
        // Check if token is valid
        $reset = $this->passwordResetModel->where('token', $token)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->first();
            
        if (!$reset) {
            return redirect()->to('/admin/forgot-password')->with('error', 'Invalid or expired reset token.');
        }
        
        // Validate form
        $rules = [
            'password' => 'required|min_length[8]',
            'password_confirm' => 'matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        // Get admin by email
        $admin = $this->adminModel->where('email', $reset['email'])->first();
        
        if (!$admin) {
            return redirect()->to('/admin/login')->with('error', 'Admin account not found.');
        }
        
        // Update password
        $this->adminModel->update($admin['id'], [
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);
        
        // Delete used token
        $this->passwordResetModel->where('token', $token)->delete();
        
        // Send confirmation email
        $emailService = \Config\Services::email();
        $emailService->setTo($admin['email']);
        $emailService->setFrom(getenv('email_from'), getenv('site_name') . ' Admin');
        $emailService->setSubject('Password Reset Confirmation');
        
        $message = view('emails/password_reset_confirmation', [
            'name' => $admin['name'],
            'login_link' => site_url('admin/login'),
            'ip_address' => $this->request->getIPAddress(),
            'time' => date('Y-m-d H:i:s')
        ]);
        
        $emailService->setMessage($message);
        $emailService->send();
        
        return redirect()->to('/admin/login')
            ->with('success', 'Your password has been reset successfully. You can now login with your new password.');
    }
    
    /**
     * Check if admin is logged in via remember me cookie
     */
    protected function checkRememberMe()
    {
        $cookie = $this->request->getCookie('remember_token');
        
        if ($cookie && !session()->has('admin_id')) {
            $admin = $this->adminModel->where('remember_token', $cookie)
                ->where('token_expires >', date('Y-m-d H:i:s'))
                ->first();
                
            if ($admin) {
                // Set session
                $sessionData = [
                    'admin_id' => $admin['id'],
                    'admin_name' => $admin['name'],
                    'admin_email' => $admin['email'],
                    'admin_role' => $admin['role'],
                    'isAdminLoggedIn' => true,
                    'last_activity' => time(),
                    'admin_ip' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString()
                ];
                
                session()->set($sessionData);
                
                // Update last login
                $this->adminModel->update($admin['id'], [
                    'last_login' => date('Y-m-d H:i:s'),
                    'last_ip' => $this->request->getIPAddress()
                ]);
                
                return true;
            }
        }
        
        return false;
    }
}
