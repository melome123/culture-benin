<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenu;
use App\Models\Langue;
use App\Models\Region;
use App\Models\Typecontenu;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContenuRejete;

class ContenuController extends Controller
{
    public function list()
    {
        $langues = Langue::pluck('nomlang', 'id');
        $regions = Region::pluck('nom', 'id');
        $typecontenus = Typecontenu::pluck('nomtypec', 'id');
        $users = User::pluck('nom', 'id');
        return view('admin.contenus', compact('langues', 'regions', 'typecontenus', 'users'));
    }

    /**
     * Moderation view for contenus
     */
    public function moderation()
    {
        $langues = Langue::pluck('nomlang', 'id');
        $regions = Region::pluck('nom', 'id');
        $typecontenus = Typecontenu::pluck('nomtypec', 'id');
        $users = User::pluck('nom', 'id');
        return view('moderation.contenus', compact('langues', 'regions', 'typecontenus', 'users'));
    }

    public function dashboard() {
    $contenus = Contenu::where('statut','active')->get();
    return view('dashboard', compact('contenus'));
}


    /**
     * Data endpoint for moderation DataTable
     */
    public function dataModeration()
    {
        $query = Contenu::select('id', 'titre', 'statut', 'date_valid', 'created_at', 'user_id');

        return DataTables::of($query)
            ->addColumn('email', function ($item) {
                $user = User::find($item->user_id);
                return $user ? $user->email : 'Unknown';
            })
            ->addColumn('actions', function ($item) {
                $id = $item->id;
                return "<a href='/moderation/contenus/".$id."/verdict' class='btn btn-sm btn-primary btn-verdict'><i class='bi bi-file-earmark-text'></i> Verdict</a>";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the verdict page where moderator can read contenu before deciding
     */
    public function verdict($id)
    {
        $c = Contenu::with('user')->findOrFail($id);
        return view('moderation.contenu_verdict', ['contenu' => $c]);
    }

    /**
     * Approve a contenu (set statut to 'validé')
     */
    public function approve($id)
    {
        $c = Contenu::findOrFail($id);
        $c->statut = 'validé';
        $c->date_valid = now();
        $c->save();
        return response()->json(['success' => true, 'id' => $c->id]);
    }

    /**
     * Reject a contenu (set statut to 'rejeté' and notify author)
     */
    public function reject(Request $request, $id)
    {
        $c = Contenu::findOrFail($id);
        $c->statut = 'rejeté';
        $c->save();

        $reason = $request->input('reason');

        $user = $c->user;
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new ContenuRejete($c, $reason));
            } catch (\Exception $e) {
                logger()->error('Failed sending rejection email for contenu '.$c->id.': '.$e->getMessage());
            }
        }

        return response()->json(['success' => true, 'id' => $c->id]);
    }
     public function index(Request $request)
    {
        $query = Contenu::query()
            ->where('statut', 'validé')
            ->with(['user', 'region', 'typecontenu', 'medias'])
            ->latest();
            
        // Filtres
        if ($request->has('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        
        if ($request->has('typecontenu_id')) {
            $query->where('typecontenu_id', $request->typecontenu_id);
        }
        
        if ($request->has('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('texte', 'like', '%' . $request->search . '%');
        }
        
        $contenus = $query->paginate(12);
        $regions = Region::all();
        $types = TypeContenu::all();
        
        return view('contenus.index', compact('contenus', 'regions', 'types'));
    }
    
    public function show($slug)
    {
        $contenu = Contenu::where('slug', $slug)
            ->where('statut', 'validé')
            ->with(['user', 'region', 'typecontenu', 'langue', 'medias.typemedia', 'commentaires.user'])
            ->firstOrFail();
            
        // Articles similaires (même région ou type)
        $articlesSimilaires = Contenu::where('statut', 'validé')
            ->where('id', '!=', $contenu->id)
            ->where(function($q) use ($contenu) {
                $q->where('region_id', $contenu->region_id)
                  ->orWhere('typecontenu_id', $contenu->typecontenu_id);
            })
            ->with(['user', 'medias'])
            ->limit(5)
            ->get();
            
        return view('contenus.show', compact('contenu', 'articlesSimilaires'));
    }

  public function data()
{
    $query = Contenu::select('id', 'titre', 'statut', 'date_valid', 'created_at', 'user_id');

    return DataTables::of($query)
        ->editColumn('date_valid', fn($contenu) => $contenu->date_valid?->format('Y-m-d'))
        ->editColumn('created_at', fn($contenu) => $contenu->date_valid?->format('Y-m-d'))
        ->addColumn('actions', function ($item) {
            $id = $item->id;
            return "<a href='/admin/contenus/".$id."' class='btn btn-sm btn-outline-secondary'><i class='bi bi-eye'></i></a>"
                 ."<a href='/admin/contenus/".$id."/edit' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil'></i></a>"
                 ."<button data-id='".$id."' class='btn btn-sm btn-outline-danger btn-delete'><i class='bi bi-trash'></i></button>";
        })
        ->rawColumns(['actions'])
        ->make(true);
}


            public function store(Request $request)
    {
        $request->validate(['titre' => 'required', 'texte' => 'required']);
        $data = $request->all();
        Contenu::create($data);
        return redirect()->route('admin.contenus');
    }

    public function show1($id)
    {
        $c = Contenu::findOrFail($id);
        return response()->json([
            'id' => $c->id,
            'titre' => $c->titre,
            'texte' => $c->texte,
            'date_valid' => $c->date_valid,
            'langue_id' => $c->langue_id,
            'region_id' => $c->region_id,
            'typecontenu_id' => $c->typecontenu_id,
            'user_id' => $c->user_id,
            'statut' => $c->statut,
        ]);
    }

    public function update(Request $request, $id)
    {
        $u = Contenu::findOrFail($id);
        $u->update($request->all());
        return redirect()->route('admin.contenus');
    }

    public function destroy($id)
    {
        Contenu::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }


}


