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

- Run following commands:
```
git clone https://github.com/alpharameeztech/transactional-email-microservice.git
composer install
cp .env.example .env
npm install
php artisan migrate
```

## Installation Instructions with Docker

Run the following command:  

```
git clone https://github.com/alpharameeztech/transactional-email-microservice.git
docker run --rm -v $(pwd):/app composer install
cp .env.example .env
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

![1](https://user-images.githubusercontent.com/36469012/137141058-383ce726-30f3-49c2-9729-4b068faff2a3.png)

**Note:**If invalid data is provided on send email API, an appropriate error response is returned, something similar to the screenshot below

![2](https://user-images.githubusercontent.com/36469012/137141292-cb03f546-0a55-464e-81c3-9cca58c63dfe.png)

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
 
![3](https://user-images.githubusercontent.com/36469012/137141352-41626e21-a0b0-446a-af05-e6ea4568614b.png)


If you do not provide any of required fields, an error will appear on the CLI asking you for the missing fields.

**With CLI, only the log entry is created(as shown in the below screenshot) when the email is sent(wont be storing the under the database)**.

![4](https://user-images.githubusercontent.com/36469012/137141452-6009f5b1-c492-4ed5-a78c-db52c2c4d68f.png)


## Send an email through Frontend

A form is set up to compose an email.

When the email is successfully sent, following actions are taken:

- An email will be sent with either the default email service provider(if no exception is thrown) or with the fallback service
- Record gets saved to the database   
- A log entry is created under 'laravel.log' file

![5](https://user-images.githubusercontent.com/36469012/137141511-bd53ad9e-8f58-48c4-bce0-1cef53cc5425.png)

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
![6](https://user-images.githubusercontent.com/36469012/137141582-dd700a78-58ce-4541-8c21-dc4731f7cefb.png)

- A log entry created
![7](https://user-images.githubusercontent.com/36469012/137141738-3b01aa91-f13d-4e7e-abac-86a19ddd8f4f.png)

- User is successfully saved on the database
- A welcome email is sent to the user's email just registered

**Note:** If user registration failed for any reason, an appropriate error response is returned as the screenshot below

![8](https://user-images.githubusercontent.com/36469012/137141813-3f8819c7-99a7-4318-a1f7-9a7cd48d255a.png)

## Consumer self-service endpoint for forgot password

**API link:** ```[site.url]/api/v1/forgot/password```

**Request Type:** ```Post```

An API endpoint exist to generate a token for password recovery if the user is registered with that email address.

When this API is called, following are the things that will happen:

- A json response is returned with the appropriate status code and message
![9](https://user-images.githubusercontent.com/36469012/137141950-e0fcc11d-073c-4738-9470-94cb4a7012a6.png)

- A log entry is created on the 'laravel.log' file
![10](https://user-images.githubusercontent.com/36469012/137141994-959db812-b30a-4fe3-b16c-af2fe0433189.png)

- A password reset token is saved on the database table

**Note: No email is send on forgot password API request.**

**Note:** If invalid data is provided on forgot password api, an appropriate error response is returned, something similar to the screenshot below

![11](https://user-images.githubusercontent.com/36469012/137142106-8249b946-351e-4db3-965d-82e4277f9937.png)

## Consumer self-service endpoint for password reset

**API link:** ```[site.url]/api/v1/password/reset```

**Request Type:** ```Post```

An API endpoint exist to reset a password based on the forgot password token.

When this API is called, following are the things that will happen:

- A json response is returned with the appropriate status code and message
![12](https://user-images.githubusercontent.com/36469012/137142150-83c15677-c076-44cf-855d-77d8debdd348.png)

- A log entry is created on the 'laravel.log' file
![13](https://user-images.githubusercontent.com/36469012/137142225-34493ce4-5aa4-46b9-ae56-bc62422ae0dd.png)

- A password reset token entry gets deleted(once the token is utilized)
- Password gets reset and saved on the database table

**Note:** If invalid data is provided on reset password api, an appropriate error response is returned, something similar to the screenshot below

![14](https://user-images.githubusercontent.com/36469012/137142264-f5e2e9ea-522f-4b17-8602-6fd06047238c.png)

## About frontend

Frontend is a single page application built using Vue and Vuetify and it composed of mainly three pages:

- Dashboard
- Compose Email
- Sent(Emails)

**Dashboard:**

The Dashboard is a simple text page that describes about the application functionality.

![15](https://user-images.githubusercontent.com/36469012/137142374-7c2a4f95-c043-479f-a96c-b6ffb5a018e5.png)

**Compose Email:**

This page includes a simple form to send an email.

![16](https://user-images.githubusercontent.com/36469012/137142419-34e4d490-ba00-48c3-bf3a-e72c76af7f37.png)

**Sent Emails:**

The page will list all the emails that have been sent successfully.

![17](https://user-images.githubusercontent.com/36469012/137142446-52215510-e9c5-4827-84cb-84e98a570859.png)
