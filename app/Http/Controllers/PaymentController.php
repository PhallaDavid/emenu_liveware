<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PayWayService;

class PaymentController extends Controller
{
    protected $payWayService;

    public function __construct(PayWayService $payWayService)
    {
        $this->payWayService = $payWayService;
    }

    public function pay(Order $order)
    {
        // Ensure order belongs to current session or user if authentication existed
        // specific for QR Menu, we might rely on the signed URL or order ID if valid?
        // For simplicity now, we assume public access via link or direct flow.
        
        $req_time = now()->setTimezone('Asia/Phnom_Penh')->format('YmdHis');
        $merchant_id = $this->payWayService->getMerchantId();
        $tran_id = $req_time . '-' . $order->id; // Unique transaction ID
        $amount = number_format($order->total_price, 2, '.', '');
        $firstname = 'Guest'; 
        $lastname = 'Customer';
        $email = 'guest@example.com'; 
        $phone = '012345678'; 
        $payment_option = 'abapay_khqr'; // or cards, etc.
        $return_url = base64_encode(route('payment.callback', ['order' => $order->id]));
        $cancel_url = ""; // Usually required in string even if empty? Or maybe optional. Let's try likely match.
        // Actually, if I look at standard integration, cancel_url often follows return_url.
        // Let's assume cancel_url is expected.
        
        $continue_success_url = base64_encode(route('payment.success', ['order' => $order->id]));
        
        // Save transaction_id to order
        $order->update(['transaction_id' => $tran_id]);

        // String to hash for PayWay
        // $req_time . $merchant_id . $tran_id . $amount . $items . $shipping . $firstname . $lastname . $email . $phone . $type . $payment_option . $return_url . $cancel_url . $continue_success_url . $currency . $return_params;
        
        $items = base64_encode(json_encode([
            ['name' => 'Order ' . $order->id, 'quantity' => '1', 'price' => $amount]
        ]));
        $shipping = '0.00';
        $type = 'purchase';
        $currency = 'USD';
        $return_params = "";
        
        // Standard Hash Order without cancel_url (optional)
        $data = $req_time . $merchant_id . $tran_id . $amount . $items . $shipping . $firstname . $lastname . $email . $phone . $type . $payment_option . $return_url . $continue_success_url . $currency . $return_params;

        $hash = $this->payWayService->generateHash($data);

        return view('payment.checkout', compact('order', 'req_time', 'merchant_id', 'tran_id', 'amount', 'items', 'shipping', 'firstname', 'lastname', 'email', 'phone', 'type', 'payment_option', 'return_url', 'continue_success_url', 'currency', 'return_params', 'hash'));
    }

    public function callback(Request $request)
    {
        // Handle callback from ABA PayWay
        // Verify hash
        // Update order status
        
        $input = $request->all();
        // Log::info($input);

        // Logic to verify transaction status...
        // For now, assume success if status is 0 (standard PayWay success code)
        
        if ($request->input('status') == 0) {
            $tran_id = $request->input('tran_id');
            // Find order by transaction_id
            $order = Order::where('transaction_id', $tran_id)->first();
            
            if ($order) {
                $order->status = 'paid';
                $order->save();
            }
        }
        
        return response()->json(['status' => true]); // Acknowledge to PayWay
    }

    public function success(Order $order)
    {
        if ($order->status !== 'paid' && $order->transaction_id) {
            // Try to verify with API
            $parts = explode('-', $order->transaction_id);
            if (count($parts) >= 2) {
                $req_time = $parts[0];
                $result = $this->payWayService->checkTransaction($order->transaction_id, $req_time);

                if ($result && isset($result['status']) && $result['status'] == 0) {
                     $order->status = 'paid';
                     $order->save();
                }
            }
        }
        
        return redirect()->route('menu.index', ['table' => $order->table_number]);
    }
}
