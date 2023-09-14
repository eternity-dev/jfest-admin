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
                        <option value="all">All</option>
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
