<?php

namespace App\Listeners;

use App\Events\OrderShipped;

class UserLoggedOut
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderShipped  $event
     * @return void
     */
    public function handle($event)
    {
        // Access the order using $event->order...
        \PragmaRX\Google2FALaravel\Facade::logout();
    }
}