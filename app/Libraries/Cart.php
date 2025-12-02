<?php

namespace App\Libraries;

class Cart
{
    protected $session;
    protected $productModel;
    protected $cartName = 'shopping_cart';

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->productModel = new \App\Models\ProductModel();
        
        // Initialize cart if it doesn't exist
        if (!$this->session->has($this->cartName)) {
            $this->session->set($this->cartName, [
                'items' => [],
                'total_items' => 0,
                'total_price' => 0.00
            ]);
        }
    }

    /**
     * Get the cart contents
     */
    public function contents()
    {
        $cart = $this->session->get($this->cartName);
        return $cart['items'];
    }

    /**
     * Get cart summary
     */
    public function summary()
    {
        $cart = $this->session->get($this->cartName);
        return [
            'total_items' => $cart['total_items'],
            'total_price' => $cart['total_price']
        ];
    }

    /**
     * Add an item to the cart
     */
    public function add($productId, $quantity = 1)
    {
        $product = $this->productModel->find($productId);
        
        if (!$product || $quantity < 1) {
            return false;
        }

        $cart = $this->session->get($this->cartName);
        $itemId = md5($productId);
        
        if (isset($cart['items'][$itemId])) {
            // Item already in cart, update quantity
            $newQty = $cart['items'][$itemId]['quantity'] + $quantity;
            
            // Check stock
            if ($newQty > $product['stock']) {
                return false;
            }
            
            $cart['items'][$itemId]['quantity'] = $newQty;
        } else {
            // Add new item to cart
            if ($quantity > $product['stock']) {
                return false;
            }
            
            $cart['items'][$itemId] = [
                'id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image'] ?? null,
                'stock' => $product['stock'],
                'category' => $this->getProductCategory($productId)
            ];
        }
        
        // Update cart totals
        $this->updateCartTotals($cart);
        
        return true;
    }

    /**
     * Update item quantity
     */
    public function update($itemId, $quantity)
    {
        $cart = $this->session->get($this->cartName);
        
        if (!isset($cart['items'][$itemId]) || $quantity < 1) {
            return false;
        }
        
        $product = $this->productModel->find($cart['items'][$itemId]['id']);
        
        if (!$product || $quantity > $product['stock']) {
            return false;
        }
        
        $cart['items'][$itemId]['quantity'] = $quantity;
        
        // Update cart totals
        $this->updateCartTotals($cart);
        
        return true;
    }

    /**
     * Remove an item from the cart
     */
    public function remove($itemId)
    {
        $cart = $this->session->get($this->cartName);
        
        if (isset($cart['items'][$itemId])) {
            unset($cart['items'][$itemId]);
            
            // Update cart totals
            $this->updateCartTotals($cart);
            
            return true;
        }
        
        return false;
    }

    /**
     * Clear the cart
     */
    public function destroy()
    {
        $this->session->set($this->cartName, [
            'items' => [],
            'total_items' => 0,
            'total_price' => 0.00
        ]);
        
        return true;
    }

    /**
     * Update cart totals
     */
    protected function updateCartTotals(&$cart)
    {
        $totalItems = 0;
        $totalPrice = 0.00;
        
        foreach ($cart['items'] as $item) {
            $totalItems += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        }
        
        $cart['total_items'] = $totalItems;
        $cart['total_price'] = $totalPrice;
        
        $this->session->set($this->cartName, $cart);
    }

    /**
     * Get product category name
     */
    protected function getProductCategory($productId)
    {
        $product = $this->productModel->select('categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->find($productId);
        
        return $product['category_name'] ?? 'Uncategorized';
    }

    /**
     * Get cart item count
     */
    public function totalItems()
    {
        $cart = $this->session->get($this->cartName);
        return $cart['total_items'];
    }

    /**
     * Get cart total price
     */
    public function totalPrice()
    {
        $cart = $this->session->get($this->cartName);
        return $cart['total_price'];
    }
}
