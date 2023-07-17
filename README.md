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
