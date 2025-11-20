<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SAML idP configuration file
    |--------------------------------------------------------------------------
    |
    | Use this file to configure the service providers you want to use.
    |
     */
    // Outputs data to your laravel.log file for debugging
    'debug' => false,
    // Define the email address field name in the users table
    'email_field' => 'username',
    // Define the name field in the users table
    'name_field' => 'username',
    // The URI to your login page
    'login_uri' => 'login',
    // Log out of the IdP after SLO
    'logout_after_slo' => env('LOGOUT_AFTER_SLO', true),
    // The URI to the saml metadata file, this describes your idP
    'issuer_uri' => 'saml/metadata',
    // The certificate
    // 'cert' => env('SAMLIDP_CERT'),
    // Name of the certificate PEM file, ignored if cert is used
    'certname' => 'cert.pem',
    // The certificate key
    // 'key' => env('SAMLIDP_KEY'),
    // Name of the certificate key PEM file, ignored if key is used
    'keyname' => 'key.pem',
    // Encrypt requests and responses
    'encrypt_assertion' => true,
    // Make sure messages are signed
    'messages_signed' => true,
    // Defind what digital algorithm you want to use
    'digest_algorithm' => \RobRichards\XMLSecLibs\XMLSecurityDSig::SHA256,
    // list of all service providers
    'sp' => [
        'aHR0cHM6Ly9iYW5rZGF0YXB1Ymxpa2FzaS5wYWRhbmcuZ28uaWQvc2FtbDIvMWNmNmFmNzMtYjdjNC00ZGU4LTg4MGEtNDM4ODc5NTg3NWE3L2Fjcw==' => [
            'destination' => 'https://bankdatapublikasi.padang.go.id/saml2/1cf6af73-b7c4-4de8-880a-4388795875a7/acs',
            'logout' => 'https://bankdatapublikasi.padang.go.id/saml2/1cf6af73-b7c4-4de8-880a-4388795875a7/sls',
            'certificate' => 'file://' . storage_path('samlidp/bankdatacert.pem'),
            'query_params' => true,
            'encrypt_assertion' => false,
        ]
    ],

    // If you need to redirect after SLO depending on SLO initiator
    // key is beginning of HTTP_REFERER value from SERVER, value is redirect path
    'sp_slo_redirects' => [
        // 'http://103.141.75.27:81' => 'http://103.141.75.27:81',
        // 'http://103.141.74.98:8081' => 'http://103.141.74.98:8081',
        'bankdatapublikasi.padang.go.id' => 'bankdatapublikasi.padang.go.id',

    ],

    // All of the Laravel SAML IdP event / listener mappings.
    'events' => [
        'CodeGreenCreative\SamlIdp\Events\Assertion' => [],
        // 'Illuminate\Auth\Events\Logout' => ['CodeGreenCreative\SamlIdp\Listeners\SamlLogout'],
        'Illuminate\Auth\Events\Authenticated' => ['CodeGreenCreative\SamlIdp\Listeners\SamlAuthenticated'],
        'Illuminate\Auth\Events\Login' => ['CodeGreenCreative\SamlIdp\Listeners\SamlLogin'],
    ],

    // List of guards saml idp will catch Authenticated, Login and Logout events
    'guards' => ['web'],
];
