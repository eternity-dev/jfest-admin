@extends('template.app')
@section('content')
    <header class="d-flex align-items-end justify-content-between">
        <section class="d-block">
            <h4 class="m-0 mb-1">Exports Data</h4>
            <span class="text-muted">
                Hello there {{ $auth->name }}! What you are trying to export right now? It is registrations data or tickets data?
            </span>
        </section>
    </header>
    <main class="vstack gap-4">
        <form
            action="{{ route('dashboard.exports.store.registrations') }}"
            method="post"
            class="row">
            @csrf
            <div class="col-3">
                <h6>Registrations</h6>
                <span class="text-muted">
                    Export all registrations data or just a bit of it, choose what you want to export!
                </span>
            </div>
            <div class="col">
                <div class="vstack mb-3">
                    <span class="form-label">Select competition</span>
                    <select
                        class="form-select"
                        id="competition"
                        name="competition"
                        @if ($data['competitions']->isEmpty()) disabled @endif>
                        @forelse ($data['competitions'] as $competition)
                            <option value="{{ $competition->id }}">
                                {{ $competition->name }}
                            </option>
                        @empty
                            <option>--</option>
                        @endforelse
                    </select>
                    @error ('competition') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="vstack gap-2 mb-3">
                    <div class="vstack">
                        <span>Fields to be displayed</span>
                        <span class="form-text">Choose what fields to be displayed on the exported file</span>
                    </div>
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="include_email"
                            id="include-email"
                            checked>
                        <label for="include-email" class="form-check-label">Include email field</label>
                    </div>
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="include_name"
                            id="include-name"
                            checked>
                        <label for="include-name" class="form-check-label">Include name field</label>
                    </div>
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="include_phone"
                            id="include-phone"
                            checked>
                        <label for="include-phone" class="form-check-label">Include phone field</label>
                    </div>
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="include_instagram"
                            id="include-instagram">
                        <label for="include-instagram" class="form-check-label">Include instagram field</label>
                    </div>
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="include_nickname"
                            id="include-nickname">
                        <label for="include-nickname" class="form-check-label">Include nickname field</label>
                    </div>
                    @error ('include_email') <small class="text-danger">{{ $message }}</small> @enderror
                    @error ('include_name') <small class="text-danger">{{ $message }}</small> @enderror
                    @error ('include_phone') <small class="text-danger">{{ $message }}</small> @enderror
                    @error ('include_instagram') <small class="text-danger">{{ $message }}</small> @enderror
                    @error ('include_nickname') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="row">
                    <div class="col">
                        <label for="from-date" class="form-label">From</label>
                        <input
                            type="date"
                            class="form-control"
                            id="from-date"
                            name="from"
                            value="{{ old('from') ?? now()->subDays(7)->toDateString() }}">
                        @error ('from') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col">
                        <label for="to-date" class="form-label">To</label>
                        <input
                            type="date"
                            class="form-control"
                            id="to-date"
                            name="to"
                            value="{{ old('to') ?? now()->toDateString() }}">
                        @error ('to') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
            <div class="col-3">
                <button
                    type="submit"
                    class="btn btn-success"
                    @if ($data['competitions']->isEmpty()) disabled @endif>
                    <i class="ri-file-excel-line pe-1"></i>
                    <span>Export To Excel</span>
                </button>
            </div>
        </form>
    </main>
@endsection
