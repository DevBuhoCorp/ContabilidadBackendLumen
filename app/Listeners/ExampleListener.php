<?php

namespace App\Listeners;

use App\Events\CuentaContableEvent;
use App\Events\ExampleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExampleListener
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
     * @param  CuentaContableEvent  $event
     * @return void
     */
    public function handle(CuentaContableEvent $event)
    {
        //
    }
}
