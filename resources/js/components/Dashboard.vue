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

    </v-container>
</template>

