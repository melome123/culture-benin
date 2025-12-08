<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commentaire;
use App\Models\Contenu;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class CommentaireController extends Controller
{
    public function list()
    {
        $contenus = Contenu::pluck('titre', 'id');
        $users = User::pluck('nom', 'id');
        return view('admin.commentaires', compact('contenus', 'users'));
    }

    /**
     * Show moderation view (for moderators) with DataTable
     */
    public function moderation()
    {
        $contenus = Contenu::pluck('titre', 'id');
        $users = User::pluck('nom', 'id');
        return view('moderation.comments', compact('contenus', 'users'));
    }

    /**
     * Validate (approve) a comment
     */
    public function validateComment($id)
    {
        $c = Commentaire::findOrFail($id);
        $c->statut = 'active';
        $c->published_at = $c->published_at ?? now();
        $c->save();
        return response()->json(['success' => true, 'id' => $c->id]);
    }

    public function data()
    {
        $query = Commentaire::select('id', 'texte', 'published_at', 'user_id', 'idcontenu','statut');

        return DataTables::of($query)
            ->addColumn('actions', function ($item) {
                $id = $item->id;
                return "<a href='/admin/commentaires/".$id."' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a>"
                     ."<a href='/admin/commentaires/".$id."/edit' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil'></i></a>"
                     ."<button data-id='".$id."' class='btn btn-sm btn-outline-danger btn-delete'><i class='bi bi-trash'></i></button>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function data1()
    {
        $query = Commentaire::select('id', 'texte', 'published_at', 'user_id', 'contenu_id');

        return DataTables::of($query)
            ->addColumn('email', function ($item) {
                $user = User::find($item->user_id);
                return $user ? $user->email : 'Unknown';
            })
            ->addColumn('contenu_titre', function ($item) {
                $contenu = Contenu::find($item->contenu_id);
                return $contenu ? $contenu->titre : 'Unknown';
            })
            ->addColumn('actions', function ($item) {
                $id = $item->id;
                return "<a href='/admin/commentaires/".$id."' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a>"
                     ."<a href='/admin/commentaires/".$id."/edit' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil'></i></a>"
                     ."<button data-id='".$id."' class='btn btn-sm btn-outline-danger btn-delete'><i class='bi bi-trash'></i></button>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate(['texte' => 'required']);
        Commentaire::create($request->all());
        return redirect()->route('admin.commentaires');
    }

    /**
     * Store a public comment for a contenu (used by site users)
     */
    public function storeForContenu(Request $request, $contenuId)
    {
        $request->validate(['texte' => 'required|string|max:2000']);

        $comment = Commentaire::create([
            'texte' => $request->input('texte'),
            'published_at' => now(),
            'idcontenu' => $contenuId,
            'note'=> $request->input('note', null),
            'user_id' => $request->user()?->id,
        ]);

        // If request expects JSON (AJAX), return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'texte' => $comment->texte,
                    'published_at' => $comment->published_at?->format('Y-m-d H:i'),
                    'user' => $comment->user?->nom ?? $request->user()?->nom ?? 'Anonyme',
                ]
            ]);
        }

        return redirect()->back();
    }

    public function show($id)
    {
        $c = Commentaire::findOrFail($id);
        return response()->json([
            'id' => $c->id,
            'texte' => $c->texte,
            'published_at' => $c->published_at,
            'idcontenu' => $c->idcontenu,
            'user_id' => $c->user_id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $u = Commentaire::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.commentaires');
    }

    public function destroy($id)
    {
        Commentaire::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
 

