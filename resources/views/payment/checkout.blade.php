<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment...</title>
</head>

<body>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif;">
        <p>Redirecting to secure payment gateway...</p>
    </div>

    <form id="payway_form" action="{{ config('app.payway_api_url', env('PAYWAY_API_URL')) }}" method="POST">
        <input type="hidden" name="req_time" value="{{ $req_time }}">
        <input type="hidden" name="merchant_id" value="{{ $merchant_id }}">
        <input type="hidden" name="tran_id" value="{{ $tran_id }}">
        <input type="hidden" name="amount" value="{{ $amount }}">
        <input type="hidden" name="items" value="{{ $items }}">
        <input type="hidden" name="shipping" value="{{ $shipping }}">
        <input type="hidden" name="firstname" value="{{ $firstname }}">
        <input type="hidden" name="lastname" value="{{ $lastname }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="phone" value="{{ $phone }}">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="payment_option" value="{{ $payment_option }}">
        <input type="hidden" name="return_url" value="{{ $return_url }}">
        <input type="hidden" name="continue_success_url" value="{{ $continue_success_url }}">
        <input type="hidden" name="currency" value="{{ $currency }}">
        <input type="hidden" name="return_params" value="{{ $return_params }}">
        <input type="hidden" name="hash" value="{{ $hash }}">
    </form>

    <script>
        document.getElementById('payway_form').submit();
    </script>
</body>

</html>
