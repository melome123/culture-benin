<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Langue;
use App\Models\Region;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        $langues = Langue::pluck('nomlang', 'id');
        $regions = Region::pluck('nom', 'id');
        return view('auth.register', compact('langues', 'regions'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'date_naissance' => 'required|date',
            'langue_id' => 'required|integer|exists:langues,id',
            'region_id' => 'required|integer|exists:regions,id',
        ]);

        $user = User::create([
            'nom' => $request->input('name'),
            'prenom' => $request->input('prenom'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => 4,
            'date_naissance' => $request->input('date_naissance'),
            'langue_id' => $request->input('langue_id'),
            'region_id' => $request->input('region_id'),
            'statut' => 'active',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
