<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    public function list()
    {
        return view('admin.regions');
    }

        public function data()
        {
                // Sélectionner les colonnes avec Eloquent
                $query = Region::select('id', 'nom', 'description', 'population', 'superficie');

                return DataTables::of($query)
                        ->addColumn('actions', function ($region) {
                                // Render action buttons inline to avoid relying on a partial that may be missing
                                $id = $region->id;
                                return "
<button class='btn btn-outline-secondary' data-bs-toggle='offcanvas' data-bs-target='#offcanvasRegion' data-mode='read' data-id='$id'>
    <i class='bi bi-eye'></i>
</button>

<button class='btn btn-primary' data-bs-toggle='offcanvas' data-bs-target='#offcanvasRegion' data-mode='edit' data-id='$id'>
    <i class='bi bi-pencil'></i>
</button>

<button class='btn btn-sm btn-outline-danger btn-delete' data-id='$id' data-bs-toggle='modal' data-bs-target='#deleteModal'>
    <i class='bi bi-trash'></i>
</button>
";
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
        }
    public function store(Request $request)
    {
        $request->validate(['nom' => 'required','population' => 'required','description' => 'required', 'superficie' => 'required']);
        Region::create($request->all());
        return redirect()->route('admin.regions');
    }
public function show($id)
{
    $region = Region::findOrFail($id);

    // Retourner les données en JSON
    return response()->json([
        'id' => $region->id,
        'nom' => $region->nom,
        'population' => $region->population,
        'description' => $region->description,
        'superficie' => $region->superficie,
    ]);
}

    public function update(Request $request, $id)
    {
        $u =Region::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.regions');
    }
    public function destroy($id)
    {
        Region::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
