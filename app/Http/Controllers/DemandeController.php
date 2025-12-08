<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Demande;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemandeApprouvee;
use App\Mail\DemandeRejetee;

class DemandeController extends Controller
{
    public function list()
    {
        return view('admin.demandes');
    }

public function data()
{
    $query = Demande::with('user') // ← important
        ->where('statut', 'en attente')
        ->select('id', 'user_id', 'date_demande', 'statut');

    return DataTables::of($query)
        ->editColumn('date_demande', fn($demande) => is_string($demande->date_demande) ? \Carbon\Carbon::parse($demande->date_demande)->format('Y-m-d') : $demande->date_demande?->format('Y-m-d'))
        ->addColumn('nom', fn($demande) => $demande->user?->nom)
        ->addColumn('prenom', fn($demande) => $demande->user?->prenom)
        ->addColumn('email', fn($demande) => $demande->user?->email)
        ->addColumn('actions', function ($item) {
            $id = $item->id;
            return "
<button class='btn btn-sm btn-success btn-approve' data-id='{$id}'>
  <i class='bi bi-check-circle'></i> Approuver
</button>
<button class='btn btn-sm btn-danger btn-reject' data-id='{$id}'>
  <i class='bi bi-x-circle'></i> Rejeter
</button>
            ";
        })
        ->rawColumns(['actions'])
        ->make(true);
}



    public function store(Request $request)
    {
        $request->validate(['user_id' => 'required']);
        $request->merge(['date_demande' => now(), 'statut' => 'en attente']);
        Demande::create($request->all());
        return redirect()->route('admin.demandes');
    }

    public function approve($id)
    {
        $demande = Demande::findOrFail($id);
        $user = $demande->user;

        $user->update(['role_id' => 3, 'statut' => 'active']);
        $demande->update(['statut' => 'approuvée']);

        Mail::to($user->email)->send(new DemandeApprouvee($user));

        return response()->json(['success' => true, 'message' => 'Demande approuvée et email envoyé']);
    }

    public function reject($id)
    {
        $demande = Demande::findOrFail($id);
        $user = $demande->user;

        $user->update(['statut' => 'rejected']);
        $demande->update(['statut' => 'rejetée']);

        Mail::to($user->email)->send(new DemandeRejetee($user));

        return response()->json(['success' => true, 'message' => 'Demande rejetée et email envoyé']);
    }
}
