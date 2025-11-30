<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentHistoryModel extends Model
{
    protected $table = 'payment_history';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_id',
        'customer_email',
        'customer_phone',
        'payment_proof_image',
        'payment_amount',
        'payment_method',
        'payment_date',
        'notes',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getPaymentHistoryByOrder($orderId)
    {
        return $this->where('order_id', $orderId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getLatestPaymentByOrder($orderId)
    {
        return $this->where('order_id', $orderId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    public function getPendingPayments()
    {
        return $this->where('status', 'pending')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function verifyPayment($paymentId, $adminId, $status = 'verified', $adminNotes = '')
    {
        $data = [
            'status' => $status,
            'verified_by' => $adminId,
            'verified_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $adminNotes
        ];

        return $this->update($paymentId, $data);
    }
}
