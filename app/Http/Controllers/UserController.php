<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Region;
use App\Models\Langue;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function list()
    {

        return view('admin.users');
    }

    public function data(){

        $query = User::with(['role','langue','region'])
          ->select('id','nom','prenom','date_naissance','role_id','langue_id','region_id');

        return DataTables::of($query)
          ->editColumn('date_naissance', fn($user) => $user->date_naissance?->format('Y-m-d'))
          ->addColumn('role', fn($user) => $user->role?->nomrole)
          ->addColumn('langue', fn($user) => $user->langue?->nomlang)
          ->addColumn('region', fn($user) => $user->region?->nom)
          ->make(true);
   }



    public function create()
    {
        $langues = Langue::pluck('nomlang','id');
        $regions = Region::pluck('nom','id');
        $roles = Role::pluck('nomrole','id');
        return view('admin.createusers', compact('regions','roles','langues'));
    }
     public function edit($id)
    {
        $langues = Langue::pluck('nomlang','id');
        $regions = Region::pluck('nom','id');
        $roles = Role::pluck('nomrole','id');
        $u = User::findOrFail($id);
        return view('admin.editusers', compact('regions','roles','langues','u'));
    }

    public function show($id)
    {
        $u = User::findOrFail($id);
        return view('admin.showusers', compact('u'));
    }
    
     public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'date_naissance' => 'required|date',
            'role_id' => 'required|integer',
            'langue_id'=> 'required|integer',
            'region_id' => 'required|integer',
        ]);

        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'date_naissance' => $request->date_naissance,
            'langue_id' => $request->langue_id,
            'region_id' => $request->region_id,
            'statut' => 'active',
        ]);

        return redirect()->back()->with('success','User ajoutée');
    }


    public function update(Request $request, User $u)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => [
        'required',
        'email',
        Rule::unique('users')->ignore($u->id),
    ],
        'date_naissance' => 'nullable|date',
        'region_id' => 'nullable|exists:regions,id',
        'langue_id' => 'nullable|exists:langues,id',
        'role_id' => 'nullable|exists:roles,id',
        'password' => 'nullable|string|min:8', // mot de passe facultatif
    ]);

    // Mise à jour des champs sauf mot de passe
    $u->nom = $validated['nom'];
    $u->prenom = $validated['prenom'];
    $u->email = $validated['email'];
    $u->date_naissance = $validated['date_naissance'] ?? $u->date_naissance;
    $u->region_id = $validated['region_id'] ?? $u->region_id;
    $u->langue_id = $validated['langue_id'] ?? $u->langue_id;
    $u->role_id = $validated['role_id'] ?? $u->role_id;

    // Mise à jour du mot de passe uniquement si rempli
    if (!empty($validated['password'])) {
        $u->password = Hash::make($validated['password']);
    }

    $u->save();

    return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
}

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
