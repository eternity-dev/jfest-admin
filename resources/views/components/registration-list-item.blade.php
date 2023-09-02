<div class="card">
    <div
        class="d-flex align-items-center justify-content-between p-2 ps-3"
        data-bs-toggle="collapse"
        data-bs-target="{{ sprintf('#%s', $registration->uuid) }}">
        <div class="d-flex gap-3 align-items-center">
            <strong>#{{ $registration->uuid }}</strong>
        </div>
        <div class="d-flex gap-1">
            <a href="{{ $url['update_url'] }}" class="btn btn-sm btn-light">
                <i class="ri-edit-line"></i>
            </a>
            <a href="{{ $url['remove_url'] }}" class="btn btn-sm btn-danger">
                <i class="ri-delete-bin-line"></i>
            </a>
        </div>
    </div>
    <div
        class="collapse border-top bg-body-tertiary"
        id="{{ $registration->uuid }}">
        <div class="d-flex flex-column p-3">
            <div class="row gap-2">
                <div class="col row gap-4">
                    <div class="row gap-2">
                        <h6>General Information</h6>
                        <div class="row">
                            <span class="col-3">ID</span>
                            <span class="col-9">: {{ $registration->uuid }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">Price</span>
                            <span class="col-9">: Rp {{ $meta['formatted_amount'] }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">Competition</span>
                            <span class="col-9">: {{ $registration->competition->name }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">User Email</span>
                            <span class="col-9">: {{ $registration->user->email }}</span>
                        </div>
                        <div class="row">
                            <span class="col-3">User Name</span>
                            <span class="col-9">: {{ $registration->user->name }}</span>
                        </div>
                    </div>
                    <div class="row gap-2">
                        <div class="col">
                            <div class="row gap-2">
                                <h6>Order Information</h6>
                                <div class="row">
                                    <span class="col-3">Order ID</span>
                                    <span class="col-9">: {{ $registration->order->reference }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-3">Order Status</span>
                                    <span class="col-9">: {{
                                        Illuminate\Support\Str::studly($registration->order->status->value)
                                    }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-3">Order Total</span>
                                    <span class="col-9">: Rp {{ $meta['formatted_order_amount'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gap-2">
                        <h6>Payment Information</h6>
                        @if (!is_null($registration->order->payment))
                            <div class="row">
                                <span class="col-3">Trx Status</span>
                                <span class="col-9">
                                    <span>: </span>
                                    {{ Illuminate\Support\Str::studly($registration->order->payment->status->value) }}
                                </span>
                            </div>
                            <div class="row">
                                <span class="col-3">Trx ID</span>
                                <span class="col-9">: {{ $registration->order->payment->transaction_id }}</span>
                            </div>
                            <div class="row">
                                <span class="col-3">Trx Link</span>
                                <span class="col-9">
                                    <span>: </span>
                                    <a href={{ $registration->order->payment->link }}>Go to payment link</a>
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
                                <span class="col-3">Method</span>
                                <span class="col-9">: {{ $registration->order->payment->method }}</span>
                            </div>
                        @else
                            <span>Ticket not paid</span>
                        @endif
                    </div>
                </div>
                {{-- additional information --}}
                <div class="col-6 d-flex flex-column p-3">
                    <div class="row gap-2">
                        <div class="col row gap-4">
                            <div class="row gap-2">
                                <h6>Additional Information</h6>
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
                                @if (!isset($registration->team->members))
                                    <div class="row">
                                        <span class="col-6 text-muted">Email</span>
                                        <span class="col-6 d-flex justify-content-end">
                                            {{ $registration->email }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-6 text-muted">Name</span>
                                        <span class="col-6 d-flex justify-content-end">
                                            {{ $registration->name }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-6 text-muted">Phone</span>
                                        <span class="col-6 d-flex justify-content-end">
                                            {{ $registration->phone }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            @if (isset($registration->team->members))
                                <div class="row gap-2">
                                    <h6>Team Information</h6>
                                    <div class="row">
                                        <span class="col-6 text-muted">Team Name</span>
                                        <span class="col-6 text-muted">
                                            {{ $registration->team->name }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-6 text-muted">Leader Name</span>
                                        <span class="col-6 text-muted">
                                            {{ $registration->name }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-6 text-muted">Leader Email</span>
                                        <span class="col-6 text-muted">
                                            {{ $registration->email }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-6 text-muted">Leader Phone</span>
                                        <span class="col-6 text-muted">
                                            {{ $registration->phone }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row gap-2">
                                    <h6>Team Members Information</h6>
                                    <ul class="list-group ms-2">
                                        @foreach ($registration->team->members as $member)
                                            <li class="list-group-item d-flex flex-column gap-1">
                                                <div class="row">
                                                    <span class="col-3 text-muted">Name</span>
                                                    <span class="col-9 text-muted">
                                                        : {{ $member->name }}
                                                    </span>
                                                </div>
                                                <div class="row">
                                                    <span class="col-3 text-muted">Email</span>
                                                    <span class="col-9 text-muted">
                                                        : {{ $member->instagram ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="row">
                                                    <span class="col-3 text-muted">Phone</span>
                                                    <span class="col-9 text-muted">
                                                        : {{ $member->nickname ?? '-' }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
