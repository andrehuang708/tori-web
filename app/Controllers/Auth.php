<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register()
    {
        if (strtoupper($this->request->getMethod()) === 'POST') {
            $rules = [
                'first_name' => 'required|min_length[2]|max_length[50]',
                'last_name' => 'required|min_length[2]|max_length[50]',
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]',
                'phone_number' => 'required|min_length[10]|max_length[20]',
                'confirm_password' => 'required|matches[password]'
            ];

            if ($this->validate($rules)) {
                $firstName = $this->request->getPost('first_name');
                $lastName = $this->request->getPost('last_name');
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $phoneNumber = $this->request->getPost('phone_number');

                // Normalize phone number format
                $phoneNumber = trim($phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '+62' . substr($phoneNumber, 1);
                } elseif (substr($phoneNumber, 0, 3) !== '+62') {
                    $phoneNumber = '+62' . $phoneNumber;
                }

                // Check if email already exists
                $existingUser = $this->userModel->getUserByEmail($email);
                if ($existingUser) {
                    return redirect()->back()->with('error', 'Email is already registered. Please use a different email or login.')->withInput();
                }

                // Check if phone number already exists
                $db = \Config\Database::connect();
                $existingPhone = $db->table('users')
                                    ->where('phone_number', $phoneNumber)
                                    ->get()
                                    ->getRow();
                if ($existingPhone) {
                    return redirect()->back()->with('error', 'Phone number is already registered. Please use a different phone number.')->withInput();
                }

                try {
                    $salt = $this->generateSalt();
                    $hashedPassword = md5($password . $salt);
                    $currentDateTime = date('Y-m-d H:i:s');

                    $userData = [
                        'first_name' => ucfirst(trim($firstName)),
                        'last_name' => ucfirst(trim($lastName)),
                        'email' => strtolower(trim($email)),
                        'password' => $hashedPassword,
                        'phone_number' => $phoneNumber,
                        'salt' => $salt,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime
                    ];

                    $result = $db->table('users')->insert($userData);
                    
                    if ($result) {
                        return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
                    } else {
                        return redirect()->back()->with('error', 'Unable to create account. Please try again later.')->withInput();
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Registration error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Registration failed. Please try again later.')->withInput();
                }
            } else {
                $validationErrors = $this->validator->getErrors();
                $errorMessages = [];
                foreach ($validationErrors as $field => $error) {
                    $errorMessages[] = ucfirst($field) . ': ' . $error;
                }
                $errorMessage = implode('; ', $errorMessages);
                return redirect()->back()->with('error', 'Validation failed: ' . $errorMessage)->with('validation', $this->validator)->withInput();
            }
        }

        return view('auth/register');
    }

    public function login()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                $user = $this->userModel->verifyUser($email, $password);

                if ($user) {
                    session()->set([
                        'user_id' => $user['id'],
                        'user_email' => $user['email'],
                        'user_phone' => $user['phone_number'],
                        'first_name' => $user['first_name'] ?? '',
                        'logged_in' => true
                    ]);

                    // Check if there's an intended URL after login
                    $intendedUrl = session()->get('intended_url');
                    if ($intendedUrl) {
                        session()->remove('intended_url');
                        return redirect()->to($intendedUrl)->with('success', 'Login successful!');
                    }

                    return redirect()->to('/')->with('success', 'Login successful!');
                } else {
                    return redirect()->back()->with('error', 'Invalid email or password.');
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    private function generateSalt()
    {
        return md5(uniqid(rand(), true));
    }
}
