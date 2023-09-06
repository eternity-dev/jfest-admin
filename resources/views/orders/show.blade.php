@extends('template.app')
@section('content')
    <header class="d-flex align-items-end justify-content-between">
        <section class="d-block">

            <h4 class="m-0 mb-1">Order #{{ $data['order']->reference }}</h4>
            <span class="text-muted">
                Hello there {{ $auth->name }}! Here are some information for order made by
                {{ $data['order']->user->name }}
            </span>
        </section>
        <section class="d-flex align-items-end gap-2">
            <a href="{{ route('dashboard.orders.index') }}" class="btn btn-dark">
                <i class="ri-arrow-left-line pe-1"></i>
                <span>Back</span>
            </a>
            @if ($data['order']->status != App\Enums\OrderStatusEnum::Paid)
                <form action="{{ route('dashboard.orders.update', [
                    'order' => $data['order'],
                    'state' => 'mark-as-paid'
                ])}}" method="post">
                    @csrf
                    @method('put')
                    <button
                        type="submit"
                        class="btn btn-primary">
                        <i class="ri-check-double-line"></i>
                        <span>Mark As Paid</span>
                    </button>
                </form>
            @endif
            <button type="button" class="btn btn-dark" id="refresh-button">
                <i class="ri-refresh-line pe-1"></i>
                <span>Refresh</span>
            </button>
        </section>
    </header>
    <main class="vstack gap-3">
        <section class="row">
            <div class="col row row-gap-5">
                <div class="col-5 vstack">
                    <h6>General Information</h6>
                    <span class="text-muted">
                        Set of informations about the order general information.
                        <br><br>
                        Some notes:
                        <ul class="pt-1">
                            <li>Please note, don&lsquo;t click the save button unless it needed!</li>
                            <li>Click cancel to reset the form as it was</li>
                        </ul>
                    </span>
                </div>
                <div class="col-7">
                    <form action="{{ route('dashboard.orders.update', [
                        'order' => $data['order']
                    ]) }}" method="post" class="vstacks">
                        @csrf
                        @method('put')
                        <div class="vstack mb-3">
                            <span class="form-label">Order Reference</span>
                            <div class="input-group">
                                <span class="input-group-text" id="label-reference">#</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="order[reference]"
                                    aria-label="Reference"
                                    aria-describedby="label-reference"
                                    value="{{ $data['order']->reference }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="vstack mb-3">
                            <span class="form-label">Total Price</span>
                            <div class="input-group">
                                <span class="input-group-text" id="label-total_price">IDR</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="order[total_price]"
                                    aria-label="Total Price"
                                    aria-describedby="label-total_price"
                                    value="{{ $data['order']->total_price }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="order_status" class="form-label">
                                Order Status
                            </label>
                            <select
                                class="form-select w-50"
                                id="order_status"
                                name="order[status]">
                                <option
                                    value="{{ $data['order']->status->value }}"
                                    selected>
                                    {{ str($data['order']->status->value)->title() }}
                                </option>
                                @foreach ([
                                    App\Enums\OrderStatusEnum::Expired,
                                    App\Enums\OrderStatusEnum::Paid,
                                    App\Enums\OrderStatusEnum::Pending,
                                ] as $status)
                                    @if ($status != $data['order']->status)
                                        <option
                                            value="{{ $status->value }}">
                                            {{ str($status->value)->title() }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="form-text">
                                Please choose or update order status carefully, because it will affect the entire order display.
                            </span>
                        </div>
                        <div class="hstack gap-2 pt-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="reset" class="btn btn-dark">Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="col-5 vstack">
                    <h6>Payment Information</h6>
                    <span class="text-muted">
                        Set of informations about the order's payment information.
                        <br><br>
                        Some notes:
                        <ul class="pt-1">
                            <li>If some fields are empty, it means the payment was not being processed yet</li>
                        </ul>
                    </span>
                </div>
                <div class="col-7">
                    @if (!is_null($data['order']->payment) && isset($data['order']->payment))
                        <div class="vstack">
                            <div class="vstack mb-3">
                                <span class="form-label">Transaction ID</span>
                                <div class="input-group">
                                    <span class="input-group-text" id="label-trx_id">#</span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="payment[transaction_id]"
                                        aria-label="Trx ID"
                                        aria-describedby="label-trx_id"
                                        value="{{ $data['order']->payment->transaction_id }}"
                                        disabled>
                                </div>
                                @if (is_null($data['order']->payment->transaction_id))
                                    <span class="form-text">
                                        It seems that this order is not processed yet by Midtrans
                                    </span>
                                @endif
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="vstack">
                                        <span class="form-label">Amount</span>
                                        <div class="input-group">
                                            <span class="input-group-text" id="label-amount">IDR</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="payment[amount]"
                                                aria-label="Amount"
                                                aria-describedby="label-amount"
                                                value="{{ $data['order']->payment->amount }}"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="vstack">
                                        <span class="form-label">Fee</span>
                                        <div class="input-group">
                                            <span class="input-group-text" id="label-fee">IDR</span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="payment[fee]"
                                                aria-label="Fee"
                                                aria-describedby="label-fee"
                                                value="{{ $data['order']->payment->fee }}"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-8">
                                    <div class="vstack">
                                        <span class="form-label">Payment Method</span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="payment[method]"
                                            aria-label="Method"
                                            aria-describedby="label-method"
                                            value="{{ str($data['order']->payment->method ?? '-')->title() }}"
                                            disabled>
                                        @if (is_null($data['order']->payment->method))
                                            <span class="form-text">
                                                No method provided, because the payment is not being processed yet
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="vstack">
                                        <span class="form-label">Payment Status</span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="payment[status]"
                                            aria-label="Payment Status"
                                            aria-describedby="label-payment_status"
                                            value="{{
                                                str($data['order']->payment->status->value)->title()
                                            }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-dark">
                            There are no payment information. It could be this order is not being processed by the user yet.
                        </div>
                    @endif
                </div>
                <div class="col-5 vstack">
                    <h6>User Information</h6>
                    <span class="text-muted">
                        Set of informations about the user. Some of the informations maybe private, so be aware when you look into this kind of information!
                    </span>
                </div>
                <div class="col-7">
                    <div class="vstack mb-3">
                        <span class="form-label">Unique ID</span>
                        <div class="input-group">
                            <span class="input-group-text" id="label-uuid">#</span>
                            <input
                                type="text"
                                class="form-control"
                                name="user[uuid]"
                                aria-label="UUID"
                                aria-describedby="label-uuid"
                                value="{{ $data['order']->user->uuid }}"
                                disabled>
                        </div>
                    </div>
                    <div class="vstack mb-3">
                        <span class="form-label">Email</span>
                        <input
                            type="text"
                            class="form-control"
                            name="user[email]"
                            aria-label="Email"
                            aria-describedby="label-email"
                            value="{{ $data['order']->user->email }}"
                            disabled>
                    </div>
                    <div class="vstack mb-3">
                        <span class="form-label">Name</span>
                        <input
                            type="text"
                            class="form-control"
                            name="user[name]"
                            aria-label="Name"
                            aria-describedby="label-name"
                            value="{{ $data['order']->user->name }}"
                            disabled>
                    </div>
                </div>
                <div class="col-5 vstack">
                    <h6>Tickets Information</h6>
                    <span class="text-muted">
                        Set of tickets information related to this order. Please note we can&lsquo;t change any information about the tickets
                    </span>
                </div>
                <div class="col-7">
                    <div class="row">
                        @if ($data['order']->tickets->isNotEmpty())
                            <div class="col">
                                <ul class="list-group mb-3">
                                    @foreach ($data['order']->tickets as $ticket)
                                        <li class="list-group-item vstack">
                                            <span>{{ $ticket->code ?? '-' }}</span>
                                            <span>{{ str($ticket->attended_status->value)->title() }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($data['order']->status == App\Enums\OrderStatusEnum::Pending)
                                    <div class="alert alert-info">
                                        This order is not paid yet, so the tickets information are not being displayed on user&lsquo;s side.
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="col-4">
                            <a
                                href=""
                                class="btn btn-primary">
                                <i class="ri-coupon-line pe-1"></i>
                                <span>Create Ticket</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-5 vstack">
                    <h6>Registrations Information</h6>
                    <span class="text-muted">
                        Set of registrations information related to this order. Please note we can&lsquo;t change any information about the registrations
                    </span>
                </div>
                <div class="col-7">
                    <div class="row">
                        @if ($data['order']->registrations->isNotEmpty())
                            <div class="col">
                                <ul class="list-group mb-3">
                                    @foreach ($data['order']->registrations as $registration)
                                        <li class="list-group-item vstack">
                                            <span class="d-block pb-2">{{ $registration->uuid ?? '-' }}</span>
                                            <span class="row">
                                                <span class="col-5">Competition Name</span>
                                                <span class="col">: {{ $registration->competition->name ?? '-' }}</span>
                                            </span>
                                            <span class="row">
                                                <span class="col-5">Participant Name</span>
                                                <span class="col">: {{ $registration->name ?? '-' }}</span>
                                            </span>
                                            <span class="row">
                                                <span class="col-5">Participant Phone</span>
                                                <span class="col">: {{ $registration->phone ?? '-' }}</span>
                                            </span>
                                            <span class="row">
                                                <span class="col-5">Participant IG</span>
                                                <span class="col">: {{ $registration->instagram ?? '-' }}</span>
                                            </span>
                                            <span class="row">
                                                <span class="col-5">Participant Nickname</span>
                                                <span class="col">: {{ $registration->nickname ?? '-' }}</span>
                                            </span>
                                            @if (!is_null($registration->team) && $registration->team->number_of_members > 0)
                                                <span>Registered Members</span>
                                                <ul class="px-3">
                                                    @foreach ($registration->team->members as $member)
                                                        <li>{{ $member->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($data['order']->status == App\Enums\OrderStatusEnum::Pending)
                                    <div class="alert alert-info">
                                        This order is not paid yet, so the registrations information are not being displayed on user&lsquo;s side.
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="col">
                                <div class="alert alert-dark">
                                    It seems there are no registrations data related to this order yet.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="vstack gap-4">
                    <div class="vstack">
                        <h6>Additional Information</h6>
                        <div class="row">
                            <span class="col-5">Created At</span>
                            <span class="col">
                                : {{ $data['order']->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="row">
                            <span class="col-5">Updated At</span>
                            <span class="col">
                                : {{ $data['order']->updated_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="row">
                            <span class="col-5">Expired At</span>
                            <span class="col">
                                : {{ $data['order']->expired_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            type="button"
                            class="btn btn-success dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ri-download-line pe-1"></i>
                            <span>Export</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#!" class="dropdown-item">Export Tickets To Excel</a></li>
                            <li><a href="#!" class="dropdown-item">Export Registrations To Excel</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
