<?php

namespace App\Events;

use App\Models\Cuentacontable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CuentaContableEvent extends Event
{
    use SerializesModels;

    public $cuenta;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Cuentacontable $cuenta)
    {
        $this->cuenta = $cuenta;
//        Log::info("Item Created Event Fire: " . $cuenta);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function itemCreated(Cuentacontable $item)
    {
        Log::info("Item Created Event Fire: " . $item);

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function itemUpdated(Cuentacontable $item)
    {
//        Log::info("Item Updated Event Fire: " . $item);
//        Log::info("Item Updated Old Event Fire: " . json_encode($item->getOriginal()) );
        if( $item->IDPadre ){
            $old = $item->getOriginal();
            $row = Cuentacontable::find( $item->IDPadre );
            $row->update([ 'Saldo'=> $row->Saldo - ( $item->Saldo - $old["Saldo"] ) ]);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function itemDeleted(Cuentacontable $item)
    {
        Log::info("Item Deleted Event Fire: " . $item);
    }
}
