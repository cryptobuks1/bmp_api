<?php

return [

    ['GET', '/', ['Api\Controllers\HomepageController', 'show']],
    ['POST', '/api-test', ['Api\Controllers\HomepageController', 'showTest']],
    ['POST', '/getAccessToken', ['Api\Controllers\OauthController', 'getAccessToken']], // Get the Access Token for the API access
    
    //Wallet related routes
    ['POST', '/getWalletBalance', ['Api\Controllers\WalletController', 'getWalletBalance']], // To get wallet balance
    ['POST', '/getAllWalletAddress', ['Api\Controllers\WalletController', 'getAllWalletAddress']], // To get all wallet address
    ['POST', '/createWallet', ['Api\Controllers\WalletController', 'createWallet']], // To create wallet
    
    //Payment receive related routes
    ['POST', '/generateAddressToRecivePayment', ['Api\Controllers\ReceiveController', 'generateAddressToRecivePayment']], // To get all wallet address
    ['POST', '/getCallbacklogsByInvoiceId', ['Api\Controllers\ReceiveController', 'getCallbacklogsByInvoiceId']], // To get all wallet address
    
     
    // Customer related routes
    ['POST', '/createCustomer', ['Api\Controllers\CustomerController', 'createCustomer']], // Accept and insert the customer data
    ['POST', '/createCustomerLoyalty', ['Api\Controllers\CustomerController', 'createCustomerLoyalty']], // Accept and insert the customer loyalty data
    ['POST', '/createCustomerBill', ['Api\Controllers\CustomerController', 'createCustomerBill']], // Accept and insert the customer bill data
    ['POST', '/createCustomerTier', ['Api\Controllers\CustomerController', 'createCustomerTier']], // Accept and insert the customer tier data
    ['POST', '/createCustomerPoints', ['Api\Controllers\CustomerController', 'createCustomerPoints']], // Accept and insert the customer points data    
    ['POST', '/createCustomerLoginLogs', ['Api\Controllers\CustomerController', 'createCustomerLoginLogs']], // Accept and insert the customer login logs data    
    ['POST', '/createCustomerDeviceToken', ['Api\Controllers\CustomerController', 'createCustomerDeviceToken']], // Accept and insert the customer device token data    
    ['POST', '/updateDeviceToken', ['Api\Controllers\CustomerController', 'updateDeviceToken']], // Accept and insert the customer device token data    
    ['POST', '/getCustomerBillDetails', ['Api\Controllers\CustomerController', 'getCustomerBillDetails']], // Returns customer bills    
    ['POST', '/getCustomerUpdatedBills', ['Api\Controllers\CustomerController', 'getCustomerUpdatedBills']], // Returns customer updated bills    
    ['POST', '/createTempBillDetails', ['Api\Controllers\CustomerController', 'createTempBillDetails']], // insert temp bills    
    ['POST', '/getCustomerNotifications', ['Api\Controllers\CustomerController', 'getCustomerNotifications']], // get customer push notifications
    ['POST', '/earnPointsCalculation', ['Api\Controllers\CustomerController', 'earnPointsCalculation']], //Calculate Earn points and update records accordingly
    // Merchant related routes
    ['POST', '/createMerchant', ['Api\Controllers\MerchantController', 'createMerchant']], // Accept and insert the Merchant data
    ['POST', '/createMerchantLocations', ['Api\Controllers\MerchantController', 'createMerchantLocations']], // Accept and insert the Merchant Location data
    ['POST', '/createMerchantLocationBrands', ['Api\Controllers\MerchantController', 'createMerchantLocationBrands']], // Accept and insert the Merchant Location Brand data
    ['POST', '/createMerchantLoyaltyProgram', ['Api\Controllers\MerchantController', 'createMerchantLoyaltyProgram']], // Accept and insert the Merchant Loyalty Program data
    ['POST', '/createMerchantLoyaltyProgramTiers', ['Api\Controllers\MerchantController', 'createMerchantLoyaltyProgramTiers']], // Accept and insert the Merchant Loyalty Program Tiers data
    ['POST', '/createMerchantLoyaltyProgramCategorywiseTiers', ['Api\Controllers\MerchantController', 'createMerchantLoyaltyProgramCategorywiseTiers']], // Accept and insert the Merchant Loyalty Program Categoriwise Tiers data
    ['POST', '/createMerchantOffers', ['Api\Controllers\MerchantController', 'createMerchantOffers']], // Accept and insert the Merchant offers data
    ['POST', '/getMerchantOffers', ['Api\Controllers\MerchantController', 'getMerchantOffers']], // Returls Merchant offers list
    ['POST', '/getOfferDetails', ['Api\Controllers\MerchantController', 'getOfferDetails']], // Returns the Merchant offer details 
    ['POST', '/getOfferLocations', ['Api\Controllers\MerchantController', 'getOfferLocations']], // Returns Offer related Merchant locations 
    ['POST', '/getStoreLocations', ['Api\Controllers\MerchantController', 'getStoreLocations']], // Returns Merchant store locations 
    //Customer order related routes
    ['POST', '/createCustomerOrder', ['Api\Controllers\OrderController', 'createCustomerOrder']], // Accept and insert the customer order data    
    ['POST', '/createCustomerOrderDetails', ['Api\Controllers\OrderController', 'createCustomerOrderDetails']], // Accept and insert the customer order details data    
    ['POST', '/createCustomerOrderStatusTrack', ['Api\Controllers\OrderController', 'createCustomerOrderStatusTrack']], // Accept and insert the customer order status track data    
    ['POST', '/getCustomerOrders', ['Api\Controllers\OrderController', 'getCustomerOrders']], // returns the list of orders   
    ['POST', '/getCustomerOrderDetails', ['Api\Controllers\OrderController', 'getCustomerOrderDetails']], // returns the details of an order
    ['POST', '/getIndividualOrderDetails', ['Api\Controllers\OrderController', 'getIndividualOrderDetails']], // returns the details of an order details
    // product related routes
    ['POST', '/createProductMaster', ['Api\Controllers\ProductController', 'createProductMaster']], // Accept and insert the product data
    ['POST', '/createProductMerchant', ['Api\Controllers\ProductController', 'createProductMerchant']], // Accept and insert the product merchant data
    ['POST', '/createProductImages', ['Api\Controllers\ProductController', 'createProductImages']], // Accept and insert the product images data
    ['POST', '/createProductInventories', ['Api\Controllers\ProductController', 'createProductInventories']], // Accept and insert the product inventory data
    ['POST', '/createProductLocationInventories', ['Api\Controllers\ProductController', 'createProductLocationInventories']], // Accept and insert the product location inventory data
    ['POST', '/getProductList', ['Api\Controllers\ProductController', 'getProductList']], // gert the list of products
    ['POST', '/getProductDetails', ['Api\Controllers\ProductController', 'getProductDetails']], // get the details of product
    ['POST', '/getProductLocationWiseQuantity', ['Api\Controllers\ProductController', 'getProductLocationWiseQuantity']], // get the location wise quantity of product
    ['POST', '/checkProductStatus', ['Api\Controllers\ProductController', 'checkProductStatus']], // get the details of product
    ['POST', '/checkMerchantInventoryRestrictions', ['Api\Controllers\ProductController', 'checkMerchantInventoryRestrictions']], // get the details of product
    //Other merchants related route
    ['POST', '/createOtherMerchantAttributes', ['Api\Controllers\OtherMerchantController', 'createOtherMerchantAttributes']], // Accept and insert the other merchant attributes data
    ['POST', '/createOtherMerchantAttributeCustomers', ['Api\Controllers\OtherMerchantController', 'createOtherMerchantAttributeCustomers']], // Accept and insert the other merchant attribute customers data
    ['POST', '/createOtherMerchantAttributeCustomersCrawl', ['Api\Controllers\OtherMerchantController', 'createOtherMerchantAttributeCustomersCrawl']], // Accept and insert the other merchant attribute customers crawl data
    ['POST', '/getOtherMerchantCrawledPoints', ['Api\Controllers\OtherMerchantController', 'getOtherMerchantCrawledPoints']], // Accept parameters and provide customers crawled point data
    //Loyalty program related route
    ['POST', '/createLoyaltyProgram', ['Api\Controllers\LoyaltyProgramController', 'createLoyaltyProgram']], // Accept and insert the loyalty program data
    ['POST', '/createLoyaltyProgramSettings', ['Api\Controllers\LoyaltyProgramController', 'createLoyaltyProgramSettings']], // Accept and insert the loyalty program settings data
    ['POST', '/createLoyaltyProgramTiers', ['Api\Controllers\LoyaltyProgramController', 'createLoyaltyProgramTiers']], // Accept and insert the loyalty program tiers data
    ['POST', '/getLoyaltyProgramTierDetails', ['Api\Controllers\LoyaltyProgramController', 'getLoyaltyProgramTierDetails']], // Accept and insert the loyalty program tiers data
    // category related routes
    ['POST', '/getCategory', ['Api\Controllers\CategoryController', 'getCategory']], // Get Category data
    ['POST', '/createCategory', ['Api\Controllers\CategoryController', 'createCategory']], // Accept and insert the Category data
    // brand related routes
    ['POST', '/getBrand', ['Api\Controllers\BrandController', 'getBrand']], // Get Brand data
    ['POST', '/getSelectiveBrands', ['Api\Controllers\BrandController', 'getSelectiveBrands']], // Get Brand data
    ['POST', '/getBrandLocationDetails', ['Api\Controllers\BrandController', 'getBrandLocationDetails']], // Get Brand Location Details data
    ['POST', '/createBrand', ['Api\Controllers\BrandController', 'createBrand']], // Accept and insert the Brand data
    ['POST', '/updateBrand', ['Api\Controllers\BrandController', 'updateBrand']], // Accept and update the Brand data
    //If registered then this service will return user related all merchant data
    // Routes for otp API
    // send OTP for mobile message
    ['POST', '/getOtp', ['Api\Controllers\SmsController', 'getOtp']],
    // check OTP for mobile message
    ['POST', '/checkOtp', ['Api\Controllers\SmsController', 'checkOtp']],
    //This service will check wheather this user is already registered or not. 
    //If registered then this service will return user related all merchant data
    ['POST', '/loginCustomer', ['Api\Controllers\UserController', 'loginCustomer']],
    ['POST', '/registerCustomer', ['Api\Controllers\UserController', 'registerCustomer']],
    //get user details
    ['POST', '/getUserDetails', ['Api\Controllers\UserController', 'getDetails']],

    //get merchant details based on customer id
    ['POST', '/getMerchantDetails', ['Api\Controllers\MerchantController', 'getMerchantDetails']],
    //get merchant details based on merchant URL
    ['POST', '/getDetailsByURL', ['Api\Controllers\MerchantController', 'getDetailsByURL']],
    //add merchant
    ['POST', '/addMerchant', ['Api\Controllers\MerchantController', 'addMerchant']],
    //get other merchant dynamic fields
    ['POST', '/getMerchantDynamicFields', ['Api\Controllers\OtherMerchantController', 'getMerchantDynamicFields']],
    //get Offer list
    ['POST', '/getOfferList', ['Api\Controllers\MerchantController', 'getOfferList']],
    //Delete Merchant Card
    ['POST', '/deleteMerchantCard', ['Api\Controllers\MerchantController', 'deleteMerchantCard']],
    //UploadCardImage
    ['POST', '/managePhysicalCard', ['Api\Controllers\MerchantController', 'managePhysicalCard']],
    //Get product list
//    ['POST', '/getProductList', ['Api\Controllers\ProductController', 'getProductList']],
    //Get poitns based on merchant id and customer id
    ['POST', '/getPoints', ['Api\Controllers\CustomerController', 'getPoints']],
    //Get dashboard details from GetDashboardIconDetails
    ['POST', '/getDashboardIconDetails', ['Api\Controllers\DashBoardController', 'getDashboardIconDetails']],
    //update customer profile based on customer id
    ['POST', '/profileUpdate', ['Api\Controllers\CustomerController', 'profileUpdate']],
    //get merchant location
    ['POST', '/getLocation', ['Api\Controllers\MerchantController', 'getLocation']],
    //get brand name
    ['POST', '/getBrandName', ['Api\Controllers\BrandController', 'getBrandName']],
    //Get otp status    
    ['POST', '/getOtpVerifiedStatus', ['Api\Controllers\SmsController', 'getOtpVerifiedStatus']],
    // validate bill number based on brand id and bill number    
    ['POST', '/validateBillNumber', ['Api\Controllers\CustomerController', 'validateBillNumber']],
    // submit bill based on input parameters 
    ['POST', '/submitBill', ['Api\Controllers\CustomerController', 'submitBill']],
    // add customer log in customer login logs table
    ['POST', '/trackUserLog', ['Api\Controllers\CustomerController', 'trackUserLog']],
    // delete cutomer and merchant for testing purpose
    ['POST', '/testDeleteCustomerDetails', ['Api\Controllers\CustomerController', 'testDeleteCustomerDetails']],
    //Route for creating pos users
    ['POST', '/createPOSUser', ['Api\Controllers\UserController', 'createPOSUser']],
    //Route for LULU Mall Api Call
    ['POST', '/luluMallApiCall', ['Api\Controllers\LuluMallController', 'luluMallApiCall']], // insert temp bills
    // Get product status
    ['POST', '/checkSpecificProductStatus', ['Api\Controllers\ProductController', 'checkSpecificProductStatus']],
    // place order
    ['POST', '/placeOrder', ['Api\Controllers\OrderController', 'placeOrder']],
    // validate price against product total price
    ['POST', '/validatePriceVsPoints', ['Api\Controllers\ProductController', 'validatePriceVsPoints']],
    // check valida credentials
    ['POST', '/checkForOMCredentials', ['Api\Controllers\OtherMerchantController', 'checkForOMCredentials']],
    //Get poits details based on merchant id and customer id
    ['POST', '/getEarnRedeemPointDetails', ['Api\Controllers\CustomerController', 'getEarnRedeemPointDetails']],
    //Get poits details based on merchant id and customer id
    ['POST', '/generateBarCode', ['Api\Controllers\CustomerController', 'generateBarCode']],
    //Get pincode based on input digit
    ['POST', '/getPincode', ['Api\Controllers\CustomerController', 'getPincode']],
    //submit feed back
    ['POST', '/submitFeedback', ['Api\Controllers\UserController', 'submitFeedback']],
    //track product view
    ['POST', '/trackProductView', ['Api\Controllers\ProductController', 'trackProductView']],
    //Get pincode based on input digit
    ['POST', '/appInit', ['Api\Controllers\DashBoardController', 'appInit']],
    //API for Gaurav Sir to get customer data based on mobile number
    ['GET', '/getStaticWebPage', ['Api\Controllers\MerchantController', 'getStaticWebPage']],
    //API for Gaurav Sir to get customer data based on mobile number
    ['POST', '/sendTransactionSms', ['Api\Controllers\SmsController', 'sendTransactionSms']],
    ['POST', '/sendRefundSms', ['Api\Controllers\SmsController', 'sendRefundSms']],
    ['POST', '/sendTierChangeSms', ['Api\Controllers\SmsController', 'sendTierChangeSms']],
    ['POST', '/insertOrUpdateLoginAttribute', ['Api\Controllers\OtherMerchantController', 'insertOrUpdateLoginAttribute']],
    ['POST', '/sendAddBonusPoints', ['Api\Controllers\SmsController', 'sendAddBonusPoints']],
    //Get push notification count
    ['POST', '/getPushNotificationCount', ['Api\Controllers\PushNotificationController', 'getPushNotificationCount']],
    //Provide brand and related pos id list to goFrugal
    ['POST', '/getAllBrandsData', ['Api\Controllers\BrandController', 'getAllBrandsData']],
    //Earn Reward Campaign
    ['POST', '/earnRewardsCampaign', ['Api\Controllers\CampaignController', 'earnRewardsCampaign']],
    //Brand-Wise Campaign
    ['POST', '/brandWiseCampaign', ['Api\Controllers\CampaignController', 'brandWiseCampaign']],
    //check Earn Campaign priority if its applicable to customer transaction
    ['POST', '/checkEarnCampaignPriority', ['Api\Controllers\CampaignController', 'checkEarnCampaignPriority']],
    //Bill Submission Campaign
    ['POST', '/billSubmissionCampaign', ['Api\Controllers\CampaignController', 'billSubmissionCampaign']],
    //Birthday Anniversary Campaign 
    ['POST', '/birthdayAnniversaryCampaign', ['Api\Controllers\CampaignController', 'birthdayAnniversaryCampaign']],
    //Referral 1st Transaction Campaign 
    ['POST', '/referral1stTransactionCampaign', ['Api\Controllers\CampaignController', 'referral1stTransactionCampaign']],
    //Verify Referral Code
    ['POST', '/verifyReferralOrCouponCode', ['Api\Controllers\MerchantController', 'verifyReferralOrCouponCode']],
    //init order transaction
    ['POST', '/initOrderTransaction', ['Api\Controllers\OrderController', 'initOrderTransaction']],
    //confirm order transaction
    ['POST', '/confirmOrder', ['Api\Controllers\OrderController', 'confirmOrder']],
    //get referral details
    ['POST', '/getReferralDetails', ['Api\Controllers\CampaignController', 'getReferralDetails']],
    //get coupon code list details
    ['POST', '/getCouponCodeList', ['Api\Controllers\CampaignController', 'getCouponCodeList']],
    //webhook event Delivered
    ['POST', '/eventDelivered', ['Api\Controllers\WebhookController', 'eventDelivered']],
    
    //check push notification tapped
    ['POST', '/setNotificationTapped', ['Api\Controllers\PushNotificationController', 'setNotificationTapped']],
    
    //check push notification received
    ['POST', '/setNotificationReceived', ['Api\Controllers\PushNotificationController', 'setNotificationReceived']],
    
    ['POST', '/testGoFrugal', ['Api\Controllers\CustomerController', 'testGoFrugal']],
    ['POST', '/testRazorPay', ['Api\Controllers\OrderController', 'testRazorPay']],
    
    //Bill submission through coupon code
    ['POST', '/submissionCouponCode', ['Api\Controllers\CampaignController', 'submissionCouponCode']],

    //test default tier sms
    ['POST', '/testDefaultTier', ['Api\Controllers\MerchantController', 'testDefaultTier']],

    //bulk upload customers
    ['POST', '/bulkUploadCustomers', ['Api\Controllers\MerchantController', 'bulkUploadCustomers']],

    
];
