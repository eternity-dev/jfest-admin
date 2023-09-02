<div class="card">
    <div
        class="d-flex align-items-center justify-content-between p-2">
        <div class="d-flex gap-3 align-items-center">
            <button
                class="btn btn-sm btn-light"
                data-bs-toggle="collapse"
                data-bs-target="{{ sprintf('#%s', $order->reference) }}">
                <i class="ri-arrow-down-s-line"></i>
            </button>
            <strong>#{{ $order->reference }}</strong>
            <span
                class="badge text-bg-{{ $statusClass }}">
                {{ str($status->value)->title() }}
            </span>
        </div>
        <div class="d-flex gap-1">
            @if (
                $order->status != App\Enums\OrderStatusEnum::Paid &&
                $order->status != App\Enums\OrderStatusEnum::Expired
            )
                <a href="{{ $url['mark_as_paid_url'] }}" class="btn btn-sm btn-success">
                    <i class="ri-bank-card-line pe-1"></i>
                    <span>Mark As Paid</span>
                </a>
            @endif
        </div>
    </div>
    <div
        class="collapse border-top bg-body-tertiary"
        id="{{ $order->reference }}">
        <div class="d-flex flex-column p-3">
            <div class="row gap-2">
                <div class="col row gap-4">
                    <div class="row gap-2">
                        <h6>General Information</h6>
                        <div class="row">
                            <span class="col-3">ID</span>
                            <span class="col-9">: {{ $order->reference }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">User Email</span>
                            <span class="col-9">: {{ $order->user->email }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">User Name</span>
                            <span class="col-9">: {{ $order->user->name }}</span>
                        </div>
                    </div>
                    <div class="row gap-2">
                        <h6>Payment Information</h6>
                        @if (!is_null($order->payment))
                            <div class="row">
                                <span class="col-3">Trx Status</span>
                                <span class="col-9">
                                    <span>: </span>
                                    {{ str($order->payment->status->value)->title() }}
                                </span>
                            </div>
                            <div class="row">
                                <span class="col-3">Trx ID</span>
                                <span class="col-9">: {{ $order->payment->transaction_id ?? '-' }}</span>
                            </div>
                            <div class="row">
                                <span class="col-3">Trx Link</span>
                                <span class="col-9">
                                    <span>: </span>
                                    <a href={{ $order->payment->link }}>Go to payment link</a>
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
                                <span class="col-9">: {{ $order->payment->method ?? '-' }}</span>
                            </div>
                        @else
                            <span>Order hasn&lsquo;t been checked out yet</span>
                        @endif
                    </div>
                </div>
                {{-- additional information --}}
                <div class="col-4 d-flex flex-column gap-4">
                    <div class="row gap-2">
                        <h6>Additional Information</h6>
                        <div class="row">
                            <span class="col-4 text-muted">Status</span>
                            <span
                                class="col-8 d-flex justify-content-end text-{{ $statusClass }}">
                                {{ str($status->value)->title() }}
                            </span>
                        </div>
                        <div class="row">
                            <span class="col-4 text-muted">Created at</span>
                            <span class="col-8 d-flex justify-content-end">
                                {{ $meta['date_of_created'] }}
                            </span>
                        </div>
                        <div class="row">
                            <span class="col-4 text-muted">Updated at</span>
                            <span class="col-8 d-flex justify-content-end">
                                {{ $meta['date_of_updated'] }}
                            </span>
                        </div>
                    </div>
                    <div class="row gap-2">
                        <h6>Items Information</h6>
                        <div class="row">
                            <span class="col-7 text-muted">Tickets Associated</span>
                            <span class="col-5 d-flex justify-content-end">
                                {{ $order->tickets->count() }}
                            </span>
                        </div>
                        <div class="row">
                            <span class="col-7 text-muted">Registrations Associated</span>
                            <span class="col-5 d-flex justify-content-end">
                                {{ $order->registrations->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
