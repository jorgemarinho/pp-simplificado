<?php

namespace App\Listeners;

use App\Mail\Transfer as TransferMadeMail;
use Core\Wallet\Domain\Events\TransferEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;


class SendTransferEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    use InteractsWithQueue;

    public function handle(TransferEvent $event)
    {
        Mail::to($event->getPayload()['payee_user']['email'])->send(new TransferMadeMail($event));
    }
}
