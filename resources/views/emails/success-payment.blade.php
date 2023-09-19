<div style="display: block; width: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">
    <header style="display: block; width: inherit; text-align: center">
        <div style="display: block; width: 75px; margin: 0 auto">
            <img
                src="https://bucket.jfestbali.com/images/logo.png"
                style="width: 100%; object-fit: contain; object-position: center; margin-left: -15px; margin-bottom: -10px;" />
        </div>
        <h2>JFest Bali 7</h2>
        <span style="display: block; margin-top: -5px">Exclusive event by JCOS (Japanese Community of STIKOM Bali)</span>
        <div>
            <a href="mailto:info@jfestbali.com">info@jfestbali.com</a>
            <span>|</span>
            <a href="https://jfestbali.com">https://jfestbali.com</a>
        </div>
    </header>
    <main style="display: block; margin-top: 25px">
        <p>
            Dear {{ $user->name }},
            <br /><br />
            We are excited to inform you that your payment for order <strong>#{{ $order->reference }}</strong> has been successfully processed. Thank you for placing order on JFest Bali 7.
            <br />
            Here are the details of your order:
            <br />
            <div>Order Reference: {{ $order->reference }}</div>
            <div>Order Date: {{ $order->created_at }}</div>
            <div>Payment Amount: {{ $formatter->formatCurrency($order->payment->amount, 'IDR') }}</div>
            <div>Payment Fee: {{ $formatter->formatCurrency($order->payment->fee, 'IDR') }}</div>
            <div>Payment Method: {{ Illuminate\Support\Str::title($order->payment->method) }}</div>
            <br />
            Items ordered:
            <br />
            <ul>
                @foreach ($order->tickets as $ticket)
                    <li>{{ $ticket->activity->name }} - {{ $formatter->formatCurrency($ticket->price, 'IDR') }}</li>
                @endforeach
                @foreach ($order->registrations as $registration)
                    <li>{{ $registration->competition->name }} - {{ $formatter->formatCurrency($registration->price, 'IDR') }}</li>
                @endforeach
            </ul>
            <br />
            Please remember to bring your ticket QR code picture with you to the event. This will be used to expedite your check-in process and ensure a smooth entry.
            <br />
            <br />
            If you have any questions or need assistance, please don't hesitate to reach out to our team at
            <a href="mailto:info@jfestbali.com">info@jfestbali.com</a>.
            <br />
            Thank you again for choosing us for your entertainment needs. We&quot;re committed to making your experience memorable. We look forward to seeing you at the event!
        </p>
    </main>
    <footer style="display: block; margin-top: 30px">
        <div>Best regards,</div>
        <div>JFest Bali 7 Team</div>
    </footer>
</div>
