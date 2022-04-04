# Payment Integration Test

## Project Setup

Requirements

- `docker`
- `composer`

1. Clone the repository from GitHub from `Terminal`.

```bash
git clone git@github.com:thihakyaw/payment-integration-test.git
```

1. Get into the project directory.

```bash
cd payment-integration-test
```

1. Install packages using `composer` .

```bash
composer install
```

If you are still using the `composer` version 1 and faced memory limit issue, please use this command.

```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

1. create a new file `.env` in root directory of the project and copy everything inside from `.env.example`
2. In project directory, run this command to setup the project in `docker`. It will take around 5-10 minutes for the first time composing for Laravel project. 
- Tip - set up alias in `zsh` for `./vendor/bin/sail` follow this [guideline](https://linuxhint.com/configure-use-aliases-zsh/) or if you are using `bash`, follow the bash alias setup [guideline](https://www.cyberciti.biz/faq/create-permanent-bash-alias-linux-unix/)

```bash
./vendor/bin/sail up -d
```

Install composer again using sail command just to make sure every packages required are in sail.

```bash
./vendor/bin/sail composer install
```

I recommend running in development (-d) mode as we are testing this on local machine.

1. Generate application key for Laravel before starting

```bash
./vendor/bin/sail artisan key:generate
```

1. Migrate database following this command

```bash
./vendor/bin/sail artisan migrate
```

Now, we can access the project from `localhost`.
![Screen Shot 2022-04-04 at 09 25 47](https://user-images.githubusercontent.com/16256698/161588993-669c9720-3e13-4837-94be-d41e6b7123f6.png)


## The architecture and the design

Framework - [Laravel](https://laravel.com)

Third-party Stripe Library - [Laravel Cashier](https://laravel.com/docs/9.x/billing)

**Database Design**

![Untitled Diagram drawio](https://user-images.githubusercontent.com/16256698/161589135-7b5ed5bc-5069-43cb-9d39-3e5eff9c0613.png)


**Sequence Diagram**

![Untitled Diagram drawio (2)](https://user-images.githubusercontent.com/16256698/161589164-b452126d-bfd7-4d9a-9adb-c9e1d00b4cbf.png)


**Testing**

As this project use `Laravel Cashier` package, there is no separated service classes for charging payment. There is no unit test therefore. Instead, I wrote `Feature Test` as a user’s perspective.

`Note - for feature test, I wanted to use fake name generator and fake email generator rather than hardcoding the name and email but there is something wrong with Faker from Laravel and time is limited, I have to use hard coded values for name and email.` 

To run the tests, run this command 

```bash
./vendor/bin/sail artisan test
```

**User Interface**

![Untitled](https://user-images.githubusercontent.com/16256698/161589263-820bea47-db8d-463b-bb9e-b6e35d84e4f6.png)

![Untitled](Payment%20In%20f86de/Untitled.png)

When you start call the [http://localhost](http://localhost/) , you will start to see the checkout component only first. After finished step 1, step 2 component will appear and after finishing the step 2, step 3 component will appear.

1. The checkout component. You have to fill in the price not lower than 349$. This is for a test purpose. You can fill any amount that you want to charge for the `Sony WH-1000XM4`. Fill in your name and email and then press `checkout` . You will see payment section
2. Fill the card information. `Card holder name` can be anyone’s name. For card information testing, you can use these number - 
- Visa Card - 4242 4242 4242 4242
- Master Card - 5200828282828210

Expiry Month and Expiry Year can be any month and year `not later than current month and year`. `CVC` and `ZIP` number can be any number.

For more Cards to test, check this [Stripe Card Testing Page](https://stripe.com/docs/testing).

1. After payment is charger, you will see the payment information including payment status and card information that Stripe has offer

**Database Connection Access**

In case you want to access database from SequelPro or Workbench, use these credentials.

The credentials are from `.env.example` . 

host - `localhost`

port - `3306`

username - `root`

password - `password`

**Postman Collection**

Check the `PaymentIntegrationTestPostmanCollection.json` from the project root directory.
