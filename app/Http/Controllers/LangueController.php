<?php

namespace App\Http\Controllers;

use App\Models\Langue;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LangueController extends Controller
{
    public function list()
    {
        return view('admin.langues');
    }

    public function data()
{
    // Sélectionner les colonnes avec Eloquent
    $query = Langue::select('id', 'nomlang', 'description', 'codelang');

    return DataTables::of($query)
        ->addColumn('actions', function ($langue) {
            return view('partials.actions', compact('langue'))->render();
        })
        ->rawColumns(['actions'])
        ->make(true);
}
    public function store(Request $request)
    {
        $request->validate(['nomlang' => 'required','codelang' => 'required','description' => 'required']);
        Langue::create($request->all());
        return redirect()->route('admin.langues');
    }
public function show($id)
{
    $langue = Langue::findOrFail($id);

    // Retourner les données en JSON
    return response()->json([
        'id' => $langue->id,
        'nomlang' => $langue->nomlang,
        'codelang' => $langue->codelang,
        'description' => $langue->description,
    ]);
}

    public function update(Request $request, $id)
    {
        $u =Langue::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.langues');
    }
    public function destroy($id)
    {
        Langue::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
