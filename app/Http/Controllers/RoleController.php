<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function list()
    {
        return view('admin.roles');
    }

    public function data()
    {
        $query = Role::select('id', 'nomrole');

        return DataTables::of($query)
            ->addColumn('actions', function ($role) {
                $id = $role->id;
                return "
<a href='/admin/roles/".$id."' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a>
<a href='/admin/roles/".$id."/edit' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil'></i></a>
<button data-id='".$id."' class='btn btn-sm btn-outline-danger btn-delete'><i class='bi bi-trash'></i></button>
";
            })
                ->rawColumns(['actions'])
                ->make(true);
            }

            public function store(Request $request)
    {
        $request->validate(['nomrole' => 'required']);
        Role::create($request->all());
        return redirect()->route('admin.roles');
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'id' => $role->id,
            'nomrole' => $role->nomrole,
        ]);
    }

    public function update(Request $request, $id)
    {
        $u = Role::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.roles');
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}





