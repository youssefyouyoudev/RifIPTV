<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_country_code' => ['required', 'string', 'regex:/^\+\d{1,4}$/'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9\s\-]{6,20}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_country_code' => $request->phone_country_code,
            'phone_number' => preg_replace('/\s+/', '', (string) $request->phone_number),
            'role' => 'client',
            'password' => Hash::make($request->password),
        ]);

        Client::create([
            'user_id' => $user->id,
            'assigned_admin_id' => User::query()->where('role', 'admin')->value('id'),
            'phone' => $request->phone_country_code.preg_replace('/\s+/', '', (string) $request->phone_number),
            'onboarding_status' => 'new',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('onboarding.show', absolute: false));
    }
}
