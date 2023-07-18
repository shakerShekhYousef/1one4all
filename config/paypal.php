<?php
return [
    'client_id' => env('PAYPAL_CLIENT_ID','Aby4doMkcCKVnU2J4EAnCmw7fsyXTV1C7MVwNa9P53MrB65phB12gfagfPPbpEXM2sOPLovgQQdWD3RB'),
    'secret' => env('PAYPAL_SECRET','EPOy12VJnNgcBrrw_fRMbOk6nqGMW25R48VW7ATOA9UBZRyACpfBESd9s1AdOBRVg3ssRqmVArIBYDgz'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
