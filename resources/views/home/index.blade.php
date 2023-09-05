@extends('template.app')
@section('content')
    <header class="d-flex align-items-end justify-content-between">
        <section class="d-block">
            <h4 class="m-0 mb-1">Overview</h4>
            <span class="text-muted">
                Hello there {{ $user->name }}! Comes and check what happen while you&lsquo;re away
            </span>
        </section>
        <section class="d-flex align-items-end gap-2">
            <button class="btn btn-light" id="refresh-button">
                <i class="ri-refresh-line pe-1"></i>
                <span>Refresh</span>
            </button>
            <a href="" class="btn btn-primary">
                <i class="ri-coupon-line pe-1"></i>
                <span>Create Ticket</span>
            </a>
        </section>
    </header>
    <section class="row gap-4 py-3">
        <div class="col">
            <div class="d-flex align-items-center border-end">
                <div class="d-block">
                    <span class="text-muted">Your Total Revenue</span>
                    <div class="d-flex align-items-end gap-1">
                        @php $ex = 100000; @endphp
                        <h1 class="m-0">IDR {{ number_format($meta['total_revenue']['all'], 2, ',', '.') }}</h1>
                        <div
                            class="d-flex align-items-center gap-1 {{
                                $meta['total_revenue']['diff'] > 0 ? 'text-success' : null
                            }}">
                            @if ($meta['total_revenue']['diff'] > 0 )
                                <i class="ri-arrow-right-up-line ri-xl"></i>
                                <span style="line-height: 1.65">
                                    {{
                                        ($meta['total_revenue']['diff'] > 1000000
                                            ? round($meta['total_revenue']['diff'] / 1000000, 1) . 'M'
                                            : ($meta['total_revenue']['diff'] > 1000
                                                ? round($meta['total_revenue']['diff'] / 1000, 1) . 'k'
                                                : round($meta['total_revenue']['diff'], 1)))
                                    }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="d-flex align-items-center border-end">
                <div class="d-block">
                    <span class="text-muted">Total Tickets Sold</span>
                    <div class="d-flex align-items-end gap-1">
                        <h1 class="m-0">{{ $meta['total_tickets_sold']['all'] }}</h1>
                        <div
                            class="d-flex align-items-center gap-1 {{
                                $meta['total_tickets_sold']['diff'] > 0 ? 'text-success' : null
                            }}">
                            @if ($meta['total_tickets_sold']['diff'] > 0 )
                                <i class="ri-arrow-right-up-line ri-xl"></i>
                                <span style="line-height: 1.65">
                                    {{
                                        ($meta['total_tickets_sold']['diff'] > 1000000
                                            ? ($meta['total_tickets_sold']['diff'] / 1000000) . 'M'
                                            : ($meta['total_tickets_sold']['diff'] > 1000
                                                ? ($meta['total_tickets_sold']['diff'] / 1000) . 'k'
                                                : $meta['total_tickets_sold']['diff']))
                                    }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="d-flex align-items-center">
                <div class="d-block">
                    <span class="text-muted">Total Registrations Registered</span>
                    <div class="d-flex align-items-end gap-1">
                        @php $ex = 5 @endphp
                        <h1 class="m-0">5</h1>
                        <div
                            class="d-flex align-items-center gap-1 {{
                                $meta['total_registrations_sold']['diff'] > 0 ? 'text-success' : null
                            }}">
                            @if ($meta['total_registrations_sold']['diff'] > 0 )
                                <i class="ri-arrow-right-up-line ri-xl"></i>
                                <span style="line-height: 1.65">
                                    {{
                                        ($meta['total_registrations_sold']['diff'] > 1000000
                                            ? ($meta['total_registrations_sold']['diff'] / 1000000) . 'M'
                                            : ($meta['total_registrations_sold']['diff'] > 1000
                                                ? ($meta['total_registrations_sold']['diff'] / 1000) . 'k'
                                                : $meta['total_registrations_sold']['diff']))
                                    }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="row row-gap-4 col-gap-2">
        <div class="col-6 d-flex flex-column gap-2">
            <div class="text-muted">Tickets Sold / Day</div>
            <canvas
                id="chart-tickets-sold"
                style="width: 100%; height: 250px"
                data-tickets="{{ json_encode($data['chart']['tickets_sold']) }}"></canvas>
        </div>
        <div class="col-6 d-flex flex-column gap-2">
            <div class="text-muted">Total Revenue / Day</div>
            <canvas
                id="chart-total-revenue"
                style="width: 100%; height: 250px"
                data-revenue="{{ json_encode($data['chart']['total_revenue']) }}"></canvas>
        </div>
        <div class="col-5 d-flex flex-column gap-2">
            <div class="text-muted">Registrations Registered</div>
            <canvas
                id="chart-registrations"
                style="width: 100%; height: 200px"
                data-registrations="{{ json_encode($data['chart']['registrations_registered']) }}"></canvas>
        </div>
        <div class="col-2 d-flex flex-column gap-2">
            <div class="text-muted">Payment Method Used</div>
            <canvas
                id="chart-payments"
                style="width: 100%; height: 200px"
                data-payments="{{ json_encode($data['chart']['payments_method']) }}"></canvas>
        </div>
        <div class="col-5 d-flex flex-column gap-2">
            <div class="text-muted">New Users</div>
            <canvas
                id="chart-users"
                style="width: 100%; height: 200px"
                data-users="{{ json_encode($data['chart']['users']) }}"></canvas>
        </div>
    </section>
@endsection
@section('scripts') @vite('resources/js/app.js') @endsection
