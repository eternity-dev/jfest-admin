@extends('template.app')
@section('content')
    <header class="d-flex align-items-end justify-content-between">
        <section class="d-block">
            <h4 class="m-0 mb-1">Create New Ticket</h4>
            <span class="text-muted">
                Hello there {{ $auth->name }}! It looks like you want to create a new ticket
            </span>
        </section>
    </header>
    <section class="row gap-3 py-3">
        <div class="col">
            <form action="{{ route('dashboard.tickets.store') }}" method="post" >
                @csrf
                <div class="row mb-3">
                    <label for="activity" class="col-4 col-form-label">Activity Name</label>
                    <div class="col">
                        <select
                            class="form-select"
                            id="activity"
                            name="activity">
                            @foreach ($data['activities'] as $activity)
                                <option value="{{ $activity->name }}">{{ $activity->name }}</option>
                            @endforeach
                        </select>
                        @error ('activity') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="uuid" class="col-4 col-form-label">UUID</label>
                    <div class="col">
                        <input
                            type="text"
                            class="form-control"
                            id="uuid"
                            name="uuid"
                            value="{{ str()->uuid() }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="user" class="col-4 col-form-label">User UUID</label>
                    <div class="col d-flex flex-column gap-1">
                        <input
                            type="text"
                            class="form-control"
                            id="user"
                            name="user"
                            placeholder="Type the email..."
                            list="users-list"
                            value="{{
                                !is_null($data['user'])
                                    ? $data['user']->uuid
                                    : ''
                            }}">
                        <datalist id="users-list">
                            @foreach ($data['users'] as $user)
                                <option value="{{ $user->uuid }}">
                                    {{ $user->email }}
                                </option>
                            @endforeach
                        </datalist>
                        @error ('user') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="price" class="col-4 col-form-label">Price</label>
                    <div class="col d-flex flex-column gap-1">
                        <select
                            type="text"
                            class="form-select"
                            id="price"
                            name="price">
                            @foreach ($data['sales'] as $sale)
                                <option value="{{ $sale->id }}">
                                    {{ $sale->name }}
                                </option>
                            @endforeach
                        </select>
                        @error ('price') <small class="text-danger">{{ $message }}</small> @enderror
                        <span class="form-text">Please select the price according to the sale correctly because its will be related to how much the user&lsquo;s pay</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-4 col-form-label">Amount of Tickets</label>
                    <div class="col">
                        <input
                            type="number"
                            class="form-control w-25"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') ?? 1 }}"
                            min="1"
                            max="10">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-4"></div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-4"></div>
    </section>
@endsection
