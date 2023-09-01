<div class="card">
    <div
        class="d-flex align-items-center justify-content-between p-2 ps-3"
        data-bs-toggle="collapse"
        data-bs-target="{{ sprintf('#%s', $ticket->code) }}">
        <div class="d-flex gap-3 align-items-center">
            <strong>#{{ $ticket->code }}</strong>
            <span
                class="badge {{ $ticket->attended_status == App\Enums\AttendStatusEnum::Attended
                    ? 'text-bg-success'
                    : 'text-bg-danger'
                }}">
                {{ $ticket->attended_status->value }}
            </span>
        </div>
        <div class="d-flex gap-1">
            <a href="{{ $url['update_url'] }}" class="btn btn-sm btn-light">
                <i class="ri-edit-line"></i>
            </a>
            <a href="{{ $url['remove_url'] }}" class="btn btn-sm btn-danger">
                <i class="ri-delete-bin-line"></i>
            </a>
            @if ($ticket->attended_status == App\Enums\AttendStatusEnum::NotAttended)
                <a href="{{ $url['mark_attended_url'] }}" class="btn btn-sm btn-primary">
                    <i class="ri-check-double-line pe-1"></i>
                    <span>Mark as Attended</span>
                </a>
            @endif
        </div>
    </div>
    <div
        class="collapse border-top bg-body-tertiary"
        id="{{ $ticket->code }}">
        <div class="d-flex flex-column p-3">
            <div class="row gap-2">
                <div class="col row gap-4">
                    <div class="row gap-2">
                        <h6>General Information</h6>
                        <div class="row">
                            <span class="col-3">ID</span>
                            <span class="col-9">: {{ $ticket->uuid }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">Order ID</span>
                            <span class="col-9">: {{ $ticket->order->reference }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">Order Status</span>
                            <span class="col-9">: {{
                                Illuminate\Support\Str::studly($ticket->order->status->value)
                            }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">Order Total Price</span>
                            <span class="col-9">: Rp {{ $meta['formatted_order_amount'] }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">Activity</span>
                            <span class="col-9">: {{ $ticket->activity->name }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">User Email</span>
                            <span class="col-9">: {{ $ticket->user->email }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">User Name</span>
                            <span class="col-9">: {{ $ticket->user->name }}</span>
                        </div>
                    </div>
                    <div class="row gap-2">
                        <h6>Payment Information</h6>
                        @if (!is_null($ticket->order->payment))
                            <div class="row">
                                <span class="col-3">Trx Status</span>
                                <span class="col-9">
                                    <span>: </span>
                                    {{ Illuminate\Support\Str::studly($ticket->order->payment->status->value) }}
                                </span>
                            </div>
                            <div class="row">
                                <span class="col-3">Trx ID</span>
                                <span class="col-9">: {{ $ticket->order->payment->transaction_id }}</span>
                            </div>
                            <div class="row">
                                <span class="col-3">Trx Link</span>
                                <span class="col-9">
                                    <span>: </span>
                                    <a href={{ $ticket->order->payment->link }}>Go to payment link</a>
                                </span>
                            </div>
                            <div class="row">
                                <span class="col-3">Amount</span>
                                <span class="col-9">: Rp {{ $meta['formatted_payment_amount'] }}</span>
                            </div>
                            <div class="row">
                                <span class="col-3">Fee</span>
                                <span class="col-9">: Rp {{ $meta['formatted_payment_fee'] }}</span>
                            </div>
                            <div class="row">
                                <span class="col-3">Payment Method</span>
                                <span class="col-9">: {{ $ticket->order->payment->method }}</span>
                            </div>
                        @else
                            <span>Ticket not paid</span>
                        @endif
                    </div>
                </div>
                {{-- additional information --}}
                <div class="col-4 d-flex flex-column gap-2">
                    <h6>Additional Information</h6>
                    <div class="row">
                        <span class="col-6 text-muted">Status</span>
                        <span
                            class="col-6 d-flex justify-content-end {{
                                $ticket->attended_status == App\Enums\AttendStatusEnum::Attended
                                    ? 'text-success'
                                    : 'text-danger'
                            }}">
                            {{ str($ticket->attended_status->value)->title() }}
                        </span>
                    </div>
                    <div class="row">
                        <span class="col-6 text-muted">Attended at</span>
                        <span class="col-6 d-flex justify-content-end">
                            {{ $meta['date_of_attended'] }}
                        </span>
                    </div>
                    <div class="row">
                        <span class="col-6 text-muted">Created at</span>
                        <span class="col-6 d-flex justify-content-end">
                            {{ $meta['date_of_created'] }}
                        </span>
                    </div>
                    <div class="row">
                        <span class="col-6 text-muted">Updated at</span>
                        <span class="col-6 d-flex justify-content-end">
                            {{ $meta['date_of_updated'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
