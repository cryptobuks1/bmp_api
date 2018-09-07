<?php

return [

    ['GET', '/', ['Api\Controllers\HomepageController', 'show']],
    ['POST', '/api-test', ['Api\Controllers\HomepageController', 'showTest']],
    ['POST', '/getAccessToken', ['Api\Controllers\OauthController', 'getAccessToken']], // Get the Access Token for the API access
    
    //Wallet related routes
    ['POST', '/getWalletBalance', ['Api\Controllers\WalletController', 'getWalletBalance']], // To get wallet balance
    ['POST', '/getAllWalletAddress', ['Api\Controllers\WalletController', 'getAllWalletAddress']], // To get all wallet address
    ['POST', '/createWallet', ['Api\Controllers\WalletController', 'createWallet']], // To create wallet
    ['POST', '/getAllWalletDetailByUserName', ['Api\Controllers\WalletController', 'getAllWalletDetailByUserName']], // To get wallet details by user name
    ['POST', '/sendPayment', ['Api\Controllers\WalletController', 'sendPayment']], // To get wallet details by user name

    //Payment receive related routes
    ['POST', '/generateAddressToRecivePayment', ['Api\Controllers\ReceiveController', 'generateAddressToRecivePayment']], // To get all wallet address
    ['POST', '/getCallbacklogsByInvoiceId', ['Api\Controllers\ReceiveController', 'getCallbacklogsByInvoiceId']], // To get all wallet address
    ['POST', '/checkForAvailableInvoiceToRecivePayment', ['Api\Controllers\ReceiveController', 'checkForAvailableInvoiceToRecivePayment']], // Check if invoice is already exist or not
    ['POST', '/checkForPaidInvoiceToRecivePayment', ['Api\Controllers\ReceiveController', 'checkForPaidInvoiceToRecivePayment']], // Check if invoice is already exist or not
    ['POST', '/getPoolDataToRecivePayment', ['Api\Controllers\ReceiveController', 'getPoolDataToRecivePayment']], // Check if invoice is already exist or not
    
    //Tree related routes
    ['POST', '/joinTree', ['Api\Controllers\TreeController', 'joinTree']], // To get all wallet address
    

    ['POST', '/loginCustomer', ['Api\Controllers\UserController', 'loginCustomer']],
    ['POST', '/registerCustomer', ['Api\Controllers\UserController', 'registerCustomer']],
    ['POST', '/updateCustomer', ['Api\Controllers\UserController', 'updateCustomer']],
    //get user details
    ['POST', '/getAllUserDataByUserName', ['Api\Controllers\UserController', 'getAllUserDataByUserName']],
    ['POST', '/sendTestEmail', ['Api\Controllers\UserController', 'sendTestEmail']],
    ['POST', '/sendForgetPassword', ['Api\Controllers\UserController', 'sendForgetPassword']],
    ['POST', '/verifyEmail', ['Api\Controllers\UserController', 'verifyEmail']],
    
    
];
