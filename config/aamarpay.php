<?php
$success='dokani.subscription.success';
$cancel='dokani.subscription.cancel';
return [
    'store_id' => env('AAMARPAY_STORE_ID','smartsoftware'),
    'signature_key' => env('AAMARPAY_KEY','44cc30cf0ca7efb892705c0779a0c617'),
    'sandbox' => env('AAMARPAY_SANDBOX', false),
    'redirect_url' => [
        'success' => [
            'route' => env('AAMARPAY_SUCCESS_URL','dokani.subscription.success') // payment.success
        ],
        'cancel' => [
            'route' => env('AAMARPAY_CANCEL_URL','dokani.subscription.cancel') // payment/cancel or you can use route also
        ]
    ]
];

