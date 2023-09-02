<div class="card">
    <div
        class="d-flex align-items-center justify-content-between p-2">
        <div class="d-flex gap-3 align-items-center">
            <button
                class="btn btn-sm btn-light"
                data-bs-toggle="collapse"
                data-bs-target="{{ sprintf('#%s', $ticket->code) }}">
                <i class="ri-arrow-down-s-line"></i>
            </button>
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
            @if (is_null($ticket->code))
                <a href="{{ $url['update_url'] }}" class="btn btn-sm btn-success">
                    <i class="ri-rfid-line pe-1"></i>
                    <span>Generate ID</span>
                </a>
            @endif
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
                        <div class="col">
                            <div class="row gap-2">
                                <h6>Order Information</h6>
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
                                    <span class="col-3">Order Total</span>
                                    <span class="col-9">: Rp {{ $meta['formatted_order_amount'] }}</span>
                                </div>
                            </div>
                        </div>
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
