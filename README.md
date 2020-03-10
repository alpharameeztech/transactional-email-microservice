# Transactional email microservice
This app is a simple email microservice built using Laravel 6, Vue 2 and, Vuetify 2.
The app sends an email to the recipient's email through the following:

- API's endpoint
- CLI
- Frontend
 
The app utilizes the two email service providers:

- MailJet 
- SendGrid
 
 **Note:** At the moment, the app supports only one recipient's email address when sending an email.

## The API's endpoint

- Post request to send an email: ```[site.url]/api/v1/send/email```
- Post request to register the user: ```[site.url]/api/v1/register```
- Post request to generate forgot password token: ```[site.url]/api/v1/forgot/password```
- Post request to reset password with the token: ```[site.url]/api/v1/password/reset```

## Why two email service providers?
The purpose of using the two email service providers is to utilize one service as a default and the other as a fallback service(which can be set under the config/mail.php).

**Note: The app has the flexibility to accept more than one fallback service.**

## Installation Instructions without Docker

- Clone the repo
- Run following commands:
```
composer install
npm install
php artisan migrate
```

## Installation Instructions with Docker

Run the following command:  

```
docker-compose up
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan migrate

```
As a final step, visit http://your_server_ip in the browser

**Note:** Create a user for MySQL

## How to verify that the fallback service is actually working?
The app first tries to send an email with whatever the default email service provider is set under the config/mail.php:
```
'service' => env('DEFAULT_EMAIL_SERVICE', 'MailJet'),
```

**Note: DEFAULT_EMAIL_SERVICE variable value should be the exact name of any class defined under [site.url]/app/Interfaces/EmailInterface/Implementations/[file].php**

The fallback email services are defined under the 'fallbacks' array of config/mail.php file as:
```
  'fallbacks' => [ 'SendGrid']
```

**Note: 'fallbacks' array value should be the exact name of any class defined under [site.url]/app/Interfaces/EmailInterface/Implementations/[file].php**

So if for any reason, the default email service provider is down, the App changes the default email
service provider from any fallback service list

```
'service' => env('DEFAULT_EMAIL_SERVICE', 'MailJet')

'fallbacks' => ['SendGrid']
```

**Note: One test specifically written to prove this fallback service.**

## How to add more email service provider as a fallback?

Lets say, MailGun needs to be added as a fallback email service, then following are the steps to follow:

- Create a class named as 'MailGun'(name the file as same as ClassName) under [site.ur]/app/Interfaces/EmailInterface/Implementations/MailGun.php. This class should implement Email Interface

- Then add this class name under 'config/mail.php'
    
```
    'fallbacks' => ['SendGrid', 'MailGun']
```
