<?php

namespace App\Providers;

use App\Listeners\LoginSuccessful;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        Login::class => [
            LoginSuccessful::class,
        ],

        // \Illuminate\Notifications\Events\NotificationFailed::class => [
        //     \App\Listeners\DeleteExpiredNotificationTokens::class,
        // ],
        
        // 'CodeGreenCreative\SamlIdp\Events\Assertion' => [
        //     'App\Listeners\SamlAssertionAttributes'
        // ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(\Slides\Saml2\Events\SignedIn::class, function (\Slides\Saml2\Events\SignedIn $event) {
            // $messageId = $event->getAuth()->getLastMessageId();

            // your own code preventing reuse of a $messageId to stop replay attacks
            $samlUser = $event->getSaml2User();

            $userData = [
                'id' => $samlUser->getUserId(),
                'attributes' => $samlUser->getAttributes(),
                'assertion' => $samlUser->getRawSamlAssertion()
            ];

            $attribute = $userData['attributes'];

            $user = User::where('username', $attribute['nip'])->first();

            // Login a user.
            Auth::login($user);
        });

        Event::listen('Slides\Saml2\Events\SignedOut', function (\Slides\Saml2\Events\SignedOut $event) {
            Log::info('logout');
            Auth::logout();
            Session::save();
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
