<p align="center"><img src="https://logowik.com/content/uploads/images/bkash2848.jpg" width="400"></p>

## Bkash-PGW-Integration-API-Laravel-Package
Users can import this laravel package to integrate bkash pgw in their projects or businesses.

## Installing the package
You will have to make a slight adjustment to your new project composer.json file. Open the file and update it by including the following array somewhere in the object:

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Tirtho496/Bkash-PGW-Integration-API-Laravel-Package"
    }
]
```

Now composer will look into this repository for any installable package. Execute the following command to install the package:

```composer require tirtho496/bkash_pgw```

Now, open the config/app.php file and scroll down to the providers array. In that array, there should be a section for the package service providers. Add the following line of code in that section:

```
/*
 * Package Service Providers...
 */
Tirtho496\Bkash_pgw\Providers\PaymentProvider::class,
```
This will register the ```PaymentProvider``` class as one of the service providers for this project. Start the application using ```php artisan serve``` and call the ```/payment``` route for checkout using bkash pgw.
Place your code there to invoke the necessary functions from ```Payment.php``` for your checkout process.

## Using the functions
### Tokenized Checkout (With Agreement)
1. ```createHeader()``` Used to create the request header. Obtain value for ```Authorization``` by invoking the ```grantToken``` function.
   ```X-APP-Key``` should be collected from live credentials.
2. ```grantToken()``` Place your user-name and password in the ```header``` array and ```app_key``` and ```app_secret``` for the request
   body.
3. ```createAgreement()``` Begin the tokenized checkout process initially invoke the function in ```Payment.php``` using the route
   ```/bkash/createAgreement```
4. ```executeAgreement(paymentID)``` Completes the agreement process referenced by the ```paymentID``` parameter. Note it is invoked in the function executeCallback which is called by the route ```/bkash/executeCallback```
5. ```payAgreement()``` Checkout with agreement. The route ```/bkash/payAgreement``` invokes the function.
6. ```exectuePayment(paymentID)``` Completes the checkout process referenced by the ```paymentID```parameter. Note that this function is invoked within the ```callback()``` function called by the route ```/bkash/callback```

### Checkout (Without Agreement)
1. ```createHeader()``` Used to create the request header. Obtain value for ```Authorization``` by invoking the ```grantToken``` function.
   ```X-APP-Key``` should be collected from live credentials.
2. ```grantToken()``` Place your user-name and password in the ```header``` array and ```app_key``` and ```app_secret``` for the request
   body.
3. ```createPayment()``` Checkout with agreement. The route ```/bkash/create``` invokes the function.
4. ```exectuePayment(paymentID)``` Completes the checkout process referenced by the ```paymentID``` parameter. Note that this function is invoked within the ```callback()``` function called by the route ```/bkash/callback```

### General
1. ```makeRequest(url,header,method,body_data_json)``` process and send request for api hit.
2. ```queryPayment(paymentID)``` Can be called only when no response is returned from ```executePayment()``` to check the payment status.
3. ```searchPayment()``` Check for the status of a successful transaction.
4. ```refundPayment()``` Make a refund for a successfull transaction.
