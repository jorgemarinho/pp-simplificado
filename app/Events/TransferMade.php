<?php

namespace App\Events;

use Core\Wallet\Domain\Events\TransferEvent;
use Core\Wallet\Interfaces\TransferEventManagerInterface;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferMade implements TransferEventManagerInterface
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function dispatch(object $event): void
    {
        event($event);
    }
}
