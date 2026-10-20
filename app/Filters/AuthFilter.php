<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            // Store intended URL for redirect after login
            session()->set('intended_url', current_url());
            return redirect()->to('login')->with('error', 'Please login to access this page');
        }

        // Force-password-change gate: accounts flagged by an admin reset
        // may ONLY visit the change-password page (or logout) until a new
        // password is set. Everything else redirects to change-password.
        if (session()->get('force_password_change')) {
            $path = trim(uri_string(), '/');
            $allowed = ['user/change-password', 'logout', 'login'];
            if (! in_array($path, $allowed, true)) {
                return redirect()->to('user/change-password')
                    ->with('error', 'Please set a new password to continue.')
                    ->with('force_password_change', true);
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not return any value.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null       $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
