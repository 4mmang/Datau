<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikel = Article::all();
        return view('artikel.index', compact('artikel'));
    }

    public function show($id)
    {
        $artikel = Article::findOrFail($id);
        return view('artikel.show', compact('artikel'));
    }
}
