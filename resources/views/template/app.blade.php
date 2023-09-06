<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ sprintf("%s - %s", env("APP_NAME"), isset($title) ? $title : "Home") }}</title>

        @yield("styles")
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
            crossorigin="anonymous">
        <link
            href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
            rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a href="{{ route('dashboard.home.index') }}" class="navbar-brand">
                        <img
                            src="https://bucket.jfestbali.com/images/logo.png"
                            alt="JFest logo"
                            width="40"
                            height="40">
                    </a>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbar-main"
                        aria-controls="navbar-main"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar-main">
                        <ul class="navbar-nav ms-2 me-auto mb-2 mb-lg-0">
                            @foreach ($navbar_url as $item)
                                <li class="nav-item">
                                    <a
                                        href="{{ $item['href'] }}"
                                        class="nav-link {{ request()->url() == $item['href']
                                            ? 'active'
                                            : null
                                        }}">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @auth
                            <div class="nav-item dropdown mr-auto">
                                <a
                                    class="nav-link py-2 dropdown-toggle"
                                    href="#"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    {{ $user->username }}@jfestbali.com
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header">Account</h6></li>
                                    <li><a class="dropdown-item" href="#">My Profile</a></li>
                                    <li><a class="dropdown-item" href="#">Change My Password</a></li>
                                    <li><h6 class="dropdown-header">Dangerous</h6></li>
                                    <li><a class="dropdown-item text-danger" href="{{ $auth_url['revoke'] }}">Sign Out</a></li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>
            <div class="container d-flex flex-column gap-4 pt-4 pb-5">@yield("content")</div>
        </div>
        @if (Illuminate\Support\Facades\Session::has('message'))
            <div class="toast-container position-fixed top-0 end-0 m-3">
                <div
                    class="toast"
                    role="alert"
                    aria-live="polite"
                    aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">System</strong>
                        <small class="text-body-secondary">just now</small>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="toast"
                            aria-label="close"></button>
                    </div>
                    <div class="toast-body">
                        {{ Illuminate\Support\Facades\Session::get('message') }}
                    </div>
                </div>
            </div>
        @endif

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
            crossorigin="anonymous"></script>
        <script>
            const tooltipTriggerList = document.querySelector('[data-bs-toggle="tooltip"]');
            tooltipTriggerList && [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

            const toastElemList = document.querySelectorAll(".toast");
            const toastList = [...toastElemList].map((el) => {
                new bootstrap.Toast(el, {}).show();
            });

            const refreshButton = document.getElementById("refresh-button");

            if (document.body.contains(refreshButton)) {
                refreshButton.addEventListener("click", () => {
                    window.location.reload();
                });
            }
        </script>
        @yield("scripts")
    </body>
</html>
