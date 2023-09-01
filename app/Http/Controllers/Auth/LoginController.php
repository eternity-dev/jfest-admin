<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginStoreRequest;
use App\Models\Organizer;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = null;
        $currentStep = $request->query("state", "step-one");

        if ($currentStep == "step-one") {
            return view('auth.login', [
                ...$this->withLinks([]),
                ...$this->withMetadata(['current_step' => $currentStep])
            ]);
        }

        if (!($username = $request->query('username', false))) {
            return to_route('auth.attempt.index', ['state' => 'step-one']);
        }

        try {
            $currentUser = Organizer::where('username', $username)->firstOrFail();

            return view('auth.login', [
                'current_user' => $currentUser,
                ...$this->withLinks([]),
                ...$this->withMetadata(['current_step' => $currentStep])
            ]);
        } catch (ModelNotFoundException $_) { return to_route('auth.attempt.index', ['state' => 'step-one']); }
    }

    public function store(LoginStoreRequest $request)
    {
        $data = $request->validated();

        switch ($request->query("state", "step-one")) {
            case "step-one":
                return to_route("auth.attempt.index", [
                    "state" => "step-two",
                    "username" => $data['username']
                ]);
            case "step-two":
                try {
                    $user = Organizer::select(['username', 'password'])
                        ->where('username', $data['username'])
                        ->firstOrFail();

                    $userCredentials = [
                        'username' => $user->username,
                        'password' => $data['password']
                    ];

                    if (!Auth::attempt($userCredentials)) {
                        return redirect()
                            ->back()
                            ->withErrors(['password' => sprintf('Incorrect password for %s', $user->username)]);
                    }

                    return to_route(RouteServiceProvider::HOME);
                } catch (ModelNotFoundException $_) { return to_route('auth.attempt.index', ['state' => 'step-one']); }
        }
    }
}
