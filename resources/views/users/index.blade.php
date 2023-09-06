@extends('template.app')
@section('content')
    <header class="d-flex align-items-end justify-content-between">
        <section class="d-block">
            <h4 class="m-0 mb-1">Users</h4>
            <span class="text-muted">
                Hello there {{ $auth->name }}! Here are the list of all the users registered
            </span>
        </section>
        <section class="d-flex align-items-end gap-2">
            <button class="btn btn-dark" id="refresh-button">
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
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="ri-search-line pe-1"></i>
                    <span>Search</span>
                </button>
            </form>
            <div class="{{ $data['users']->isEmpty() ? 'border-0' : 'border-top' }}">
                @forelse ($data['users'] as $user)
                    <div class="row py-3 border-bottom">
                        <div class="col-1 d-flex align-items-center">
                            <img
                                src="{{ $user->avatar }}"
                                alt="User's avatar"
                                width="35"
                                height="35"
                                class="img-fluid rounded-circle">
                        </div>
                        <div class="col-4 d-flex flex-column">
                            <span class="m-0">{{ str($user->name)->title() }}</span>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <div class="col-4 d-flex flex-column">
                            <span>{{ str($user->uuid)->substr(0, 13) }}...</span>
                            <small class="text-muted">UUID</small>
                        </div>
                        <div class="col-2 d-flex flex-column">
                            <span>{{ str($user->role->value)->title() }}</span>
                            <small class="text-muted">Role</small>
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
                                    <li><a
                                        href="{{ route('dashboard.orders.index', [
                                            'search' => $user->email
                                        ]) }}"
                                        class="dropdown-item">Search related orders</a></li>
                                    <li><a href="" class="dropdown-item">Search related tickets</a></li>
                                    <li><a href="" class="dropdown-item">Search related registrations</a></li>
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
            {{ $data['users']->links() }}
        </div>
        <div class="col-3 d-flex flex-column gap-3">
            <h5>Summary</h5>
            <div class="d-flex flex-column gap-4">
                <canvas
                    id="chart-users"
                    style="width: 100%; height: 200px"
                    data-users="{{ json_encode($meta['chart']['users']) }}"></canvas>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-user-line text-primary" style="font-size: 30px"></i>
                    <div class="d-block">
                        <h4 class="m-0">{{ $meta['total_users'] }}</h4>
                        <small class="text-muted">Total Users</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-admin-line text-info" style="font-size: 30px"></i>
                    <div class="d-block">
                        <h4 class="m-0">{{ $meta['total_admin_users'] }}</h4>
                        <small class="text-muted">Total Admins</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts') @vite('resources/js/app.js') @endsection
