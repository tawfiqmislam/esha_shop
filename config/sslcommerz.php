<?php

// SSLCommerz configuration

$apiDomain = config('app.sslcz_test_mode') ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com";
return [
	'apiCredentials' => [
		'store_id' => config('app.sslcz_store_id'),
		'store_password' => config('app.sslcz_store_password'),
	],
	'apiUrl' => [
		'make_payment' => "/gwprocess/v4/api.php",
		'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
		'order_validate' => "/validator/api/validationserverAPI.php",
		'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
		'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
	],
	'apiDomain' => $apiDomain,
	'connect_from_localhost' => config('app.sslcz_test_mode'), // For Sandbox, use "true", For Live, use "false"
	'success_url' => '/sslcommerz/success',
	'failed_url' => '/sslcommerz/fail',
	'cancel_url' => '/sslcommerz/cancel',
	'ipn_url' => '/sslcommerz/ipn',
];
