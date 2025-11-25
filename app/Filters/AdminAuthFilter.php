<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in as admin
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            // Get current URL
            $currentUrl = current_url(true);
            
            // Don't redirect if we're already on the login page
            if ($currentUrl->getPath() !== '/admin/login') {
                // Store the current URL for redirecting after login
                $session->set('redirect_url', current_url());
                
                // Check for AJAX requests
                if ($request->isAJAX()) {
                    return service('response')->setJSON([
                        'status' => 'error',
                        'message' => 'Your session has expired. Please log in again.',
                        'redirect' => site_url('admin/login')
                    ]);
                }
                
                return redirect()->to('admin/login')->with('error', 'Please log in to access the admin area.');
            }
            // If we're already on the login page, just show the page
            return null;
        }
        
        // Check for session timeout (30 minutes)
        $lastActivity = $session->get('last_activity');
        $timeout = 1800; // 30 minutes in seconds
        
        if (time() - $lastActivity > $timeout) {
            // Session expired
            $session->destroy();
            return redirect()->to('admin/login')
                           ->with('error', 'Your session has expired. Please log in again.');
        }
        
        // Update last activity time
        $session->set('last_activity', time());
        
        // Check for IP change
        $currentIp = $request->getIPAddress();
        if ($session->has('admin_ip') && $session->get('admin_ip') !== $currentIp) {
            $session->destroy();
            return redirect()->to('admin/login')
                           ->with('error', 'Security alert: IP address changed. Please log in again.');
        }
        
        // Check for user agent change
        $currentUserAgent = $request->getUserAgent()->getAgentString();
        if ($session->has('user_agent') && $session->get('user_agent') !== $currentUserAgent) {
            $session->destroy();
            return redirect()->to('admin/login')
                           ->with('error', 'Security alert: Browser changed. Please log in again.');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
