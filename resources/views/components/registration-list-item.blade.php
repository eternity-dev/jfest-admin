<div class="card">
    <div
        class="d-flex align-items-center justify-content-between p-2">
        <div class="d-flex gap-3 align-items-center">
            <button
                class="btn btn-sm btn-light"
                data-bs-toggle="collapse"
                data-bs-target="{{ sprintf('#%s', $registration->uuid) }}">
                <i class="ri-arrow-down-s-line"></i>
            </button>
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
                <div class="col d-flex flex-column align-items-start gap-4">
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
                            </div>
                            @if (!isset($registration->team->members))
                                <div class="row gap-2">
                                    <h6>Participant Information</h6>
                                    <div class="row">
                                        <span class="col-4 text-muted">Email</span>
                                        <span class="col-8">
                                            : {{ $registration->email }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-4 text-muted">Name</span>
                                        <span class="col-8 d-flex">
                                            : {{ $registration->name }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-4 text-muted">Phone</span>
                                        <span class="col-8">
                                            : {{ $registration->phone }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @if (isset($registration->team->members))
                                <div class="row gap-2">
                                    <h6>Team Information</h6>
                                    <div class="row">
                                        <span class="col-4 text-muted">Team Name</span>
                                        <span class="col-8 text-muted">
                                            : {{ $registration->team->name }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-4 text-muted">Leader Name</span>
                                        <span class="col-8 text-muted">
                                            : {{ $registration->name }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-4 text-muted">Leader Email</span>
                                        <span class="col-8 text-muted">
                                            : {{ $registration->email }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span class="col-4 text-muted">Leader Phone</span>
                                        <span class="col-8 text-muted">
                                            : {{ $registration->phone }}
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
