@extends('template.app')
@section('content')
    <header class="d-flex align-items-end justify-content-between">
        <section class="d-block">
            <h4 class="m-0 mb-1">Registrations</h4>
            <span class="text-muted">
                Hello there {{ $auth->name }}! The competitions seem legit til now!
            </span>
        </section>
        <section class="d-flex align-items-end gap-2">
            <button class="btn btn-dark" id="refresh-button">
                <i class="ri-refresh-line pe-1"></i>
                <span>Refresh</span>
            </button>
        </section>
    </header>
    <div class="row gap-3 py-3">
        <div class="col vstack gap-4">
            <form action="{{ route('dashboard.registrations.index') }}" class="d-flex gap-2">
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
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="ri-search-line pe-1"></i>
                    <span>Search</span>
                </button>
            </form>
            <div class="{{ $data['registrations']->isEmpty() ? 'border-0' : 'border-top' }}">
                @forelse ($data['registrations'] as $registration)
                    <div class="row py-3 border-bottom">
                        <div class="col-3 d-flex flex-column">
                            <span class="m-0">{{ $registration->user->email }}</span>
                            <small class="text-muted">Email</small>
                        </div>
                        <div class="col-2 d-flex flex-column">
                            <span class="m-0">{{ $registration->competition->name }}</span>
                            <small class="text-muted">Competition</small>
                        </div>
                        <div class="col-2 d-flex flex-column">
                            <span class="m-0">{{ $registration->team ? $registration->team->name : 'Individual' }}</span>
                            <small class="text-muted">Team Name</small>
                        </div>
                        <div class="col-2 d-flex flex-column">
                            <span class="m-0">{{ str($registration->order->status->value)->title() }}</span>
                            <small class="text-muted">Ord. Status</small>
                        </div>
                        <div class="col-2 d-flex flex-column">
                            <span class="m-0">{{
                                !is_null($registration->order->payment)
                                    ? str($registration->order->payment->status->value)->title()
                                    : '-'
                                }}</span>
                            <small class="text-muted">Trx. Status</small>
                        </div>
                        <div class="col-1 d-flex align-items-center justify-content-end gap-1">
                            <div class="dropdown dropstart">
                                <button
                                    class="btn btn-sm btn-dark"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ri-more-line"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @if (
                                        $registration->order->status != App\Enums\OrderStatusEnum::Paid &&
                                        $registration->order->status != App\Enums\OrderStatusEnum::Expired
                                    )
                                        <li>
                                            <form action="{{ route('dashboard.orders.update', [
                                                'order' => $registration->order,
                                                'state' => 'mark-as-paid'
                                            ]) }}" method="post">
                                                @csrf
                                                @method('put')
                                                <button
                                                    type="submit"
                                                    class="dropdown-item d-flex align-items-center gap-2">
                                                    Mark As Paid
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li class="dropdown-header">Details</li>
                                    @endif
                                    <li>
                                        <a
                                            href="{{ route('dashboard.orders.show', ['order' => $registration->order]) }}"
                                            class="dropdown-item d-flex align-items-center gap-2">
                                            Show Details
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            {{ $data['registrations']->links() }}
        </div>
    </div>
@endsection
@section('scripts') @vite('resources/js/app.js') @endsection
