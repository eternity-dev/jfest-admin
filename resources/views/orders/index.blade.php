@extends('template.app')
@section('content')
    <header class="d-flex align-items-end justify-content-between">
        <section class="d-block">
            <h4 class="m-0 mb-1">Orders</h4>
            <span class="text-muted">
                Hello there {{ $user->name }}! It looks like you have so many new orders had been placed
            </span>
        </section>
        <section class="d-flex align-items-end gap-2">
            <button class="btn btn-light" id="refresh-button">
                <i class="ri-refresh-line pe-1"></i>
                <span>Refresh</span>
            </button>
        </section>
    </header>
    <section class="row gap-3 py-3">
        <div class="col d-flex flex-column gap-4">
            <form action="{{ route('dashboard.orders.index') }}" class="d-flex gap-2">
                <div class="input-group">
                    <span id="label-search" class="input-group-text">By Anything</span>
                    <input
                        type="text"
                        class="form-control"
                        id="search"
                        name="search"
                        value="{{ $meta['search_query'] }}"
                        placeholder="Type the keywords here..."
                        autocomplete="off"
                        aria-label="Search"
                        aria-describedby="label-search">
                </div>
                <div class="dropdown">
                    <button
                        type="button"
                        class="btn btn-light dropdown-toggle d-flex align-items-center gap-1"
                        data-bs-toggle="dropdown">
                        <i class="ri-filter-line pe-1"></i>
                        <span>Filter</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a
                            href="{{ route('dashboard.orders.index', ['status' => 'paid']) }}"
                            class="dropdown-item">
                            Only with paid status
                        </a></li>
                        <li><a
                            href="{{ route('dashboard.orders.index', ['status' => 'pending']) }}"
                            class="dropdown-item">
                            Only with pending status
                        </a></li>
                        <li><a
                            href="{{ route('dashboard.orders.index', ['status' => 'expired']) }}"
                            class="dropdown-item">
                            Only with expired status
                        </a></li>
                    </ul>
                </div>
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="ri-search-line pe-1"></i>
                    <span>Search</span>
                </button>
            </form>
            <div class="{{ $data['orders']->isEmpty() ? 'border-0' : 'border-top' }}">
                @forelse ($data['orders'] as $order)
                    <div class="row py-3 border-bottom">
                        <div class="col-1 d-flex align-items-center">
                            <img
                                src="{{ $order->user->avatar }}"
                                alt="User's avatar"
                                width="35"
                                height="35"
                                class="img-fluid rounded-circle">
                        </div>
                        <div class="col-5 d-flex flex-column">
                            <span class="m-0">{{ $order->reference }}</span>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                        <div class="col-2 d-flex flex-column">
                            @if (!is_null($order->payment) || isset($order->payment))
                                <span>
                                    <span>Rp</span>
                                    <span class="m-0">{{
                                        number_format(
                                            $order->payment->amount + $order->payment->fee,
                                            2, ',', '.'
                                        )
                                    }}</span>
                                </span>
                            @else
                                <span>-</span>
                            @endif
                            <small class="text-muted">Price</small>
                        </div>
                        <div class="col-2 d-flex flex-column">
                            <span>{{ str($order->status->value)->title() }}</span>
                            <small class="text-muted">Status</small>
                        </div>
                        <div class="col-2 d-flex align-items-center justify-content-end gap-1">
                            <div class="dropdown dropstart">
                                <button
                                    class="btn btn-sm btn-light"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ri-more-line"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @if (
                                        $order->status != App\Enums\OrderStatusEnum::Paid &&
                                        $order->status != App\Enums\OrderStatusEnum::Expired
                                    )
                                        <li>
                                            <a
                                                href="{{ route('dashboard.orders.update', [
                                                    'order' => $order,
                                                    'state' => 'mark-as-paid'
                                                ]) }}"
                                                class="dropdown-item d-flex align-items-center gap-2">
                                                <i class="ri-check-double-line pt-1 pe-1 text-primary"></i>
                                                <span>Mark as paid</span>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li class="dropdown-header">Details</li>
                                    <li><a href="" class="dropdown-item">View tickets</a></li>
                                    <li><a href="" class="dropdown-item">View registrations</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="dropdown-header">Actions</li>
                                    <li><a href="" class="dropdown-item">Update</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="d-flex justify-content-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <h3>Oops!</h3>
                            <p class="text-muted">
                                It seems there are nothing here.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
            {{ $data['orders']->links() }}
        </div>
        <div class="col-3 d-flex flex-column gap-3">
            <h5>Summary</h5>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-money-dollar-circle-line text-warning" style="font-size: 30px"></i>
                    <div class="d-block">
                        <h4 class="m-0">{{ $meta['total_orders'] }}</h4>
                        <small class="text-muted">Total All Orders</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-money-dollar-circle-line text-success" style="font-size: 30px"></i>
                    <div class="d-block">
                        <h4 class="m-0">{{ $meta['total_paid_orders'] }}</h4>
                        <small class="text-muted">Total Paid Orders</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-loader-line text-secondary" style="font-size: 30px"></i>
                    <div class="d-block">
                        <h4 class="m-0">{{ $meta['total_pending_orders'] }}</h4>
                        <small class="text-muted">Total Pending Orders</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts') @vite('resources/js/app.js') @endsection
