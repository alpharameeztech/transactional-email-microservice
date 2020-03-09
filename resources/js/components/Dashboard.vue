<template>
    <v-container>

        <p class="title">
            Transactional email microservice
        </p>

        <p class="text-justify">
            This app is a simple email microservice built using Laravel 6, Vue 2 and, Vuetify 2.
        </p>

        <p>
            The app sends an email to the recipient's email through the following:
        </p>

        <ul>
            <li>
                API's endpoint
            </li>
            <li>
                CLI
            </li>
            <li>
                Frontend
            </li>
        </ul>


        <p>
            The app utilizes the two email service providers:
        </p>

        <ul>
            <li>
                MailJet
            </li>
            <li>
                SendGrid
            </li>
        </ul>

        <p class="title">
            The API's endpoint
        </p>

        <ul>
            <li>
                Post request to send an email: <kbd>[site.url]/api/v1/send/email</kbd>
            </li>
            <li>
                Post request to register the user: <kbd>[site.url]/api/v1/register</kbd>
            </li>
            <li>
                Post request to generate forgot password token: <kbd>[site.url]/api/v1/forgot/password</kbd>
            </li>
            <li>
                Post request to reset password with the token: <kbd>[site.url]/api/v1/password/reset</kbd>
            </li>
        </ul>

        <p class="title">
            Why two email service providers?
        </p>

        <p>
            The purpose of using the two email service providers is to utilize one service as a
            default and the other as a fallback
            service(which can be set under the <code>config/mail.php</code>).
        </p>

        <p class="subtitle-2">
            <v-icon>
                info
            </v-icon>Note: The app has the flexibility to accept more than one fallback service.
        </p>

        <p class="title">
            Installation Instructions
        </p>

        <ul>
            <li>Clone the repo</li>
            <li>Install composer dependencies using:
                <kbd>composer install</kbd>
            </li>
            <li>Run migrations:
                <kbd>php artisan migrate</kbd>
            </li>
        </ul>

        <p class="title">
            How to verify that the fallback service is actually working?
        </p>

        <p>
            The app first tries to send an email with whatever the default email service provider is set under the <code>config/mail.php</code>:
        </p>
        <code>
            'service' => env('DEFAULT_EMAIL_SERVICE', 'MailJet'),
        </code>

        <p class="subtitle-2">
            <v-icon>
                info
            </v-icon>Note: DEFAULT_EMAIL_SERVICE variable value should be the exact name of any class defined under
            <kbd>[site.url]/app/Interfaces/EmailInterface/Implementations/[file].php</kbd>
        </p>

        <p>
            The fallback email services are defined under the  'fallbacks' array of
            <code>config/mail.php</code> file as:
        </p>

        <code>
            'fallbacks' => [ 'SendGrid']
        </code>

        <p class="subtitle-2">
            <v-icon>
                info
            </v-icon>Note: 'fallbacks' array value should be the exact name of any class defined under
            <kbd>[site.url]/app/Interfaces/EmailInterface/Implementations/[file].php</kbd>
        </p>

        <p>
            So if for any reason, the default email service provider is down, the App changes the
            <code>DEFAULT_EMAIL_SERVICE</code> variable value with any service(randomly) from the
            <code>
                'fallbacks' => ['SendGrid']
            </code> array.
        </p>

        <p class="subtitle-2">
            <v-icon>
                info
            </v-icon>Note: One test specifically written to prove this fallback service.
        </p>

        <p class="title">
            How to add more email service provider as a fallback?
        </p>

        <p>
            Lets say, MailGun needs to be added as a fallback email service, then following are stesp to follow:
        </p>

        <ul>
            <li>
                Create a class named as 'MailGun'(name the file as same as ClassName)
                under <kbd>[site.url]/app/Interfaces/EmailInterface/Implementations/MailGun.php</kbd>.
                This class should implement Email Interface
            </li>
            <li>Then add this class name under
                <code>
                    'fallbacks' => ['SendGrid', 'MailGun']
                </code>
                array of  <code>config/mail.php</code> file
            </li>
        </ul>

        <p class="title">
            Send an email through an API endpoint
        </p>
        <p>
            API link: <kbd>[site.url]/api/v1/send/email</kbd>
        </p>
        <p>
            Headers: <code>Content-Type => application/json</code>
        </p>
        <p>
            Pass the data in json format including the following fields:
        </p>
        <ul>
            <li>Recipient's email as: 'to'</li>
            <li>Email's subject as: 'subject'</li>
            <li>Email's message body as: 'subject'</li>
        </ul>
        <p>
            After sending the post request with the fields, the app will first try to send an email
            with the default email service provider(as configured on 'config/mail.php').
            If for any reason, the default service is down, the app will pick any fallback service from the array
            defined in the '/config.mail.php' and send an email through that.
            Once the email is send, then the following actions are performed:</p>

        <ul>
            <li>API response will receive.
            </li>
            <li>
                The response will have the status code and the email service provider name
                through which the email is sent
            </li>
            <li>The record is saved on the database table 'sent_emails'</li>
            <li>A log entry is also created under 'laravel.log' file</li>
        </ul>

        <p>Take a look at the below screenshot which shows how to call this API endpoint using Postman.</p>

        <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/send_email_with_api.png" />

        <p class="font-weight-bold">
            If invalid data is provided on send email API, an appropriate error response is returned, something similar to the screenshot below
        </p>

        <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/error_response_on_send_email.png" />

        <v-system-bar
            dark
            color="primary"
            height="5"
        ></v-system-bar>

        <p class="title">
            Send an email through CLI
        </p>

        <p>
            A command is created to send an email from CLI
        </p>
        <p>
            Run the following command to learn about this send email command:  <kbd> php artisan send:email --help</kbd>
        </p>

        <p>
            Run this command to send an email from the CLI: <kbd>php artisan send:email</kbd>
        </p>

        <p>Once you run the command, the CLI will prompt for the following fields:</p>
        <ul>
            <li>
                Recipient's email
            </li>
            <li>
                Email's subject
            </li>
            <li>
                Email's body
            </li>
        </ul>

        <p>Something like that will appear</p>

        <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/send_email_from_cli.png" />

        <p>
            If you do not provide any of required fields, an error will appear on the CLI asking you for the missing fields.
        </p>
        <p>
            With CLI, only the log entry is created(as shown in the below screenshot) when the email is sent(wont be storing the under the database).
        </p>
        <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/cli_log_entery.png" />

        <v-system-bar
            dark
            color="primary"
            height="5"
        ></v-system-bar>

        <p class="title">
            Send an email through Frontend
        </p>
        <p>
            A form is set up to compose an email.
        </p>

        <p>When the email is successfully sent, following actions are performed:</p>

        <ul>
            <li>
                An email will be sent with either the default email service provider(if no exception is thrown) or with the fallback service
            </li>
            <li>
                A log entry is created under 'laravel.log' file
            </li>
            <li>The entry will be saved under the 'sent_emails' table</li>
        </ul>

        <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/send_email_from_frontend.png" />

        <v-system-bar
            dark
            color="primary"
            height="5"
        ></v-system-bar>

        <p class="title">
            Consumer self-service endpoint for user registration
        </p>
        <p>
            API link: <kbd>[site.url]/api/v1/register</kbd>
        </p>
        <p>
            A consumer self-service API endpoint exist to register a user.
        </p>
        <p>
            This endpoint requires the following required fields:
        </p>

        <ul>
            <li>
                User name field as: 'name'
            </li>
            <li>
                User email fields as: 'email'
            </li>
            <li>
                User's password field as: 'password'
            </li>
            <li>
                User's confirm password field as: 'password_confirmation'
            </li>
        </ul>

        <p>
            When the user is successfully registered, the following actions are performed:
        </p>
        <ul>
            <li>
                A json response received which will show the success message along with the service name used to send the welcome email
            </li>

            <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/user_registration_api.png" />

            <li>
                A log entry created
            </li>

            <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/user_registration_log_entry.png" />

            <li>
                User is successfully saved on the database
            </li>
            <li>
                A welcome email is sent to the user's email just registered
            </li>
        </ul>

        <p class="font-weight-bold">If user registration failed for any reason, an appropriate error response is returned</p>

        <img src="https://somefreeresources.s3.ca-central-1.amazonaws.com/user_registration_error.png" />

        <v-system-bar
            dark
            color="primary"
            height="5"
        ></v-system-bar>

    </v-container>
</template>

