<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view('admin.artikel.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.artikel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'cover' => ['required', 'mimes:png,jpg,jpeg'],
            'description' => 'required',
        ]);

        $path = $request->file('cover')->store('covers', 'public');

        Article::create([
            'title' => $request->title,
            'cover' => $path,
            'description' => $request->description,
        ]);

        return back()->with([
            'message' => 'Successfully added article',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.artikel.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $article = Article::findOrFail($id);
        $article->title = $request->title;
        $article->description = $request->description;

        if ($request->file('cover')) {
            Storage::delete('public/' . $article->cover);
            $article->cover = $request->file('cover')->store('covers', 'public');
        }

        $article->save();
        return back()->with([
            'message' => 'Successfully updated the article',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        Storage::delete('public/' . $article->cover);
        $article->delete();
        return back()->with([
            'message' => 'Successfully deleted article',
        ]);
    }
}
