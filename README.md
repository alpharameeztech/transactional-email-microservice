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
