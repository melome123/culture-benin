<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typecontenu;
use Yajra\DataTables\Facades\DataTables;

class TypecontenuController extends Controller
{
    public function list()
    {
        return view('admin.typecontenus');
    }

    public function data()
    {
        $query = Typecontenu::select('id', 'nomtypec');

        return DataTables::of($query)
            ->addColumn('actions', function ($item) {
                $id = $item->id;
                return "<a href='/admin/typecontenus/".$id."' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a>"
                     ."<a href='/admin/typecontenus/".$id."/edit' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil'></i></a>"
                     ."<button data-id='".$id."' class='btn btn-sm btn-outline-danger btn-delete'><i class='bi bi-trash'></i></button>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate(['nomtypec' => 'required']);
        Typecontenu::create($request->all());
        return redirect()->route('admin.typecontenus');
    }

    public function show($id)
    {
        $t = Typecontenu::findOrFail($id);
        return response()->json([
            'id' => $t->id,
            'nomtypec' => $t->nomtypec,
        ]);
    }

    public function update(Request $request, $id)
    {
        $u = Typecontenu::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.typecontenus');
    }

    public function destroy($id)
    {
        Typecontenu::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
 

