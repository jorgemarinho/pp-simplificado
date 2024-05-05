<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Address;
use Core\Wallet\Domain\Events\TransferEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Transfer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private TransferEvent $transferEvent)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->transferEvent->getPayload()['payee_user']['email'], $this->transferEvent->getPayload()['payee_user']['name']),
            subject: 'Transfer made in the amount of from ' . $this->transferEvent->getPayload()['payee_user']['value'] . ' to ' . $this->transferEvent->getPayload()['payer_user']['name'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.wallet.transfer',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
