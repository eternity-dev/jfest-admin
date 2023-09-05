@extends("template.auth")
@section("content")
    <div
        class="d-flex align-items-center justify-content-center"
        style="width: 100%; height: 100vh">
        <section class="d-flex flex-column gap-4" style="width: 369px">
            <header>
                <h3>Sign In</h3>
                <span>
                    Welcome back, organizer! How we address you? Please fill the form below.
                </span>
            </header>
            <form
                action="{{ route("auth.attempt.store", ['state' => $meta['current_step']]) }}"
                method="POST">
                @csrf
                @if ($meta['current_step'] == 'step-one')
                    <div class="mb-3">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                id="username"
                                name="username"
                                placeholder="Type your username..."
                                value="{{ old('username') }}"
                                autocomplete="off"
                                autofocus>
                            <label for="username">Username</label>
                        </div>
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                @elseif ($meta['current_step'] == 'step-two')
                    <div class="d-flex flex-column gap-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <span>Sign in as {{ $current_user->name }}</span>
                                <span>-</span>
                                <span class="text-muted">{{ $current_user->username }}@jfestbali.com</span>
                            </div>
                        </div>
                        <div>
                            <div class="form-floating">
                                <input type="hidden" name="username" value="{{ $current_user->username }}">
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Type your password..."
                                    autocomplete="off"
                                    autofocus>
                                <label for="username">Password</label>
                            </div>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                @endif
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary mt-2 btn-block">
                        {{ $meta['current_step'] == 'step-one' ? 'Continue' : 'Sign In' }}
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
