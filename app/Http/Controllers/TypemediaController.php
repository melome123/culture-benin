<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Typemedia;
use Yajra\DataTables\Facades\DataTables;

class TypemediaController extends Controller
{
    public function list()
    {
        return view('admin.typemedias');
    }

    public function data()
    {
        $query = Typemedia::select('id', 'nomtypem');

        return DataTables::of($query)
            ->addColumn('actions', function ($item) {
                $id = $item->id;
                return "<a href='/admin/typemedias/".$id."' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a>"
                     ."<a href='/admin/typemedias/".$id."/edit' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil'></i></a>"
                     ."<button data-id='".$id."' class='btn btn-sm btn-outline-danger btn-delete'><i class='bi bi-trash'></i></button>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate(['nomtypem' => 'required']);
        Typemedia::create($request->all());
        return redirect()->route('admin.typemedias');
    }

    public function show($id)
    {
        $t = Typemedia::findOrFail($id);
        return response()->json([
            'id' => $t->id,
            'nomtypem' => $t->nomtypem,
        ]);
    }

    public function update(Request $request, $id)
    {
        $u = Typemedia::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.typemedias');
    }

    public function destroy($id)
    {
        Typemedia::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
 

