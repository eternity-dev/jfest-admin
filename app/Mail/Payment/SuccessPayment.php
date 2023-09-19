<?php

namespace App\Mail\Payment;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class SuccessPayment extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('%s - %s', $this->order->reference, 'Successful Payment'),
            metadata: ['order_id' => $this->order->reference]
        );
    }

    public function build()
    {
        return $this->view('emails.success-payment', [
            'order' => $this->order,
            'user' => $this->order->user,
            'formatter' => new \NumberFormatter('id_ID', \NumberFormatter::CURRENCY)
        ]);
    }

    public function headers(): Headers
    {
        return new Headers(
            messageId: $this->order->user->email,
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
