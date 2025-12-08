<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contenu;
use App\Models\Media;

class DashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $u= User::count();
        $c = Contenu::count();
        $m = Media::count();
        return view('admin.dashboard',compact('u','c','m'));
    }
}