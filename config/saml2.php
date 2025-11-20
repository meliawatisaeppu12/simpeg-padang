<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    |
    | This will allow you to override the tenant model with your own.
    |
    */

    'tenantModel' => \Slides\Saml2\Models\Tenant::class,

    /*
    |--------------------------------------------------------------------------
    | Use built-in routes
    |--------------------------------------------------------------------------
    |
    | If "useRoutes" set to true, the package defines five new routes:
    |
    | Method | URI                             | Name
    | -------|---------------------------------|------------------
    | POST   | {routesPrefix}/{uuid}/acs       | saml.acs
    | GET    | {routesPrefix}/{uuid}/login     | saml.login
    | GET    | {routesPrefix}/{uuid}/logout    | saml.logout
    | GET    | {routesPrefix}/{uuid}/metadata  | saml.metadata
    | GET    | {routesPrefix}/{uuid}/sls       | saml.sls
    |
    */

    'useRoutes' => true,

    /*
    |--------------------------------------------------------------------------
    | Built-in routes prefix
    |--------------------------------------------------------------------------
    |
    | Here you may define the prefix for built-in routes.
    |
    */

    'routesPrefix' => '/saml2',

    /*
    |--------------------------------------------------------------------------
    | Middle groups to use for the SAML routes
    |--------------------------------------------------------------------------
    |
    | Note, Laravel 5.2 requires a group which includes StartSession
    |
    */

    'routesMiddleware' => ['saml'],

    /*
    |--------------------------------------------------------------------------
    | Signature validation
    |--------------------------------------------------------------------------
    |
    | Set to true if you want to use parameters from $_SERVER to validate the signature.
    |
    */

    'retrieveParametersFromServer' => false,

    /*
    |--------------------------------------------------------------------------
    | Login redirection URL.
    |--------------------------------------------------------------------------
    |
    | The redirection URL after successful login.
    |
    */

    'loginRoute' => env('SAML2_LOGIN_URL'),

    /*
    |--------------------------------------------------------------------------
    | Logout redirection URL.
    |--------------------------------------------------------------------------
    |
    | The redirection URL after successful logout.
    |
    */

    'logoutRoute' => env('SAML2_LOGOUT_URL'),


    /*
    |--------------------------------------------------------------------------
    | Login error redirection URL.
    |--------------------------------------------------------------------------
    |
    | The redirection URL after login failing.
    |
    */

    'errorRoute' => env('SAML2_ERROR_URL'),

    /*
    |--------------------------------------------------------------------------
    | Strict mode.
    |--------------------------------------------------------------------------
    |
    | If 'strict' is True, then the PHP Toolkit will reject unsigned
    | or unencrypted messages if it expects them signed or encrypted
    | Also will reject the messages if not strictly follow the SAML
    | standard: Destination, NameId, Conditions... are validated too.
    |
    */

    'strict' => true,

    /*
    |--------------------------------------------------------------------------
    | Debug mode.
    |--------------------------------------------------------------------------
    |
    | When enabled, errors must be printed.
    |
    */

    'debug' => env('SAML2_DEBUG', env('APP_DEBUG', false)),

    /*
    |--------------------------------------------------------------------------
    | Whether to use `X-Forwarded-*` headers to determine port/domain/protocol.
    |--------------------------------------------------------------------------
    |
    | If 'proxyVars' is True, then the Saml lib will trust proxy headers
    | e.g X-Forwarded-Proto / HTTP_X_FORWARDED_PROTO. This is useful if
    | your application is running behind a load balancer which terminates SSL.
    |
    */

    'proxyVars' => false,

    /*
    |--------------------------------------------------------------------------
    | Service Provider configuration.
    |--------------------------------------------------------------------------
    |
    | General setting of the service provider.
    |
    */

    'sp' => [

        /*
        |--------------------------------------------------------------------------
        | NameID format.
        |--------------------------------------------------------------------------
        |
        | Specifies constraints on the name identifier to be used to
        | represent the requested subject.
        |
        */

        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',

        /*
        |--------------------------------------------------------------------------
        | SP Certificates.
        |--------------------------------------------------------------------------
        |
        | Usually x509cert and privateKey of the SP are provided by files placed at
        | the certs folder. But we can also provide them with the following parameters.
        |
        */

        'x509cert' => env('SAML2_SP_CERT_x509','MIIEbzCCA1egAwIBAgIUF9rmjIa9RoCn/iCQv75jmEvbHogwDQYJKoZIhvcNAQEL
BQAwgcYxCzAJBgNVBAYTAklEMRYwFAYDVQQIDA1XZXN0IFN1bWF0ZXJhMQ8wDQYD
VQQHDAZQYWRhbmcxHzAdBgNVBAoMFlBlbWVyaW50YWggS290YSBQYWRhbmcxKTAn
BgNVBAsMIERpbmFzIEtvbXVuaWthc2kgZGFuIEluZm9ybWF0aWthMRwwGgYDVQQD
DBNzaW1wZWcucGFkYW5nLmdvLmlkMSQwIgYJKoZIhvcNAQkBFhVudXJoYWtpbUBw
YWRhbmcuZ28uaWQwHhcNMjUwMjEyMDkwMzE1WhcNMjYwMjEyMDkwMzE1WjCBxjEL
MAkGA1UEBhMCSUQxFjAUBgNVBAgMDVdlc3QgU3VtYXRlcmExDzANBgNVBAcMBlBh
ZGFuZzEfMB0GA1UECgwWUGVtZXJpbnRhaCBLb3RhIFBhZGFuZzEpMCcGA1UECwwg
RGluYXMgS29tdW5pa2FzaSBkYW4gSW5mb3JtYXRpa2ExHDAaBgNVBAMME3NpbXBl
Zy5wYWRhbmcuZ28uaWQxJDAiBgkqhkiG9w0BCQEWFW51cmhha2ltQHBhZGFuZy5n
by5pZDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBANIibhGydput1dY1
1V2hiJwooSeqMToiCrzAGNE4PHTE0RVQf3CgS1xbOlYn+crRXpKBSTUm2N5E1OrN
B/C7D8xug/w/tviNOVQuNU0rOXyt6UJC0L1gUoYWCFxdt6iUHjivXvgzgaUy+llt
5DSlyG5YGfO8kLywwqlYbKN5/Rbx52Zg8b+oP6TuSorHoweEFAiVkHyyEUoanYcd
WyM9me0pLv+sJ0qNQ49hJwr0uzxnKe+nt0z6D2GvgxBKpX51Zt87RVwDqeNRI4NX
rp0lEOLf4vu/UtWIMJGPuaB0+9y0ufU/hMlsmkVFQgBsNfa6jpBtBlMJ0AlfyZsl
ozyvocsCAwEAAaNTMFEwHQYDVR0OBBYEFMgwzciFfyQYeio8uvLy9WtpTiSOMB8G
A1UdIwQYMBaAFMgwzciFfyQYeio8uvLy9WtpTiSOMA8GA1UdEwEB/wQFMAMBAf8w
DQYJKoZIhvcNAQELBQADggEBAIKntEhDIbcyTseEFfLJ5qvXxTWpK4dV51ZwCjhW
tuJzwxqRQ6+nR6phnDb6Wa5YCriU7NjK5WacBZGFiCVg9ypqn/6XaGQzMPCAahtJ
okBn1kiJCjdRalglgKFLL3EdxGbGQgRZsvfCK8kVJ4wQ7JDKTHl5pwyh8E5KsDFb
MmipaLUAxnApye2kqEw5aGPn/B+ZT8Ir62zx643TnL2wpJa1IN8xVpbZxe9ZKqz/
/APUd7bczSAX/idAj5Z1pP7bG2ZpWjA6REBhSoa8glyqkXnnaqBfzt4IxfQyqpTg
I55RAeGs39dumnT5R4jhBQI4rsgjSsBx4pGb3iNumSoQAf4='),

        'privateKey' => env('SAML2_SP_CERT_PRIVATEKEY','MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDSIm4RsnabrdXW
NdVdoYicKKEnqjE6Igq8wBjRODx0xNEVUH9woEtcWzpWJ/nK0V6SgUk1JtjeRNTq
zQfwuw/MboP8P7b4jTlULjVNKzl8relCQtC9YFKGFghcXbeolB44r174M4GlMvpZ
beQ0pchuWBnzvJC8sMKpWGyjef0W8edmYPG/qD+k7kqKx6MHhBQIlZB8shFKGp2H
HVsjPZntKS7/rCdKjUOPYScK9Ls8Zynvp7dM+g9hr4MQSqV+dWbfO0VcA6njUSOD
V66dJRDi3+L7v1LViDCRj7mgdPvctLn1P4TJbJpFRUIAbDX2uo6QbQZTCdAJX8mb
JaM8r6HLAgMBAAECggEAHOEsfXQohubSP7lwVIjxzHxtAZWLZHDvRtej0YVIEchG
8AX7LdBp6wyCrPqbgvtZYwsvs4VeQtX06Tw6fiRHXJJHw3BCRlCqc15SZwOsx+zi
5P9nzs8hKFp41bDvn9STfrjjdXsmszyZWZcXyQCL1lZ3Yp8kyToF1t5XN/R5CyD9
7MvtK1XgmJww+edilyJVj9KyNb4Jz32zhwjb1woFs5HK/ZOkh3xTK8ZekFoga3cF
P9Sa0zqwY7IlsBCpvPihznFOpVvPpYifJyIKmXoo6TorYi1s4JLXYSdbBzynQVwN
6Wwtu5D7xt410tK/2+ZMcEn/afcPtcqRaDTtGFjKSQKBgQDUlUA842OZXmP4tfUH
KEFhkd7jf8xdbHLe/n+dGXosVSwstwJFp/bPdoDhgUotlGcNFKvISFcJvBi5Og9/
Qu5/pL9jmETd+TbuRhvw/RUOLsh0mXdajX4BL6lMhKqLjTZ2GAu9ytp4l5BqfS+f
TodEKYcyvlCyNm+1d/4jSFPNBQKBgQD9DSjzLuP8sauOn793csZNGu42SKLAlYE7
QiVvJABJpqsqQ3kFN1q4Mbrxh3yLR8c/a9QYZ998ubmm18jIkBnwNTGyc3Bvnglc
yBlxS+kss2DxQkhzk9mkHPR9doYgN0wI/OP9zMhv2BznoI1fsr2ViK305JrT2EX3
H+BSW3psjwKBgHxezXRR/IVQCeEKY05KqOWyd9pVfHTz68i20GZS731cXznR4Axs
liMOS5yjDYjZF+k5PL8yQ2m4mCZV55cmy232LtrPzQqosRB6CALXrifcCv7cgk2C
FkQPZFcWDskSVtiEfOoO8f43fhAvKVtkkBK60RRI7+Ezo+thRlfSCuUZAoGBAJw5
X6qirEX5OjzXCtGnnh1EHwSMW04h/qKi95Fh3Hub1dhFx5Uc03kb2pn6Vz11luDk
pRBcMHFECAWk+mQ38ouFi6Tr9+Iw4v7Q2kRD5TSfmZ3YmfogsSDMb3R1k+CXwu1Z
kzfBEY1bEp09VYpbQwfqVdr2t09KGgezOj4eE7qpAoGAGUBxr2rXOXVEOK7NjZFt
0W56rsHlszAi0ap87Pwc3pLvdAQdufN8sD8cqSRHvzdMi25enrr+UDVGHV5beWnn
gHLhqhrR9chO6k62wsghwekRY3V2M/f9qwukDncaxfQWm9h9EYd20NpEyn9xbky/
rVinvIR+qdbLxhGGD9Fee2g='),

        /*
        |--------------------------------------------------------------------------
        | Identifier (URI) of the SP entity.
        |--------------------------------------------------------------------------
        |
        | Leave blank to use the 'saml.metadata' route.
        |
        */

        'entityId' => env('SAML2_SP_ENTITYID',''),

        /*
        |--------------------------------------------------------------------------
        | The Assertion Consumer Service (ACS) URL.
        |--------------------------------------------------------------------------
        |
        | URL Location where the <Response> from the IdP will be returned, using HTTP-POST binding.
        | Leave blank to use the 'saml.acs' route.
        |
        */

        'assertionConsumerService' => [
            'url' => '',
        ],

        /*
        |--------------------------------------------------------------------------
        | The Single Logout Service URL.
        |--------------------------------------------------------------------------
        |
        | Specifies info about where and how the <Logout Response> message MUST be
        | returned to the requester, in this case our SP.
        |
        | URL Location where the <Response> from the IdP will be returned, using HTTP-Redirect binding.
        | Leave blank to use the 'saml.sls' route.
        |
        */

        'singleLogoutService' => [
            'url' => ''
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OneLogin security settings.
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'security' => [

        /*
        |--------------------------------------------------------------------------
        | NameId encryption
        |--------------------------------------------------------------------------
        |
        | Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        | will be encrypted.
        |
        */

        'nameIdEncrypted' => true,

        /*
        |--------------------------------------------------------------------------
        | AuthnRequest signage
        |--------------------------------------------------------------------------
        |
        | Indicates whether the <samlp:AuthnRequest> messages sent by
        | this SP will be signed. The Metadata of the SP will offer this info
        |
        */

        'authnRequestsSigned' => true,

        /*
        |--------------------------------------------------------------------------
        | Logout request signage
        |--------------------------------------------------------------------------
        |
        | Indicates whether the <samlp:logoutRequest> messages sent by this SP
        | will be signed.
        |
        */

        'logoutRequestSigned' => true,

        /*
        |--------------------------------------------------------------------------
        | Logout response signage
        |--------------------------------------------------------------------------
        |
        | Indicates whether the <samlp:logoutResponse> messages sent by this SP
        | will be signed.
        |
        */

        'logoutResponseSigned' => true,

        /*
        |--------------------------------------------------------------------------
        | Whether need to sign metadata.
        |--------------------------------------------------------------------------
        |
        | The possible values:
        | - false
        | - true (use certs)
        | - array:
        |   ```
        |   [
        |       'keyFileName' => 'metadata.key',
        |       'certFileName' => 'metadata.crt'
        |   ]
        |   ```
        |
        */

        'signMetadata' => true,

        /*
        |--------------------------------------------------------------------------
        | Requirement to sign messages.
        |--------------------------------------------------------------------------
        |
        | Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        | <samlp:LogoutResponse> elements received by this SP to be signed.
        |
        */

        'wantMessagesSigned' => true,

        /*
        |--------------------------------------------------------------------------
        | Requirement to sign assertion elements.
        |--------------------------------------------------------------------------
        |
        | Indicates a requirement for the <saml:Assertion> elements received by
        | this SP to be signed.
        |
        */

        'wantAssertionsSigned' => true,

        /*
        |--------------------------------------------------------------------------
        | Requirement to encrypt NameID.
        |--------------------------------------------------------------------------
        |
        | Indicates a requirement for the NameID received by this SP to be encrypted.
        |
        */

        'wantNameIdEncrypted' => false,

        /*
        |--------------------------------------------------------------------------
        | Authentication context.
        |--------------------------------------------------------------------------
        |
        | Set to false and no AuthContext will be sent in the AuthNRequest,
        |
        | Set true or don't present this parameter and you will get an
        | AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        |
        | Set an array with the possible auth context values:
        | ['urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509']
        |
        */

        'requestedAuthnContext' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact information.
    |--------------------------------------------------------------------------
    |
    | It is recommended to supply a technical and support contacts.
    |
    */

    'contactPerson' => [
        'technical' => [
            'givenName' => env('SAML2_CONTACT_TECHNICAL_NAME', 'Nur Raga'),
            'emailAddress' => env('SAML2_CONTACT_TECHNICAL_EMAIL', 'nurraga.11@icloud.com')
        ],
        'support' => [
            'givenName' => env('SAML2_CONTACT_SUPPORT_NAME', 'Nur Raga'),
            'emailAddress' => env('SAML2_CONTACT_SUPPORT_EMAIL', 'nurraga.11@icloud.com')
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization information.
    |--------------------------------------------------------------------------
    |
    | The info in en_US lang is recommended, add more if required.
    |
    */

    'organization' => [
        'en-US' => [
            'name' => env('SAML2_ORGANIZATION_NAME', 'Pemerintah Kota Padang'),
            'displayname' => env('SAML2_ORGANIZATION_NAME', 'Pemko Padang'),
            'url' => env('SAML2_ORGANIZATION_URL', 'https://padang.go.id')
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Load default migrations
    |--------------------------------------------------------------------------
    |
    | This will allow you to disable or enable the default migrations of the package.
    |
    */
    'load_migrations' => true,
];
