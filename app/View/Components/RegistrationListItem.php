<?php

namespace App\View\Components;

use App\Models\Registration;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RegistrationListItem extends Component
{
    public array $meta;

    public array $url;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Registration $registration
    ) {
        $this->meta = [
            'date_of_created' => !is_null($registration->created_at) ? $registration->created_at->diffForHumans() : '-',
            'date_of_updated' => !is_null($registration->updated_at) ? $registration->updated_at->diffForHumans() : '-',
            'formatted_amount' => number_format($registration->price, 2, ',', '.'),
            'formatted_order_amount' => number_format($registration->order->total_price, 2, ',', '.'),
            'formatted_payment_amount' => number_format($registration->order->payment->amount, 2, ',', '.'),
            'formatted_payment_fee' => number_format($registration->order->payment->fee, 2, ',', '.'),
        ];

        $this->url = [
            'update_url' => '/',
            'remove_url' => '/',
            'mark_attended_url' => '/'
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.registration-list-item');
    }
}
