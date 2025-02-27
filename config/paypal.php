<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PayPal API Credentials
    |--------------------------------------------------------------------------
    |
    | These values are used to authenticate your application with PayPal's API.
    | Ensure you have set the correct credentials in your .env file.
    |
    */

    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYPAL_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | PayPal Mode
    |--------------------------------------------------------------------------
    |
    | Set the mode to 'sandbox' for testing or 'live' for real transactions.
    |
    */

    'mode' => env('PAYPAL_MODE', 'live'),

    /*
    |--------------------------------------------------------------------------
    | PayPal API Endpoints
    |--------------------------------------------------------------------------
    |
    | These URLs are used to communicate with PayPal’s API.
    |
    */

    'base_url' => env('PAYPAL_MODE', 'live') === 'live'
        ? 'https://api.paypal.com'
        : 'https://api.sandbox.paypal.com',

    /*
    |--------------------------------------------------------------------------
    | Webhook URL (Optional)
    |--------------------------------------------------------------------------
    |
    | If you're using PayPal Webhooks, define the endpoint that PayPal should 
    | send payment notifications to.
    |
    */

    'webhook_url' => env('PAYPAL_WEBHOOK_URL'),

];
