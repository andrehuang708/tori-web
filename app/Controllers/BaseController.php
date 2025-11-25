<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 * 
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 */
class BaseController extends Controller
{
    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;
    
    /**
     * @var \CodeIgniter\Validation\Validation
     */
    protected $validation;
    
    /**
     * @var \App\Libraries\Cart
     */
    protected $cart;
    
    /**
     * @var \App\Models\CategoryModel
     */
    protected $categoryModel;
    
    /**
     * @var \App\Models\ProductModel
     */
    protected $productModel;
    
    /**
     * @var \App\Models\OrderModel
     */
    protected $orderModel;
    
    /**
     * @var array Global view data
     */
    protected $viewData = [];

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form', 'url', 'text', 'number'];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Load services
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        
        try {
            // Initialize cart
            $this->cart = \Config\Services::cart();
            
            // Load models
            $this->categoryModel = new \App\Models\CategoryModel();
            $this->productModel = new \App\Models\ProductModel();
            $this->orderModel = new \App\Models\OrderModel();
            
            // Set global view data
            $this->setGlobalViewData();
        } catch (\Exception $e) {
            log_message('error', 'Error initializing BaseController: ' . $e->getMessage());
        }
    }
    
    /**
     * Set global view data that will be available to all views
     */
    protected function setGlobalViewData()
    {
        try {
            // Get categories for navigation
            $categories = $this->categoryModel->findAll() ?? [];
            
            // Initialize cart summary with default values
            $cartSummary = [
                'total_items' => 0,
                'total_price' => 0.00
            ];
            
            // Try to get cart summary if cart is initialized
            if ($this->cart !== null) {
                $cartSummary = $this->cart->summary();
            }
            
            // Set global view data
            $this->viewData = [
                'categories' => $categories,
                'cart_total_items' => $cartSummary['total_items'] ?? 0,
                'cart_total_price' => $cartSummary['total_price'] ?? 0.00
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error in setGlobalViewData: ' . $e->getMessage());
            // Set default values on error
            $this->viewData = [
                'categories' => [],
                'cart_total_items' => 0,
                'cart_total_price' => 0.00
            ];
        }
    }
    
    /**
     * Render view with layout
     * 
     * @param string $view View file
     * @param array $data View data
     * @param string $layout Layout file
     * @return string
     */
    protected function render($view, $data = [], $layout = 'layouts/main')
    {
        // Merge global view data with local data
        $data = array_merge($this->viewData, $data);
        
        // Return the rendered view with layout
        return view($layout, [
            'content' => view($view, $data),
            'view_data' => $data
        ]);
    }
    
    /**
     * Set success flash message
     * 
     * @param string $message Message to display
     * @return void
     */
    protected function setSuccessMessage($message)
    {
        $this->session->setFlashdata('message', [
            'type' => 'success',
            'content' => $message
        ]);
    }
    
    /**
     * Set error flash message
     * 
     * @param string $message Message to display
     * @return void
     */
    protected function setErrorMessage($message)
    {
        $this->session->setFlashdata('message', [
            'type' => 'danger',
            'content' => $message
        ]);
    }
    
    /**
     * Set validation errors to flash data
     * 
     * @return void
     */
    protected function setValidationErrors()
    {
        $this->session->setFlashdata('errors', $this->validation->getErrors());
    }
    
    /**
     * Check if the request is AJAX
     * 
     * @return bool
     */
    protected function isAjax()
    {
        return $this->request->isAJAX();
    }
    
    /**
     * Send JSON response
     * 
     * @param array $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     * @return \CodeIgniter\HTTP\Response
     */
    protected function jsonResponse($data, $statusCode = 200)
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON($data);
    }
    
    /**
     * Redirect with success message
     * 
     * @param string $url URL to redirect to
     * @param string $message Success message
     * @param string $type Message type (success, error, warning, info)
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    protected function redirectWithMessage($url, $message, $type = 'success')
    {
        // Set the message in the appropriate session key based on type
        $this->session->setFlashdata($type, $message);
        
        // Also set a generic message for compatibility
        $this->session->setFlashdata('message', [
            'type' => $type,
            'content' => $message
        ]);
        
        return redirect()->to($url);
    }
}
