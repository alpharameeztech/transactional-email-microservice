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

## Send an email through an API endpoint

**API link:** ```[site.url]/api/v1/send/email```

**Headers:** ```Content-Type => application/json```

**Request Type:** ```Post```

Pass the data in json format including the following fields:

- Recipient's email as: 'to'
- Email's subject as: 'subject'
- Email's message body as: 'subject'

After sending the post request with the fields, the app will first try to send an email with the default email service provider(as configured on 'config/mail.php'). If for any reason, the default service is down, the app will pick any fallback service from the array defined in the '/config.mail.php' and send an email through that. 

Once the email is send, then the following actions are taken:

- API response will receive.  
- The response will have the status code and the email service provider name through which the email is sent
- The record is saved on the database table 'sent_emails'
- A log entry is also created under 'laravel.log' file

Take a look at the below screenshot which shows the success response when send a
request to this API endpoint using Postman.

![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/send_email_with_api.png)

**Note:**If invalid data is provided on send email API, an appropriate error response is returned, something similar to the screenshot below

![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/error_response_on_send_email.png)

## Send an email through CLI

A command is created to send an email from CLI.

Run the following command to learn about this send email command:
```
 php artisan send:email --help
```

Run this command to send an email from the CLI:
```
php artisan send:email
```
Once you run the command, the CLI will prompt for the following fields:
 
-Recipient's email
- Email's subject
- Email's body
 
Something like that will appear
 
![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/send_email_from_cli.png)

If you do not provide any of required fields, an error will appear on the CLI asking you for the missing fields.

**With CLI, only the log entry is created(as shown in the below screenshot) when the email is sent(wont be storing the under the database)**.

![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/cli_log_entery.png)

## Send an email through Frontend

A form is set up to compose an email.

When the email is successfully sent, following actions are taken:

- An email will be sent with either the default email service provider(if no exception is thrown) or with the fallback service
- Record gets saved to the database   
- A log entry is created under 'laravel.log' file

![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/send_email_from_frontend.png)

## Consumer self-service endpoint for user registration
**API link:** ```[site.url]/api/v1/register```

**Request Type:** ```Post```

A consumer self-service API endpoint exist to register a user.
This endpoint requires the following required fields:

- User name field as: 'name'
- User email fields as: 'email'
- User's password field as: 'password' 
- User's confirm password field as: 'password_confirmation'
   
When the user is successfully registered, the actions are taken:

- A json response received which will show the success message along with the service name used to send the welcome email
![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/user_registration_api.png)
- A log entry created
![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/user_registration_log_entry.png)
- User is successfully saved on the database
- A welcome email is sent to the user's email just registered

**Note:** If user registration failed for any reason, an appropriate error response is returned as the screenshot below

![Image description](https://somefreeresources.s3.ca-central-1.amazonaws.com/user_registration_error.png)
