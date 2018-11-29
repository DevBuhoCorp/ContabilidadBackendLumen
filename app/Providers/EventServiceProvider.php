<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ExampleEvent' => [
            'App\Listeners\ExampleListener',
        ],
        'item.created' => [

            'App\Events\CuentaContableEvent@itemCreated',

        ],

        'item.updated' => [

            'App\Events\CuentaContableEvent@itemUpdated',

        ],

        'item.deleted' => [

            'App\Events\CuentaContableEvent@itemDeleted',

        ]
    ];
}
