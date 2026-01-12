<?php

namespace App\Services;

class PayWayService
{
    protected $merchantId;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->merchantId = config('app.payway_merchant_id', env('PAYWAY_MERCHANT_ID'));
        $this->apiKey = config('app.payway_api_key', env('PAYWAY_API_KEY'));
        $this->apiUrl = config('app.payway_api_url', env('PAYWAY_API_URL'));
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }
    
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function getHash($transactionId, $amount)
    {
        // For PayWay, the hash is usually base64(hmac_sha512(hash_data, key))
        // The data string format depends on PayWay version. 
        // Based on common knowledge of ABA PayWay:
        // Hash = base64_encode(hash_hmac('sha512', $str, $key, true));
        // String to hash: $req_time . $merchant_id . $tran_id . $amount . $items . $shipping . $firstname . $lastname . $email . $phone . $type . $payment_option . $return_url . $cancel_url . $continue_success_url . $currency . $return_params;
        
        // HOWEVER, for simple integration it might be simpler.
        // Let's implement the standard one.
        
        // Simplification for Sandbox testing as per documentation often found:
        // The signature is usually: base64_encode(hash_hmac('sha512', $data, $key, true))
        
        // We will assume the controller constructs the string to be hashed or we do it here.
        // Let's do a generic hash method.
        
        return base64_encode(hash_hmac('sha512', $transactionId . $amount, $this->apiKey, true));
    }
    
    // Helper to generate the exact hash string expected by PayWay.
    // ABA PayWay Checkout usually requires:
    // hash = base64_encode(hash_hmac('sha512', $data, $api_key, true));
    // Data is concatenation of values of all fields properly.
    public function generateHash($data)
    {
        // Check if we should use RSA
        // If the user provided RSA keys, we should probably use them.
        // Let's check if we have a private key file
        $privateKeyPath = storage_path('app/aba/private_key.pem');
        
        if (file_exists($privateKeyPath)) {
            $privateKey = file_get_contents($privateKeyPath);
            $binary_signature = "";
            openssl_sign($data, $binary_signature, $privateKey, OPENSSL_ALGO_SHA512);
            return base64_encode($binary_signature);
        }

        // Fallback to HMAC
        $hash = base64_encode(hash_hmac('sha512', $data, $this->apiKey, true));
        return $hash;
    }

    public function checkTransaction($transactionId, $reqTime)
    {
        // Check transaction status via API
        // API URL for check transaction usually different: /api/payment-gateway/v1/payments/check-transaction
        
        // Construct the check URL
        $checkUrl = str_replace('purchase', 'check-transaction', $this->apiUrl);
        
        $data = $reqTime . $this->merchantId . $transactionId;
        $hash = $this->generateHash($data); // Re-use the generateHash method which now handles RSA logic!
        
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->post($checkUrl, [
                'form_params' => [
                    'req_time' => $reqTime,
                    'merchant_id' => $this->merchantId,
                    'tran_id' => $transactionId,
                    'hash' => $hash
                ]
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PayWay Check Transaction Error: ' . $e->getMessage());
            return null;
        }
    }
}
