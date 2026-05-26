<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth')]
#[Middleware('permission:view articles')]
class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     * Accessible by: admin, editor, viewer (all have 'view articles')
     */
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     * Accessible by: admin, editor (both have 'create articles')
     */
    #[Middleware('permission:create articles')]
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created article.
     * Accessible by: admin, editor
     */
    #[Middleware('permission:create articles')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
        ]);

        $request->user()->articles()->create($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified article.
     * Accessible by: admin, editor, viewer (all have 'view articles')
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing an article.
     * Accessible by: admin, editor (both have 'edit articles')
     */
    #[Middleware('permission:edit articles')]
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified article.
     * Accessible by: admin, editor
     */
    #[Middleware('permission:edit articles')]
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
        ]);

        $article->update($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified article.
     * Accessible by: admin only (only admin has 'delete articles')
     */
    #[Middleware('permission:delete articles')]
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
