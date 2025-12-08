<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Typemedia;
use App\Models\Contenu;
use Yajra\DataTables\Facades\DataTables;

class MediaController extends Controller
{
    public function list()
    {
        $typemedias = Typemedia::pluck('nomtypem', 'id');
        $contenus = Contenu::pluck('titre', 'id');
        return view('admin.medias', compact('typemedias', 'contenus'));
    }

    public function data()
    {
        $query = Media::select('id', 'chemin', 'description', 'typemedia_id', 'contenu_id');

        return DataTables::of($query)
            ->addColumn('actions', function ($item) {
                $id = $item->id;
                return "<a href='/admin/medias/".$id."' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a>"
                     ."<a href='/admin/medias/".$id."/edit' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil'></i></a>"
                     ."<button data-id='".$id."' class='btn btn-sm btn-outline-danger btn-delete'><i class='bi bi-trash'></i></button>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate(['chemin' => 'required']);
        Media::create($request->all());
        return redirect()->route('admin.medias');
    }

    public function show($id)
    {
        $m = Media::findOrFail($id);
        return response()->json([
            'id' => $m->id,
            'chemin' => $m->chemin,
            'description' => $m->description,
            'typemedia_id' => $m->typemedia_id,
            'contenu_id' => $m->contenu_id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $u = Media::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.medias');
    }

    public function destroy($id)
    {
        Media::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
 

