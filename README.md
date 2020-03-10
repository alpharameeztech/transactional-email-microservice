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
